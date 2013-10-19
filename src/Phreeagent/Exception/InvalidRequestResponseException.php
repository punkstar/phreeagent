<?php
namespace Phreeagent\Exception;

class InvalidRequestResponseException extends ResponseException
{
    public static function factory(\Requests_Response $response, $message = '')
    {
        if ($message == '') {
            $message = "Invalid request send to server";
        }

        return parent::factory($response, $message);
    }
}
