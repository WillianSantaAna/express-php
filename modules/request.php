<?php

class Request
{
    private $params;

    function __construct()
    {
        $this->bootstrap_self();
    }

    private function bootstrap_self()
    {
        foreach ($_SERVER as $key => $value) {
            $this->{strtolower($key)} = $value;
        }
    }

    public function set_params($params) {
        $this->params = $params;
    }

    public function get_params()
    {
        return $this->params;
    }

    public function get_body()
    {
        if ($this->request_method === "GET") {
            return;
        } else {
            return json_decode(file_get_contents('php://input'), TRUE);
        }
    }
}