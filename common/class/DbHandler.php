<?php

/**
 * Controlador de la base de da tos
 *
 * @author Alejandro Peña Florentín (alejandropenaflorentin@gmail.com)
 */
class DbHandler {

    private $connection;

    public function __construct() {
        $this->connection = new mysqli("localhost", "diw_app", "diw_app", "diw_app");
    }

    /**
     * Autentica al usuario y obtiene un token
     * @param type $user
     * @param type $password
     * @return type
     */
    public function authenticate($user, $password) {
        $output = false;
        $sql = $this->connection->query("select password,id,nombre from profesores where nombre=\"" . $user . "\"");
        if ($sql && $sql->num_rows == 1) {
            $arr = $sql->fetch_assoc();
            if ($arr["password"] === $password) {
                $jwt = new JWTManager();
                $jwt->setSub($arr["id"]);
                $jwt->setUname($arr["nombre"]);
                $output = $jwt->getToken();
            }
        }
        return $output;
    }

    public function authenticateToken($token) {
        $output = false;
        $jwt = JWTManager::createFromToken($token);
        $userId = $jwt->getSub();
        $userName = $jwt->getUname();
        $sql = $this->connection->query("select nombre from profesores where id=\"" . $userId . "\"");
        if ($sql && $sql->num_rows == 1) {
            $arr = $sql->fetch_row();
            if ($userName == $arr[0])
                $output = true;
        }
        return $output;
    }
    public function getCursos(){
        $output = [];
        $sql = $this->connection->query("select id,nombre from cursos");
        if($sql && $sql->num_rows > 0){
            for ($index = 0; $index < $sql->num_rows; $index++) {
                $output[$index] =$sql->fetch_assoc();
            }
        }
        return $output;
    }
}
