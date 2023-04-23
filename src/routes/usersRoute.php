<?php

require_once "src/models/userModel.php";
require_once "src/models/propertyModel.php";

$router->get('/api/users/types', function ($req, $res) {
    $result = userModel::getUsers();

    return $res->send($result);
});
