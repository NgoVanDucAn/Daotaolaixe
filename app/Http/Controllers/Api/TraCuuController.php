<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Cookie\CookieJar;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class TraCuuController extends Controller
{
    protected $client;
    protected $cookieJar;

    public function __construct()
    {
        $this->cookieJar = new CookieJar();
        $this->client = new Client([
            'cookies' => $this->cookieJar,
            'allow_redirects' => true,
        ]);
    }

    // Hàm lấy CAPTCHA
    public function getCaptcha(Request $request)
    {
        try {
            $response = $this->client->get('https://www.csgt.vn/tra-cuu-phuong-tien-vi-pham.html', [
                'headers' => [
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/118.0.0.0 Safari/537.36',
                ]
            ]);
    
            $response = $this->client->get('https://www.csgt.vn/lib/captcha/captcha.class.php', [
                'headers' => [
                    'Referer' => 'https://www.csgt.vn/tra-cuu-phuong-tien-vi-pham.html',
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/118.0.0.0 Safari/537.36',
                ]
            ]);

            // Lưu hình ảnh CAPTCHA
            $captchaImage = (string) $response->getBody();
            file_put_contents(public_path('captcha.png'), $captchaImage);

            return response()->json([
                'captcha_url' => asset('captcha.png'),
                'ipClient' => $request->ip(),
                'cookies' => $this->cookieJar->toArray(),
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to retrieve CAPTCHA: ' . $e->getMessage());
            return response()->json(['error' => 'Không thể lấy CAPTCHA: ' . $e->getMessage()], 500);
        }
    }

    // Hàm xử lý tra cứu thông tin
    public function postTraCuu(Request $request)
    {
        // Xác thực dữ liệu đầu vào
        $validator = Validator::make($request->all(), [
            'BienKiemSoat' => 'required|string',
            'LoaiXe' => 'required|in:1,2,3',
            'txt_captcha' => 'required|string',
            'ipClient' => 'nullable|ip',
            'cUrl' => 'nullable|url',
            'cookies' => 'nullable|array', // Nhận cookie từ FE
        ]);

        if ($validator->fails()) {
            Log::warning('Validation failed: ' . $validator->errors()->first());
            return response()->json(['error' => $validator->errors()->first()], 422);
        }

        $bienKiemSoat = $request->input('BienKiemSoat');
        $loaiXe = $request->input('LoaiXe');
        $captcha = $request->input('txt_captcha');
        $ipClient = $request->input('ipClient', $request->ip());
        $cUrl = $request->input('cUrl', "https://www.csgt.vn/tra-cuu-phuong-tien-vi-pham.html?&LoaiXe={$loaiXe}&BienKiemSoat={$bienKiemSoat}");

        // Khôi phục cookie từ FE
        $rawCookies = $request->input('cookies', []);
        $simplifiedCookies = [];

        foreach ($rawCookies as $cookie) {
            if (isset($cookie['Name']) && isset($cookie['Value'])) {
                $simplifiedCookies[$cookie['name']] = $cookie['value'];
            }
        }

        $cookieJar = CookieJar::fromArray($simplifiedCookies, 'csgt.vn');

        try {
            Log::info('Sending POST request to CSGT with data: ', [
                'BienKS' => $bienKiemSoat,
                'Xe' => $loaiXe,
                'captcha' => $captcha,
                'ipClient' => $ipClient,
                'cUrl' => $cUrl,
                'cookies' => $cookieJar,
            ]);

            $response = $this->client->post('https://www.csgt.vn/?mod=contact&task=tracuu_post&ajax', [
                'form_params' => [
                    'BienKS' => $bienKiemSoat,
                    'Xe' => $loaiXe,
                    'captcha' => $captcha,
                    'ipClient' => $ipClient,
                    'cUrl' => $cUrl,
                ],
                'cookies' => $cookieJar,
                'headers' => [
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/118.0.0.0 Safari/537.36',
                ],
            ]);

            if ($response->getStatusCode() == 200) {
                $rawResponse = (string) $response->getBody();
                Log::info('Raw Response from CSGT: ' . $rawResponse);

                // Loại bỏ khoảng trắng
                $cleanResponse = preg_replace('/\s+/', '', $rawResponse);
                $result = json_decode($cleanResponse, true);

                if (json_last_error() === JSON_ERROR_NONE && is_array($result)) {
                    if (isset($result['success']) && $result['success']) {
                        Log::info('Tra cứu thành công, href: ' . $result['href']);
                        $resultPage = $this->client->get($result['href'], [
                            'cookies' => $cookieJar,
                            'headers' => [
                                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/118.0.0.0 Safari/537.36',
                            ],
                        ]);
                        return response()->json([
                            'success' => true,
                            'href' => $result['href'],
                            'data' => (string) $resultPage->getBody(),
                        ]);
                    } else {
                        Log::warning('Tra cứu thất bại: ' . ($result['message'] ?? 'Mã xác nhận sai!'));
                        return response()->json([
                            'success' => false,
                            'error' => $result['message'] ?? 'Mã xác nhận sai!',
                        ], 400);
                    }
                } else {
                    Log::error('Phản hồi không phải JSON: ' . $rawResponse);
                    return response()->json([
                        'error' => 'Phản hồi không phải JSON',
                        'raw_response' => $rawResponse,
                    ], 500);
                }
            } elseif ($response->getStatusCode() == 404) {
                Log::warning('Yêu cầu không hợp lệ, có thể do CAPTCHA sai');
                return response()->json([
                    'success' => false,
                    'error' => 'Mã xác nhận sai hoặc yêu cầu không hợp lệ',
                ], 400);
            } else {
                Log::error('Lỗi tra cứu, mã trạng thái: ' . $response->getStatusCode());
                return response()->json([
                    'error' => 'Lỗi khi tra cứu',
                    'status_code' => $response->getStatusCode(),
                ], 500);
            }
        } catch (RequestException $e) {
            Log::error('Lỗi gửi yêu cầu: ' . $e->getMessage());
            return response()->json([
                'error' => 'Lỗi khi gửi yêu cầu: ' . $e->getMessage(),
                'status_code' => $e->getResponse() ? $e->getResponse()->getStatusCode() : null,
            ], 500);
        }
    }
}
