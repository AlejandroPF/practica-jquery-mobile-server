<?php

include "config.php";

\Slim\Slim::registerAutoloader();
$app = new \Slim\Slim();
$api = new \API\API();
$db = new DbHandler();
$app->get("/authenticate/", function() use ($api, $db) {

    if (isset($_REQUEST['token'])) {
        $token = $_REQUEST['token'];
        $authenticated = $db->authenticateToken($token);
        $response = $authenticated ? new \API\APIResponse("Success", false) : new \API\APIResponse("Bad Login", true);
    } else {
        $api->verifyRequiredParams(array("user", "password"));
        $user = $_GET['user'];
        $password = $_GET['password'];
        $authenticated = $db->authenticate($user, $password);
        $response = $authenticated ? new \API\APIResponse($authenticated, false) : new \API\APIResponse("Bad Login", true);
    }
    $api->echoResponse(200, $response->getResponse());
});
$app->get("/cursos/",function()use ($api, $db){
    $cursos = $db->getCursos();
    $output['cursos'] = $cursos;
    $api->echoResponse(200, $output);   
});
$app->run();
