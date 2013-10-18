<?php
namespace Phreeagent\Exception;

class MalformedResponseException extends ResponseException
{
    public static function factory(\Requests_Response $response, $message = '')
    {
        if ($message == '') {
            $message = "Malformed response from server";
        }

        return parent::factory($response, $message);
    }
}
