<?php

include_once "modules/router.php";

$router = new Router();
$request = ltrim(strtok($_SERVER["REQUEST_URI"], '?'), '/');
$request_uri_parts = explode('/', $request);

// allowed API routes
$routes[] = 'api/users';

chdir(__DIR__);
$query_string = $_SERVER['QUERY_STRING'];
$file_path = realpath(ltrim(($query_string ? $_SERVER["SCRIPT_NAME"] : $_SERVER["REQUEST_URI"]), '/'));

if ($file_path && is_file($file_path)) {
    if (
        strpos($file_path, __DIR__ . DIRECTORY_SEPARATOR) === 0
        && $file_path != __DIR__ . DIRECTORY_SEPARATOR . 'app.php'
        && substr(basename($file_path), 0, 1) != '.'
    ) {
        if (strtolower(substr($file_path, -4)) == '.php') {
            chdir(dirname($file_path));

            include $file_path;
        } else {
            // asset file; serve from filesystem
            return false;
        }
    } else {
        header("HTTP/1.1 404 Not Found");
        echo "404 Not Found";
    }
} else {
    // rewrite to our router file
    if (in_array(implode('/', array_slice($request_uri_parts, 0, 2)), $routes)) {
        include_once __DIR__ . DIRECTORY_SEPARATOR . "src/routes/$request_uri_parts[1]Route.php";
    } else {
        include __DIR__ . DIRECTORY_SEPARATOR . "src/routes/index.php";
    }
}
