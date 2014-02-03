<?php
namespace Phreeagent;

use Phreeagent\Exception\InvalidResponse;
use Phreeagent\Exception\MalformedResponseException;
use Phreeagent\Exception\UnsuccessfulResponseException;

/**
 * Class Resource
 *
 * @package Phreeagent
 */
abstract class Resource
{
    const ENDPOINT_PRODUCTION = 'https://api.freeagent.com';
    const ENDPOINT_SANDBOX    = 'https://api.sandbox.freeagent.com';

    const CREATE_ENDPOINT = '';
    const FETCH_ENDPOINT  = '';

    /**
     * Parse the raw response from the API and populate the resource.
     *
     * @param \stdClass $response_data
     * @return void
     */
    abstract public function loadData(\stdClass $response_data);

    /**
     * @return string
     */
    abstract public function toJson();

    /**
     * @return array
     */
    abstract public function toArray();

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
     * @param string $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * @return null|string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Retrieve the ID of a resource.
     */
    public function getId()
    {
        $matches = array();
        preg_match('/(\d+)\/?$/', $this->getUrl(), $matches);

        if (count($matches) > 0 && isset($matches[0])) {
            return (int) $matches[0];
        }

        return null;
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
        $response = $this->config->transport->get(
            $this->getFullEndpoint($resource_url),
            $this->getAuthHeaders()
        );

        $response_obj = json_decode($response->body);

        $this->setUrl($resource_url);

        $this->loadData($response_obj);
    }

    /**
     * Create a resource through the API.
     *
     * @throws Exception\UnsuccessfulResponseException
     * @throws Exception\MalformedResponseException
     */
    public function create()
    {
        $response = $this->config->transport->post(
            $this->getCreateEndpoint(),
            $this->getAuthHeaders(),
            $this->toJson()
        );

        if (!$response->success) {
            throw UnsuccessfulResponseException::factory($response, "Resource could not be created");
        }

        if ($location_header = $response->headers['location']) {
            $this->url = $this->getResourcePathFromUrl($location_header);
        } else {
            throw MalformedResponseException::factory($response, "Expected 'Location' header, but did not exist");
        }
    }

    /**
     * Get the URL to hit when creating this resource.
     */
    public function getCreateEndpoint()
    {
        return $this->getFullEndpoint(static::CREATE_ENDPOINT);
    }

    /**
     * @return string
     */
    public function getEndpoint()
    {
        if ($this->config->is_sandbox) {
            return self::ENDPOINT_SANDBOX;
        } else {
            return self::ENDPOINT_PRODUCTION;
        }
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
        return $this->getEndpoint() . $endpoint;
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
        return str_replace($this->getEndpoint(), '', $url);
    }

    /**
     * Remove any null parameters from an array.
     *
     * @param $params
     *
     * @return array
     */
    public function cleanParameters($params)
    {
        return array_filter($params, function ($value) {
            return !($value === null);
        });
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
