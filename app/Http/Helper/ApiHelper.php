<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Controller;

class ApiHelper extends Controller
{
    public static function callExternalApi(string $url, array $params = [], string $method = 'GET', array $headers = []): array
    {
        try {
            // Costruzione dinamica del base_url
            $base_url = 'http://' . (new self)->ip->getValue() . ':' . (new self)->port->getValue() . '/api/';
            $full_url = $base_url . ltrim($url, '/');

            // Se non c'è un access token, prova a recuperarlo con il login
            if (!Session::has('access_token')) {
                $loginResponse = self::performLogin();
                if (!$loginResponse['success']) {
                    return [
                        'success' => false,
                        'status' => 401,
                        'error' => 'Unable to login: ' . $loginResponse['error'],
                    ];
                }
                $headers['Authorization'] = 'Bearer ' . $loginResponse['data']['access_token'];
            } else {
                $headers['Authorization'] = 'Bearer ' . Session::get('access_token');
            }

            $method = strtoupper($method);
            $request = Http::withHeaders($headers);

            switch ($method) {
                case 'POST': $response = $request->post($full_url, $params); break;
                case 'PUT': $response = $request->put($full_url, $params); break;
                case 'DELETE': $response = $request->delete($full_url, $params); break;
                case 'GET':
                default: $response = $request->get($full_url, $params); break;
            }

            if ($response->successful()) {
                return [
                    'success' => true,
                    'data' => $response->json(),
                ];
            } elseif ($response->status() === 401) {
                $refreshResponse = self::performRefreshToken();
                if ($refreshResponse['success']) {
                    $headers['Authorization'] = 'Bearer ' . $refreshResponse['data']['access_token'];
                    return self::callExternalApi($url, $params, $method, $headers);
                } else {
                    $loginResponse = self::performLogin();
                    if ($loginResponse['success']) {
                        $headers['Authorization'] = 'Bearer ' . $loginResponse['data']['access_token'];
                        return self::callExternalApi($url, $params, $method, $headers);
                    } else {
                        return [
                            'success' => false,
                            'status' => 401,
                            'error' => 'Unable to refresh or login: ' . $loginResponse['error'],
                        ];
                    }
                }
            } else {
                return [
                    'success' => false,
                    'status' => $response->status(),
                    'error' => $response->json()['msg'] ?? $response->body(),
                ];
            }
        } catch (\Exception $e) {
            return [
                'success' => false,
                'status' => 500,
                'error' => $e->getMessage(),
            ];
        }
    }

    private static function performRefreshToken(): array
    {
        $base_url = 'http://' . (new self)->ip->getValue() . ':' . (new self)->port->getValue() . '/api/';
        $url = $base_url . 'auth/token/refresh';
        $refreshToken = Session::get('refresh_token');
        if (!$refreshToken) {
            return [
                'success' => false,
                'status' => 401,
                'error' => 'Refresh token not available',
            ];
        }

        $headers = [
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $refreshToken,
        ];

        $response = Http::withHeaders($headers)->post($url);
        if ($response->successful()) {
            $responseData = $response->json();
            Session::put('access_token', $responseData['access_token']);
            return [
                'success' => true,
                'data' => $responseData,
            ];
        } else {
            return [
                'success' => false,
                'status' => $response->status(),
                'error' => $response->body(),
            ];
        }
    }

    private static function performLogin(): array
    {
        $base_url = 'http://' . (new self)->ip->getValue() . ':' . (new self)->port->getValue() . '/api/';
        $url = $base_url . 'auth/login';
        $credentials = [
            'username' => 'PiDevice1',
            'password' => 'password123',
        ];

        $response = Http::post($url, $credentials);
        if ($response->successful()) {
            $responseData = $response->json();
            Session::put('access_token', $responseData['access_token']);
            Session::put('refresh_token', $responseData['refresh_token']);
            return [
                'success' => true,
                'data' => $responseData,
            ];
        } else {
            return [
                'success' => false,
                'status' => $response->status(),
                'error' => $response->body(),
            ];
        }
    }
}
