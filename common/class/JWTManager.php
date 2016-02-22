<?php

/**
 * Description of JWTManager
 *
 * @author Alejandro Peña Florentín (alejandropenaflorentin@gmail.com)
 */
class JWTManager {

    /**
     * @var string Identifica al emisor
     */
    private $iss = "http://disenodeinterfacesweb.com";

    /**
     * @var string Identifica el tiempo de emisión
     */
    private $iat;

    /**
     * @var string Identifica el tiempo de expiración
     */
    private $exp;

    /**
     * @var string Identifica al usuario
     */
    private $sub;

    /**
     * @var string Nombre del usuario
     */
    private $uname;

    /**
     * @var string Clave secreta usada para crear el hash
     */
    private static $secret = "2XRyjs0pzbCJOjHYwTH86PASJc9Pk5Lr";

    /**
     * @var int Tiempo válido del JWT
     */
    private $timeToExp = 3600;

    public function __construct() {
        $this->iat = time();
        $this->exp = time() + $this->timeToExp;
    }

    public function getSub() {
        return $this->sub;
    }

    public function setSub($userId) {
        $this->sub = $userId;
    }

    public function getIss() {
        return $this->iss;
    }

    public function getIat() {
        return $this->iat;
    }

    public function getExp() {
        return $this->exp;
    }

    public function setIss($iss) {
        $this->iss = $iss;
    }

    public function setIat($iat) {
        $this->iat = $iat;
    }

    public function setExp($exp) {
        $this->exp = $exp;
    }

    public function getUname() {
        return $this->uname;
    }

    public function setUname($userName) {
        $this->uname = $userName;
    }

    public function getToken() {
        $key = "example_key";
        $token = array(
            "iss" => $this->getIss(),
            "iat" => $this->getIat(),
            "exp" => $this->getExp(),
            "sub" => $this->getSub(),
            "uname" => $this->getUname()
        );
        /**
         * IMPORTANT:
         * You must specify supported algorithms for your application. See
         * https://tools.ietf.org/html/draft-ietf-jose-json-web-algorithms-40
         * for a list of spec-compliant algorithms.
         */
        $jwt = \Firebase\JWT\JWT::encode($token, self::$secret);
        return $jwt;
    }

    /**
     * Obtiene un objeto JWTManager a partir de un token
     * @param string $token Cadena jwt
     */
    public static function createFromToken($token) {
        $data = \Firebase\JWT\JWT::decode($token, self::$secret, array('HS256'));
        $jwt = new JWTManager();
        $jwt->setIss($data->iss);
        $jwt->setIat($data->iat);
        $jwt->setSub($data->sub);
        $jwt->setUname($data->uname);

        return $jwt;
    }

}
