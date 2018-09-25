<?php

namespace AppBundle\Client;

/**
 * Class HttpClient
 * @package AppBundle\Client
 */
class HttpClient
{
    /**
     * @param string $baseUrl
     * @param array $params
     *
     * @return mixed
     */
    public function fetch(string $baseUrl, array $params = [])
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $baseUrl.http_build_query($params));
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: application/json')); // Assuming you're requesting JSON
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        return curl_exec($ch);
    }
}
