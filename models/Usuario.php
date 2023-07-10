<?php

namespace Model;

class Usuario extends ActiveRecord {

    protected static $tabla = 'usuarios';
    protected static $columnasDB = ['id', 'nombre', 'email', 'password', 'token', 'confirmado'];

    public $id;
    public $nombre;
    public $email;
    public $password;
    public $password_actual;
    public $password_nueva;
    public $password2;
    public $token;
    public $confirmado;

    public function __construct($args = []) {
        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
        $this->email = $args['email'] ?? '';
        $this->password = $args['password'] ?? '';
        $this->password2 = $args['password2'] ?? '';
        $this->password_actual = $args['password_actual'] ?? '';
        $this->password_nueva = $args['password_nueva'] ?? '';
        $this->token = $args['token'] ?? '';
        $this->confirmado = $args['confirmado'] ?? 0;
    }
    
    // Validación para cuentas nuevas
    public function validarNuevaCuenta() {
        if(!$this->nombre) {
            self::$alertas['error'][] = 'El Nombre del Usuario es Obligatorio';
        }
        if(!$this->email) {
            self::$alertas['error'][] = 'El Email del Usuario es Obligatorio';
        }
        if(!$this->password) {
            self::$alertas['error'][] = 'El Password no puede ir vacio';
        }
        if(strlen($this->password) < 6) {
            self::$alertas['error'][] = 'El password debe contener al menos 6 caracteres';
        }
        if($this->password !== $this->password2) {
            self::$alertas['error'][] = 'Los password son diferentes';
        }
        return self::$alertas;
    }

    public function validar_perfil(){
        if(!$this->nombre){
            self::$alertas['error'][] = 'El nombre de usuario es obligatorio';
        }

        if(!$this->email){
            self::$alertas['error'][] = 'El email de usuario es obligatorio';
        }

        return self::$alertas;
    }

    public function nuevo_password():array{


        if(!$this->password_actual){
            self::$alertas['error'][] = 'El password de usuario es obligatorio';
        }

        if(strlen($this->password_actual) < 5){
            self::$alertas['error'][] = 'El password debe contener al menos 6 caracteres';
        }

        if(strlen($this->password_nueva) < 5){
            self::$alertas['error'][] = 'El password debe contener al menos 6 caracteres';
        }

        return self::$alertas;
    }

    // valida el password
    public function validarPassword(){
        if(!$this->password){
            self::$alertas['error'][] = 'El password de usuario es obligatorio';
        }

        if(strlen($this->password) < 5){
            self::$alertas['error'][] = 'El password debe contener al menos 6 caracteres';
        }

        return self::$alertas;
    }

    public function validarLogin(){
        if(!$this->email){
            self::$alertas['error'][] = 'El email de usuario es obligatorio';
        }

        if(!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            self::$alertas['error'][] = 'El email no valido';
        }

        if(!$this->password){
            self::$alertas['error'][] = 'El password de usuario es obligatorio';
        }

        if(strlen($this->password) < 5){
            self::$alertas['error'][] = 'El password debe contener al menos 6 caracteres';
        }

        return self::$alertas;
    }

    public function validarEmail(){
        if(!$this->email){
            self::$alertas['error'][] = 'El email es obligatorio';
        }

        if(!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            self::$alertas['error'][] = 'El email no valido';
        }

        return self::$alertas;
    }

    public function comprobarPassword():bool{
        return password_verify($this->password_actual, $this->password);
    }

    // hashear el password
    public function hashPassword():void{
        $this->password = password_hash($this->password, PASSWORD_BCRYPT);
    }

    // generar token
    public function crearToken():void{
        $this->token = md5( uniqid() );
    }

}