<?php
namespace Phreeagent;

/**
 * Class Resource
 *
 * @package Phreeagent
 */
abstract class Resource
{
    const ENDPOINT = 'https://api.freeagent.com';

    const CREATE_ENDPOINT = '';
    const FETCH_ENDPOINT  = '';

    /**
     * Parse the raw response from the API and populate the resource.
     *
     * @param \stdClass $response_data
     * @return void
     */
    abstract function loadData(\stdClass $response_data);

    /**
     * @return string
     */
    abstract function toJson();

    /**
     * @return array
     */
    abstract function toArray();

    /** @var string|null */
    protected $url;

    /** @var \Phreeagent\Config */
    protected $config;

    /**
     * @param Config $config
     */
    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    /**
     * Load a resource from the API.
     *
     * The parameter is the id of the resource.  E.g., /v2/contacts/2093385 would be loaded by passing 2093385
     * to the method.
     *
     * @param int $resource_id
     */
    public function load($resource_id)
    {
        $resource_url = sprintf(static::FETCH_ENDPOINT, $resource_id);
        $response = Transport::get($this->getFullEndpoint($resource_url), $this->getAuthHeaders());

        $response_obj = json_decode($response->body);

        $this->url = $resource_url;

        $this->loadData($response_obj);
    }

    /**
     * Create a resource through the API.
     */
    public function create()
    {
        $response = Transport::post($this->getFullEndpoint(static::CREATE_ENDPOINT), $this->getAuthHeaders(), $this->toJson());
        $this->url = $this->getResourcePathFromUrl($response->headers['location']);
    }

    /**
     * Give a resource identifier, such as /v2/contacts/2093385, return the full URL of the resource, e.g.
     * https://api.freeagent.com/v2/contacts/2093385
     *
     * @param string $endpoint
     * @return string
     */
    public function getFullEndpoint($endpoint)
    {
        return self::ENDPOINT . $endpoint;
    }

    /**
     * Given a fully qualified resource identifier, just return the path, e.g.
     * https://api.freeagent.com/v2/contacts/2093385 will return /v2/contacts/2093385.
     *
     * @param string $url
     * @return string
     */
    public function getResourcePathFromUrl($url)
    {
        return str_replace(self::ENDPOINT, '', $url);
    }

    /**
     * @return array
     */
    public function getAuthHeaders()
    {
        return array(
            "Authorization" => sprintf("Bearer %s", $this->getAccessToken()),
            "Accept"        => "application/json",
            "Content-Type"  => "application/json"
        );
    }

    /**
     * @return string
     */
    public function getAccessToken()
    {
        return $this->config->getAccessToken();
    }
}
