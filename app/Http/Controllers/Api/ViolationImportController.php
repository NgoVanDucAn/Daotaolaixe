<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LawBookmark;
use App\Models\LawBookmarkType;
use App\Models\LawTopic;
use App\Models\LawVehicleType;
use App\Models\LawViolation;
use App\Models\LawViolationRelation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ViolationImportController extends Controller
{
    public function deleteExistingData()
    {
        LawTopic::truncateTable();
        LawVehicleType::truncateTable();
        LawBookmarkType::truncateTable();
        LawViolation::truncateTable();
        LawBookmark::truncateTable();
        LawViolationRelation::truncateTable();
        return response()->json(['message' => 'All law-related data deleted successfully.']);
    }
    public function import(Request $request)
    {
        // Validate request
        $validator = Validator::make($request->all(), [
            'violations' => 'required|array',
            'topics' => 'required|array',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => 'Validation failed',
                'messages' => $validator->errors(),
            ], 400);
        }

        $violations = $request->input('violations');
        $topics = $request->input('topics');

        // Import data
        try {
            DB::transaction(function () use ($violations, $topics) {
                $topicsMap = $this->importTopics($topics);

                $vehiclesMap = $this->importVehicleTypes($violations);

                $bookmarkTypesMap = $this->importBookmarkTypes($violations);

                $violationsMap = $this->importViolations($violations, $topicsMap, $vehiclesMap);

                $this->importBookmarksAndRelations($violations, $violationsMap, $bookmarkTypesMap);

                $this->importViolationRelations($violations, $violationsMap);
            });

            return response()->json([
                'message' => 'Data imported successfully',
                'violation_records' => count($violations),
                'topic_records' => count($topics),
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Import failed: ' . $e->getMessage(),
            ], 500);
        }
    }

    private function importTopics($topics)
    {
        $topicsMap = [];

        foreach ($topics as $topic) {
            // Kiểm tra code hợp lệ
            if (!isset($topic['code']) || !is_numeric($topic['code']) || $topic['code'] <= 0) {
                continue; // Bỏ qua topic không hợp lệ
            }

            $lawTopic = LawTopic::create([
                'topic_name' => $topic['display'],
                'subtitle' => $topic['subTitle'] ?: null,
            ]);
            $topicsMap[$topic['code']] = $lawTopic->id;
        }

        return $topicsMap;
    }

    private function importVehicleTypes($violations)
    {
        $vehicleCodes = collect($violations)
            ->filter(function ($item) {
                return isset($item['code']) && is_numeric($item['code']) && $item['code'] > 0;
            })
            ->pluck('code')
            ->unique()
            ->sort()
            ->values();

        if ($vehicleCodes->isEmpty()) {
            throw new \Exception('No valid codes found in violations');
        }
    
        $vehiclesMap = [];
    
        foreach ($vehicleCodes as $code) {
            $firstViolation = collect($violations)->firstWhere('code', $code);
            $entities = $firstViolation['entities'] ?? '';
            $vehicleName = 'Khác';
    
            $entitiesLower = mb_strtolower($entities, 'UTF-8');
            if (str_contains($entitiesLower, 'xe máy') || str_contains($entitiesLower, 'mô tô')) {
                $vehicleName = 'Xe máy';
            } elseif (str_contains($entitiesLower, 'ô tô')) {
                $vehicleName = 'Ô tô';
            }
    
            $vehicleType = LawVehicleType::create([
                'vehicle_name' => $vehicleName,
            ]);
            $vehiclesMap[$code] = $vehicleType->id;
        }
    
        return $vehiclesMap;
    }

    private function importBookmarkTypes($violations)
    {
        $bookmarkTypes = collect($violations)
            ->flatMap(function ($item) {
                return collect($item['bookmarks'])->pluck('bookmarkType');
            })
            ->unique()
            ->sort()
            ->values();

        $bookmarkTypesMap = [];

        $bookmarkNameMap = [
            1 => 'Hình phạt',
            2 => 'Hình phạt bổ sung',
            3 => 'Ghi chú',
            4 => 'Hành vi liên quan',
        ];

        foreach ($bookmarkTypes as $type) {
            if (!isset($bookmarkNameMap[$type])) {
                continue;
            }

            $bookmarkType = LawBookmarkType::create([
                'bookmark_name' => $bookmarkNameMap[$type],
            ]);
            $bookmarkTypesMap[$type] = $bookmarkType->id;
        }
        return $bookmarkTypesMap;
    }

    private function importViolations($violations, $topicsMap, $vehiclesMap)
    {
        $nos = collect($violations)->pluck('no')->duplicates();
        if ($nos->isNotEmpty()) {
            throw new \Exception('Duplicate violation numbers: ' . $nos->implode(', '));
        }

        $violationsMap = [];
        foreach ($violations as $item) {
            if (!isset($item['no']) || !is_numeric($item['no']) || $item['no'] <= 0) {
                continue; // Bỏ qua bản ghi không hợp lệ
            }
    
            $violation = LawViolation::create([
                'violation_no' => $item['no'],
                'description' => $item['violation'],
                'entities' => $item['entities'],
                'fines' => $item['fines'],
                'additional_penalties' => $item['additionalPenalties'] ?: null,
                'remedial' => $item['remedial'] ?: null,
                'other_penalties' => $item['otherPenalties'] ?: null,
                'type_id' => $vehiclesMap[$item['code']],
                'topic_id' => $topicsMap[$item['topicCode']],
                'image' => $item['image'] ?: null,
                'keyword' => $item['keyword'] ?: null,
            ]);
            $violationsMap[$item['no']] = $violation->id;
        }
    
        return $violationsMap;
    }

    private function importBookmarksAndRelations($violations, $violationsMap, $bookmarkTypesMap)
    {
        $bookmarks = [];

        foreach ($violations as $item) {
            if (!isset($violationsMap[$item['no']]) || empty($item['bookmarks'])) {
                continue;
            }

            $violation = LawViolation::find($violationsMap[$item['no']]);
            if (!$violation) {
                continue;
            }

            foreach ($item['bookmarks'] as $bookmark) {
                $bookmarkCode = $bookmark['bookmarkCode'];
                $bookmarkType = $bookmark['bookmarkType'];
                $bookmarkDescription = $bookmark['bookmark'];
                $bookmarkTypeId = $bookmarkTypesMap[$bookmarkType];
                $bookmarkKey = "{$bookmarkCode}-{$bookmarkType}";

                if (!isset($bookmarks[$bookmarkKey])) {
                    $lawBookmark = LawBookmark::create([
                        'bookmark_code' => $bookmarkCode,
                        'bookmark_type_id' => $bookmarkTypeId,
                        'bookmark_description' => $bookmarkDescription,
                    ]);
                    $bookmarks[$bookmarkKey] = $lawBookmark->id;
                }

                // Ghi quan hệ nhiều-nhiều
                $violation->bookmarks()->syncWithoutDetaching([$bookmarks[$bookmarkKey]]);
            }
        }
    }

    private function importViolationRelations($violations, $violationsMap)
    {
        foreach ($violations as $item) {
            if (!isset($violationsMap[$item['no']]) || empty($item['relations'])) {
                continue;
            }
            $violationId = $violationsMap[$item['no']];
            foreach ($item['relations'] as $relatedNo) {
                if (isset($violationsMap[$relatedNo])) {
                    LawViolationRelation::create([
                        'violation_id' => $violationId,
                        'related_violation_id' => $violationsMap[$relatedNo],
                    ]);
                }
            }
        }
    }

    public function getAllLaws()
    {
        $topics = LawTopic::with([
            'violations.vehicleType',
            'violations.bookmarks.bookmarkType',
            'violations.relatedViolations',
        ])->orderBy('id')->get();

        $vehicleGroups = [];

        foreach ($topics as $topic) {
            foreach ($topic->violations as $violation) {
                $vehicleName = optional($violation->vehicleType)->vehicle_name ?? 'Không xác định';

                if (!isset($vehicleGroups[$vehicleName])) {
                    $vehicleGroups[$vehicleName] = [
                        'vehicle_type' => $vehicleName,
                        'topics' => [],
                    ];
                }

                // Xem topic đã có trong group này chưa
                $topicIndex = collect($vehicleGroups[$vehicleName]['topics'])->search(fn($t) => $t['id'] === $topic->id);

                $violationData = [
                    'id' => $violation->id,
                    'violation_no' => $violation->violation_no,
                    'description' => $violation->description,
                    'entities' => $violation->entities,
                    'fines' => $violation->fines,
                    'additional_penalties' => $violation->additional_penalties,
                    'remedial' => $violation->remedial,
                    'other_penalties' => $violation->other_penalties,
                    'image' => $violation->image,
                    'keyword' => $violation->keyword,
                    'vehicle_type' => $vehicleName,
                    'bookmarks' => $violation->bookmarks->map(function ($bookmark) {
                        return [
                            'bookmark_code' => $bookmark->bookmark_code,
                            'bookmark_description' => $bookmark->bookmark_description,
                            'bookmark_type_id' => $bookmark->bookmark_type_id,
                            'bookmark_type' => optional($bookmark->bookmarkType)->bookmark_name,
                        ];
                    }),
                    'relations' => $violation->relatedViolations->map(function ($related) {
                        return [
                            'violation_no' => $related->violation_no,
                            'description' => $related->description,
                        ];
                    }),
                ];

                if ($topicIndex === false) {
                    $vehicleGroups[$vehicleName]['topics'][] = [
                        'id' => $topic->id,
                        'name' => $topic->topic_name,
                        'subtitle' => $topic->subtitle,
                        'violations' => [$violationData],
                    ];
                } else {
                    $vehicleGroups[$vehicleName]['topics'][$topicIndex]['violations'][] = $violationData;
                }
            }
        }

        // Đảm bảo mỗi topic đều có trong mỗi group (dù không có violation)
        foreach ($topics as $topic) {
            foreach ($vehicleGroups as &$group) {
                $found = collect($group['topics'])->contains('id', $topic->id);
                if (!$found) {
                    $group['topics'][] = [
                        'id' => $topic->id,
                        'name' => $topic->topic_name,
                        'subtitle' => $topic->subtitle,
                        'violations' => [],
                    ];
                }
            }
        }

        // Reset chỉ mục
        $result = array_values($vehicleGroups);
        foreach ($result as &$group) {
            $group['topics'] = array_values($group['topics']);
        }

        return response()->json($result);
    }

    public function getViolationById($id)
    {
        $violation = LawViolation::with(['vehicleType', 'bookmarks.bookmarkType', 'relatedViolations'])->findOrFail($id);

        return response()->json([
            'id' => $violation->id,
            'no' => $violation->violation_no,
            'description' => $violation->description,
            'entities' => $violation->entities,
            'fines' => $violation->fines,
            'additional_penalties' => $violation->additional_penalties,
            'remedial' => $violation->remedial,
            'other_penalties' => $violation->other_penalties,
            'image' => $violation->image,
            'keyword' => $violation->keyword,
            'vehicle_type' => optional($violation->vehicleType)->vehicle_name,
            'bookmarks' => $violation->bookmarks->map(function ($bookmark) {
                return [
                    'id' => $bookmark->id,
                    'code' => $bookmark->bookmark_code,
                    'type' => optional($bookmark->bookmarkType)->bookmark_name,
                    'description' => $bookmark->bookmark_description,
                ];
            }),
            'relations' => $violation->relatedViolations->pluck('violation_no')->all(),
        ]);
    }

    public function updateViolation(Request $request, $id)
    {
        $violation = LawViolation::with(['bookmarks', 'relatedViolations'])->findOrFail($id);

        $violation->update($request->only([
            'description',
            'entities',
            'fines',
            'additional_penalties',
            'remedial',
            'other_penalties',
            'image',
            'keyword',
        ]));

        if ($request->has('vehicle_type_id')) {
            $violation->vehicle_type_id = $request->vehicle_type_id;
            $violation->save();
        }

        if ($request->has('bookmarks')) {
            $violation->bookmarks()->delete();

            foreach ($request->bookmarks as $bookmark) {
                $violation->bookmarks()->create([
                    'bookmark_code' => $bookmark['code'],
                    'bookmark_type_id' => $bookmark['type_id'] ?? null,
                    'bookmark_description' => $bookmark['description'] ?? null,
                ]);
            }
        }

        if ($request->has('related_violation_ids')) {
            $violation->relatedViolations()->sync($request->related_violation_ids);
        }

        $violation->load(['vehicleType', 'bookmarks.bookmarkType', 'relatedViolations']);

        return response()->json([
            'message' => 'Cập nhật thành công!',
            'data' => $violation
        ]);
    }
}
