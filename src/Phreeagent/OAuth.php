<?php
namespace Phreeagent;
use Phreeagent\Exception\InvalidRequestResponseException;

/**
 * Class OAuth
 *
 * @package Phreeagent
 */
class OAuth
{
    /** @var \Phreeagent\Config */
    protected $config;

    public $access_token;

    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    /**
     * Given a client id, client secret and a refresh token, request a new access token from the API.
     *
     * @return string
     * @throws InvalidRequestResponseException
     */
    public function getAccessToken()
    {
        if ($this->access_token == null) {
            $post_data = array(
                'client_id'     => $this->config->client_id,
                'client_secret' => $this->config->client_secret,
                'refresh_token' => $this->config->refresh_token,
                'grant_type'    => 'refresh_token',
            );

            $response = $this->config->transport->post(
                'https://api.freeagent.com/v2/token_endpoint',
                array(),
                $post_data
            );

            if (!$response->success) {
                throw InvalidRequestResponseException::factory(
                    $response,
                    "Could not fetch access token, are your credentials correct?"
                );
            }

            $response_json = json_decode($response->body);

            $this->access_token = $response_json->access_token;
        }

        return $this->access_token;
    }
}
