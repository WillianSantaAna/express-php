<?php

class Response
{
    function __construct()
    {
    }

    public function send($result)
    {
        list('status' => $status, 'result' => $result) = $result;

        header('Content-Type: application/json');
        http_response_code($status);

        return json_encode($result);
    }
}
