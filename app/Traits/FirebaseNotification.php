<?php

namespace App\Traits;

use App\Models\Chat;
use App\Models\DeviceToken;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Support\Facades\Log;

trait FirebaseNotification
{

    public function sendFcm($data, $user_id = null,)
    {
        $apiUrl = 'https://fcm.googleapis.com/v1/projects/finak-application/messages:send';
        $accessToken = $this->getAccessToken();

        $deviceTokens = [];
        if ($user_id) {
            $deviceTokens = [DeviceToken::whereUserId($user_id)->first()->token];
            if ($data['type'] !== 'not_save') {

                $this->saveNotification($data, [$user_id]);
            }
        } elseif (isset($data['is_admin']) && $data['is_admin'] == 1) {
            $userIds = User::where('status', 1)
                ->where('user_type', 0)
                ->pluck('id')->toArray();
            $deviceTokens = DeviceToken::whereIn('user_id', $userIds)->pluck('token')->toArray();


            if ($data['type'] !== 'not_save') {
                $this->saveNotification($data, $userIds);
            }
        } else {
            $userIds = User::where('status', 1)
                ->where('id', '!=', auth('api')->user()->id);

            if ($data['type'] !== 'not_save' && isset($data['is_leader']) && $data['is_leader'] == 1) {
                $userIds = $userIds->where('user_type', 1);
            } else {
                $userIds = $userIds->where('user_type', 0);
            }

            $userIds = $userIds->pluck('id')->toArray();
            $deviceTokens = DeviceToken::whereIn('user_id', $userIds)->pluck('token')->toArray();

            if ($data['type'] !== 'not_save') {
                $this->saveNotification($data, $userIds);
            }
        }





        $responses = [];
        foreach ($deviceTokens as $token) {
            $payload = $this->preparePayload($data, $token);
            $response = $this->sendNotification($apiUrl, $accessToken, $payload);

            // Check and skip invalid tokens
            if (
                isset($response['response']['error']['message']) &&
                in_array($response['response']['error']['message'], ['UNREGISTERED', 'INVALID_ARGUMENT'])
            ) {
                // Delete the invalid token
                DeviceToken::where('token', $token)->delete();
                Log::warning("Deleted invalid FCM token: $token");
                continue;
            }

            $responses[] = $response;
        }

        return response()->json(['responses' => $responses]);

    }

    protected function saveNotification($data, $userIds)
    {
        foreach ($userIds as $userId) {
            Notification::create([
                'user_id' => $userId,
                'title' => $data['title'],
                'body' => $data['body'],
                'reference_id' => $data['reference_id'] ?? null,
                'reference_table' => $data['reference_table'] ?? null,
            ]);
        }
    }


    protected function getAccessToken()
    {
        $credentialsFilePath = storage_path('app/firebase/finak.json');
        $client = new \Google_Client();
        $client->setAuthConfig($credentialsFilePath);
        $client->addScope('https://www.googleapis.com/auth/firebase.messaging');
        $client->refreshTokenWithAssertion();

        return $client->getAccessToken()['access_token'];
    }

    protected function preparePayload($data, $token)
    {
        $message = [
            'notification' => [
                'title' => $data['title'],
                'body' => $data['body'],
            ],
            'data' => [
                'reference_id' => isset($data['reference_id']) ? (string)$data['reference_id'] : '',
                'reference_table' => isset($data['reference_table']) ? (string)$data['reference_table'] : '',
                'sender_name'=> isset($data['reference_table']) && $data['reference_table'] == 'rooms' ? (string)$data['title'] : null,
            ],
            'token' => $token,
        ];

        return json_encode(['message' => $message]);
    }
    protected function sendNotification($url, $accessToken, $payload)
    {
        $headers = [
            "Authorization: Bearer " . $accessToken,
            'Content-Type: application/json'
        ];



        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);

        $response = curl_exec($ch);
        if (curl_errno($ch)) {
            $error_msg = curl_error($ch);
            curl_close($ch);
            return ['error' => $error_msg];
        }

        $info = curl_getinfo($ch);
        curl_close($ch);

        return ['response' => json_decode($response, true), 'info' => $info];
    }
}
