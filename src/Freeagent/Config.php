<?php
namespace Freeagent;

/**
 * Class Config
 *
 * @package Freeagent
 */
class Config
{
    public $client_id;
    public $client_secret;
    public $refresh_token;

    /**
     * @param $client_id
     * @param $client_secret
     * @param $refresh_token
     */
    public function __construct($client_id, $client_secret, $refresh_token)
    {
        $this->refresh_token = $refresh_token;
        $this->client_id = $client_id;
        $this->client_secret = $client_secret;

        $this->oauth = new OAuth($this);
    }

    /**
     * @return string
     */
    public function getAccessToken()
    {
        return $this->oauth->getAccessToken();
    }
}
