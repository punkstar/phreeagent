<?php
namespace Phreeagent\Exception;

class ResponseException extends \Exception
{
    public static function factory(\Requests_Response $response, $message)
    {
        $full_exception_message = sprintf(
            "%s\nURL: %s\nResponse Code: %s\nResponse Data: %s",
            $message,
            $response->url,
            $response->status_code,
            $response->body
        );

        return new static($full_exception_message);
    }
}
