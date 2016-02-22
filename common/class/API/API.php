<?php

namespace API;

/**
 * Description of API
 *
 * @author usuario
 */
class API {

    /**
     * Verifica que los parátros pasados existen
     * @param array $required_fields Parámetros obligatorios
     */
    function verifyRequiredParams($required_fields) {
        $error = false;
        $error_fields = array();
        $request_params = array();
        $request_params = $_REQUEST;

        // Handling PUT request params
        if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
            $app = \Slim\Slim::getInstance();
            parse_str($app->request()->getBody(), $request_params);
        }
        foreach ($required_fields as $field) {
            if (!isset($request_params[$field]) || strlen(trim($request_params[$field])) <= 0) {
                $error = true;
                $error_fields[] = $field;
            }
        }

        if ($error) {
            // Required field(s) are missing or empty
            // echo error json and stop the app
            $response = array();
            $app = \Slim\Slim::getInstance();
            $response["error"] = true;
            if (count($error_fields) > 1) {
                $response["responseText"] = 'Faltan los campos ' . implode(",", $error_fields) . ' o estan vacíos';
            } else {
                $response["responseText"] = 'Falta el campo ' . implode(",", $error_fields) . ' o esta vacío';
            }
            $this->echoResponse(400, $response);
            $app->stop();
        }
    }

    public function getExtraParameters($method = 'get') {
        $request = $method == 'get' ? $_GET : $_POST;
        $output = array();
        if (is_array($request) && count($request) > 0) {
            foreach ($request as $varName => $varValue) {
                $output[$varName] = $varValue;
            }
        }
        return $output;
    }

    /**
     * Echoing json response to client
     * @param String $status_code Http response code
     * @param Int $response Json response
     */
    function echoResponse($status_code, $response) {
        $app = \Slim\Slim::getInstance();
        // Http response code
        $app->status($status_code);

        // setting response content type to json
        $app->contentType('application/json;charset=utf-8');

        echo json_encode($response);
    }

}

class APIResponse {

    private $error;
    private $responseContent;

    public function __construct($response, $error = false) {
        $this->responseContent = $response;
        $this->error = $error;
    }

    public function getResponse() {
        $output = [
        "error" => $this->error,
        "response" => $this->responseContent
        ];
        return $output;
    }

}
