<?php
namespace Phreeagent;

/**
 * Class Transport
 *
 * Wrapper around requests with logging.
 *
 * @package Phreeagent
 */
class Transport
{
    public $debug = false;

    /**
     * GET request.
     *
     * @param $url
     * @param $headers
     * @param array $data
     * @return \Requests_Response
     * @throws Exception
     */
    public function get($url, $headers, $data = array())
    {
        return $this->request('get', $url, $headers, $data);
    }

    /**
     * POST request.
     *
     * @param $url
     * @param $headers
     * @param array $data
     * @return \Requests_Response
     * @throws Exception
     */
    public function post($url, $headers, $data = array())
    {
        return $this->request('post', $url, $headers, $data);
    }

    /**
     * PUT request.
     *
     * @param $url
     * @param $headers
     * @param array $data
     * @return \Requests_Response
     * @throw Exception
     */
    public function put($url, $headers, $data = array())
    {
        return $this->request('put', $url, $headers, $data);
    }

    /**
     * @param $method
     * @param $url
     * @param $headers
     * @param array $data
     * @return \Requests_Response
     * @throws \Exception
     */
    public function request($method, $url, $headers, $data = array())
    {
        if (method_exists('\Requests', $method)) {
            $response = \Requests::$method($url, $headers, $data);

            if ($this->debug) {
                $debug_message = sprintf(
                    "--- %s ---\nStatus: %s\nURL: %s\nBody: %s\nPost Data: %s\n",
                    date('c'),
                    $response->status_code,
                    $response->url,
                    $response->body,
                    json_encode($data)
                );

                echo $debug_message;
            }

            return $response;
        } else {
            throw new \Exception("HTTP method $method not supported");
        }
    }
}
