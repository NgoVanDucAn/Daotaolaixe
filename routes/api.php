<?php

use App\Http\Controllers\Api\CourseController;
use App\Http\Controllers\Api\DataAppController;
use App\Http\Controllers\Api\FeeController;
use App\Http\Controllers\Api\Law\BookmarkController;
use App\Http\Controllers\Api\Law\BookmarkTypeController;
use App\Http\Controllers\Api\Law\LawTopicController;
use App\Http\Controllers\Api\Law\VehicleTypeController;
use App\Http\Controllers\Api\MaterialController;
use App\Http\Controllers\Api\RankingController;
use App\Http\Controllers\Api\StudentController;
use App\Http\Controllers\Api\TipsController;
use App\Http\Controllers\Api\TraCuuController;
use App\Http\Controllers\Api\TrafficSignController;
use App\Http\Controllers\Api\ViolationImportController;
use App\Http\Controllers\LessonController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::get('/courses/{id}', [CourseController::class, 'show']);
Route::patch('/students/update-shlx-trainee-id', [StudentController::class, 'updateShlxTraineeId']);
Route::get('/students', [StudentController::class, 'index']);
Route::get('/students/{id}', [StudentController::class, 'show']);
Route::put('/students/{id}/edit', [StudentController::class, 'update']);
Route::get('/students/{id}/courses', [StudentController::class, 'getCoursesOfStudent']);
Route::get('/students/{student}/schedules', [StudentController::class, 'getGroupedSchedules']);
Route::get('/data-app', [DataAppController::class, 'index']);
Route::post('/new-motobike', [DataAppController::class, 'newMotobike']);
Route::get('/data-exam', [DataAppController::class, 'examSets']);
Route::get('/students/{student_id}/fees', [FeeController::class, 'show']);
Route::get('/rankings', [RankingController::class, 'index']);
Route::get('/rankings/{id}', [RankingController::class, 'show']);
Route::post('/traffic-signs/bulk-store', [TrafficSignController::class, 'bulkStore']);
Route::get('/traffic-signs', [TrafficSignController::class, 'indexGroupedByType']);
Route::get('/chapter-materials', [MaterialController::class, 'index']);
Route::post('/chapter-materials/bulk', [MaterialController::class, 'bulkStore']);
Route::post('/simulations/generate', [MaterialController::class, 'generateSimulations']);
Route::get('/simulations', [MaterialController::class, 'allSimulations']);
Route::get('/captcha', [TraCuuController::class, 'getCaptcha']);
Route::post('/tra-cuu-phuong-tien', [TraCuuController::class, 'postTraCuu']);
Route::post('/violations/import', [ViolationImportController::class, 'import']);
Route::delete('/violations/delete', [ViolationImportController::class, 'deleteExistingData']);
Route::get('/laws', [ViolationImportController::class, 'getAllLaws']);
Route::get('/laws/violation/{id}', [ViolationImportController::class, 'getViolationById']);
Route::put('/laws/violation/{id}', [ViolationImportController::class, 'updateViolation']);
Route::apiResource('vehicle-types', VehicleTypeController::class)->except(['show']);
Route::apiResource('topics', LawTopicController::class)->except(['show']);
Route::apiResource('bookmark-types', BookmarkTypeController::class)->except(['show']);
Route::post('/violations/{violation}/bookmarks', [BookmarkController::class, 'store']);
Route::put('/bookmarks/{id}', [BookmarkController::class, 'update']);
Route::delete('/bookmarks/{id}', [BookmarkController::class, 'destroy']);
Route::get('/tips', [TipsController::class, 'getAllTips'])->name('api.tips.index');

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/lesson/clone-to-chapter/{chapterId}', [LessonController::class, 'cloneToChapter']);
