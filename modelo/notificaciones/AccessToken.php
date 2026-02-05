<?php
/**
 * Helper para generar Access Tokens de Google OAuth 2.0
 * para Firebase Cloud Messaging HTTP v1 API
 * Sin dependencias externas (Composer)
 */

class GoogleAccessToken
{
    private $serviceAccountPath;

    public function __construct($serviceAccountPath)
    {
        if (!file_exists($serviceAccountPath)) {
            throw new Exception("Archivo de cuenta de servicio no encontrado: $serviceAccountPath");
        }
        $this->serviceAccountPath = $serviceAccountPath;
    }

    public function getToken()
    {
        $credentials = json_decode(file_get_contents($this->serviceAccountPath), true);

        if (!$credentials) {
            throw new Exception("Error al leer el archivo JSON de credenciales");
        }

        $header = [
            'alg' => 'RS256',
            'typ' => 'JWT'
        ];

        $now = time();
        $payload = [
            'iss' => $credentials['client_email'],
            'sub' => $credentials['client_email'],
            'aud' => 'https://oauth2.googleapis.com/token',
            'iat' => $now,
            'exp' => $now + 3600,
            'scope' => 'https://www.googleapis.com/auth/firebase.messaging'
        ];

        $jwt = $this->encodeJWT($header, $payload, $credentials['private_key']);

        // Intercambiar JWT por Access Token
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://oauth2.googleapis.com/token');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
            'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
            'assertion' => $jwt
        ]));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if (curl_errno($ch)) {
            throw new Exception('Error CURL: ' . curl_error($ch));
        }
        curl_close($ch);

        if ($httpCode !== 200) {
            throw new Exception("Error al obtener token OAuth: $response");
        }

        $data = json_decode($response, true);
        return $data['access_token'];
    }

    private function encodeJWT($header, $payload, $privateKey)
    {
        $headerEncoded = $this->base64UrlEncode(json_encode($header));
        $payloadEncoded = $this->base64UrlEncode(json_encode($payload));

        $dataToSign = "$headerEncoded.$payloadEncoded";

        $signature = '';
        if (!openssl_sign($dataToSign, $signature, $privateKey, 'SHA256')) {
            throw new Exception("Error al firmar JWT");
        }

        $signatureEncoded = $this->base64UrlEncode($signature);

        return "$dataToSign.$signatureEncoded";
    }

    private function base64UrlEncode($data)
    {
        return str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($data));
    }
}
?>