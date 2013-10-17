<?php
namespace Freeagent;

/**
 * Class OAuth
 *
 * @package Freeagent
 */
class OAuth
{
    /** @var \Freeagent\Config */
    protected $config;

    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    /**
     * Given a client id, client secret and a refresh token, request a new access token from the API.
     *
     * @return string
     */
    public function getAccessToken()
    {
        $post_data = array(
            'client_id'     => $this->config->client_id,
            'client_secret' => $this->config->client_secret,
            'refresh_token' => $this->config->refresh_token,
            'grant_type'    => 'refresh_token',
        );

        $response = \Requests::post('https://api.freeagent.com/v2/token_endpoint', array(), $post_data);
        $response_json = json_decode($response->body);

        return $response_json->access_token;
    }
}
