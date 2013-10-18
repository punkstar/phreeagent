<?php
namespace Phreeagent;

/**
 * Class Config
 *
 * @package Phreeagent
 */
class Config
{
    /**
     * @var string
     */
    public $client_id;

    /**
     * @var string
     */
    public $client_secret;

    /**
     * @var string
     */
    public $refresh_token;

    /**
     * @var Transport
     */
    public $transport;

    /**
     * @param $client_id
     * @param $client_secret
     * @param $refresh_token
     * @param Transport $transport
     */
    public function __construct($client_id, $client_secret, $refresh_token, Transport $transport = null)
    {
        $this->refresh_token = $refresh_token;
        $this->client_id = $client_id;
        $this->client_secret = $client_secret;

        $this->transport = ($transport) ? $transport : new Transport();

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
