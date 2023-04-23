<?php

include_once "modules/request.php";
include_once "modules/response.php";

class Router
{
    private $request;
    private $response;
    private $supported_http_methods = array('GET', 'POST', 'PUT', 'DELETE');

    function __construct()
    {
        $this->request = new Request;
        $this->response = new Response;
    }

    function __call($name, $args)
    {
        list($route, $method) = $args;

        if (!in_array(strtoupper($name), $this->supported_http_methods)) {
            $this->invalid_method_handler();
        }

        $this->{strtolower($name)}[rtrim($route, '/')] = $method;
    }

    function format_route($request_uri)
    {
        $uri = rtrim($request_uri, '/');
        $routes = array_keys($this->{strtolower($this->request->request_method)});
        $request_url_parts = explode('/', $uri);
        array_shift($request_url_parts);

        foreach ($routes as $route) {
            $route_parts = explode('/', $route);
            array_shift($route_parts);

            for ($i = 0; $i < count($request_url_parts); $i++) {
                $route_part = $route_parts[$i];

                if (preg_match("/^[$]/", $route_part)) {
                    $route_part = ltrim($route_part, '$');
                    $params[$route_part] = $request_url_parts[$i];
                    $$route_part = $request_url_parts[$i];
                } else if (
                    count($route_parts) !== count($request_url_parts) ||
                    $route_parts[$i] != $request_url_parts[$i]
                ) {
                    $params = $route = NULL;
                    break;
                }
            }

            if (!empty($params)) {
                $this->request->set_params($params);
                break;
            }
        }

        return $route ?? $uri;
    }

    private function invalid_method_handler()
    {
        header("{$this->request->server_protocol} 405 Method Not Allowed");
    }

    private function default_request_handler()
    {
        header("{$this->request->server_protocol} 404 Not Found");
    }

    function resolve()
    {
        $method_dictionary = $this->{strtolower($this->request->request_method)};
        $formated_route = $this->format_route($this->request->request_uri);
        $method = $method_dictionary[$formated_route];

        if (is_null($method)) {
            $this->default_request_handler();
            return;
        }

        echo call_user_func_array($method, array($this->request, $this->response));
    }

    function __destruct()
    {
        $this->resolve();
    }
}


