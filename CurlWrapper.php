<?php
namespace Mezon\CustomClient;

/**
 * Class CurlWrapper
 *
 * @package CustomClient
 * @subpackage CurlWrapper
 * @author Dodonov A.A.
 * @version v.1.0 (2019/08/07)
 * @copyright Copyright (c) 2019, aeon.org
 */

/**
 * Wrapper for CURL routines
 */
class CurlWrapper
{

    /**
     * Method send HTTP request
     *
     * @param string $uRL
     *            URL
     * @param array $headers
     *            Headers
     * @param string $method
     *            Request HTTP Method
     * @param array $data
     *            Request data
     * @return array Response body and HTTP code
     */
    public static function sendRequest(string $uRL, array $headers, string $method, array $data = []): array
    {
        $ch = curl_init();

        $curlConfig = [
            CURLOPT_URL => $uRL,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_POST => ($method == 'POST'),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => true
        ];

        if ($method == 'POST') {
            $formData = [];
            foreach ($data as $key => $value) {
                $formData[] = $key . '=' . urldecode($value);
            }
            $curlConfig[CURLOPT_POSTFIELDS] = implode('&', $formData);
        }

        curl_setopt_array($ch, $curlConfig);

        $body = curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        curl_close($ch);

        return [
            $body,
            $code
        ];
    }
}
