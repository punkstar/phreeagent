<?php
namespace Phreeagent;

/**
 * Class User
 *
 * @package Phreeagent
 */
class User extends Resource
{
    const CREATE_ENDPOINT = '/v2/users';
    const FETCH_ENDPOINT  = '/v2/users/%s';

    public $email;
    public $first_name;
    public $last_name;
    public $role;
    public $password;
    public $password_confirmation;
    public $permission_level;
    public $ni_number;
    public $opening_mileage;

    /**
     * Parse the raw response from the API and populate the resource.
     *
     * @param \stdClass $response_data
     *
     * @return void
     */
    public function loadData(\stdClass $response_data)
    {
        $keys = array(
            'email', 'first_name', 'last_name', 'email', 'role', 'permission_level', 'opening_mileage'
        );

        foreach ($keys as $key) {
            if (isset($response_data->user->$key)) {
                $this->$key = $response_data->user->$key;
            }
        }
    }

    /**
     * @return string
     */
    public function toJson() {
        return json_encode($this->toArray());
    }

    /**
     * @return array
     */
    public function toArray() {
        $user_data = array(
            'email'                 => $this->email,
            'first_name'            => $this->first_name,
            'last_name'             => $this->last_name,
            'role'                  => $this->role,
            'password'              => $this->password,
            'password_confirmation' => $this->password_confirmation,
            'permission_level'      => $this->permission_level,
            'ni_number'             => $this->ni_number,
            'opening_mileage'       => $this->opening_mileage
        );

        $user_data = $this->cleanParameters($user_data);

        return array(
            'user' => $user_data
        );
    }
}
