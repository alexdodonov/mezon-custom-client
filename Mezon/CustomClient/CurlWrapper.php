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
     * @param string $requiredHeader
     *            header to be found
     * @return bool true if the header was found, false otherwise
     */
    public static function isHeaderExists(array $headers, string $requiredHeader): bool
    {
        foreach ($headers as $header) {
            if (stripos($header, $requiredHeader) !== false) {
                return true;
            }
        }

        return false;
    }

    /**
     * Method packs arrays
     *
     * @param string $fieldName
     *            field name
     * @param array $data
     *            array data to be packed
     * @return array packed array data
     */
    public static function packArray(string $fieldName, array $data): array
    {
        $formData = [];

        foreach ($data as $key => $value) {
            if (is_scalar($value)) {
                $formData[] = $fieldName . '[' . $key . ']=' . urlencode((string) $value);
            } elseif (is_array($value)) {
                $formData = array_merge($formData, self::packArray($fieldName . '[' . $key . ']', $value));
            } else {
                throw (new \Exception('Data type is not supported'));
            }
        }

        return $formData;
    }

    /**
     * Method packs data in string
     *
     * @param array $data
     *            data to be packed
     * @return string packed data
     */
    public static function packData(array $data): string
    {
        $formData = [];

        foreach ($data as $key => $value) {
            if (is_scalar($value)) {
                $formData[] = $key . '=' . urlencode((string) $value);
            } elseif (is_array($value)) {
                $formData = array_merge($formData, self::packArray($key, $value));
            } else {
                throw (new \Exception('Data type is not supported'));
            }
        }

        return implode('&', $formData);
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
                $formData = self::packData($data);
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
