<?php
namespace Freeagent;

/**
 * Class Contact
 *
 * @package Freeagent
 */
class Contact extends Resource
{
    const CREATE_ENDPOINT = '/v2/contacts';
    const FETCH_ENDPOINT  = '/v2/contacts/%s';

    public $organisation_name;
    public $first_name;
    public $last_name;
    public $email;

    public function loadData(\stdClass $response_data)
    {
        $keys = array('organisation_name', 'first_name', 'last_name', 'email');

        foreach ($keys as $key) {
            $this->$key = $response_data->contact->$key;
        }
    }

    /**
     * @inheritdoc
     */
    public function toArray()
    {
        return array(
            'contact' => array(
                'organisation_name' => $this->organisation_name,
                'first_name'        => $this->first_name,
                'last_name'         => $this->last_name,
                'email'             => $this->email
            )
        );
    }

    /**
     * @inheritdoc
     */
    public function toJson()
    {
        return json_encode($this->toArray());
    }
}
