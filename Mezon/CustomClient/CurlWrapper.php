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
     * Does the specified header exists
     *
     * @param array $headers
     *            list of headers to be analized
     * @param string $header
     *            header to be found
     * @return bool true if the header was found, false otherwise
     */
    public static function isHeaderExists(array $headers, string $header): bool
    {
        foreach ($headers as $header) {
            if (stripos($header, $header) !== false) {
                return true;
            }
        }

        return false;
    }

    /**
     * Method send HTTP request
     *
     * @param string $url
     *            URL
     * @param array $headers
     *            Headers
     * @param string $method
     *            Request HTTP Method
     * @param array $data
     *            Request data
     * @return array Response body and HTTP code
     */
    public static function sendRequest(string $url, array $headers, string $method, array $data = []): array
    {
        $ch = curl_init();

        $curlConfig = [
            CURLOPT_URL => $url,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_POST => ($method == 'POST'),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => true
        ];

        if ($method === 'POST') {
            if (self::isHeaderExists($headers, 'Content-type: application/json')) {
                $formData = json_encode($data);
            } else {
                $formData = [];
                foreach ($data as $key => $value) {
                    $formData[] = $key . '=' . urlencode($value);
                }
                $formData = implode('&', $formData);
            }

            $curlConfig[CURLOPT_POSTFIELDS] = $formData;
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
