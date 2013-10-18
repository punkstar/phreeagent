<?php
namespace Phreeagent\Exception;

class UnsuccessfulResponseException extends ResponseException
{
    public static function factory(\Requests_Response $response, $message = '')
    {
        if ($message == '') {
            $message = "Unsuccessful response from server";
        }

        return parent::factory($response, $message);
    }
}
