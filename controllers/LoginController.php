<?php

// siempre colocar static las funciones
namespace Controllers;

use Classes\Email;
use Model\Usuario;
use MVC\Router;

class LoginController {

    public static function login(Router $router){

        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $usuario = new Usuario($_POST);

            $alertas = $usuario->validarLogin();

            if(empty($alertas)){
                // encontrar al usuario y que existe
                $usuario = Usuario::where('email', $usuario->email);
                if(!$usuario || !$usuario->confirmado){
                    Usuario::setAlerta('error', 'El usuario no existe o no esta confirmado');
                } else {
                    // el usuario existe
                    if(password_verify($_POST['password'], $usuario->password)){
                        
                        session_start();
                        $_SESSION['id'] = $usuario->id;
                        $_SESSION['nombre'] = $usuario->nombre;
                        $_SESSION['email'] = $usuario->email;
                        $_SESSION['login'] = true;
                        
                        // redireccionar
                        header('location: /dashboard');

                    } else {
                        Usuario::setAlerta('error', 'Datos ingresados son incorrectos');
                    }
                }
            }
        }

        $alertas = Usuario::getAlertas();
        // renderizar vista
        $router->render('auth/login', [
            "titulo" => "Iniciar Sesión",
            "alertas" => $alertas
        ]);

    }

    public static function logout(Router $router){

        session_start();

        $_SESSION = [];

        header('location: /');

    }

    public static function crear(Router $router){
        // instanciamos con el modelo
        $alertas = [];
        $usuario = new Usuario;

        // validamos que venga el post
        if($_SERVER['REQUEST_METHOD'] === 'POST') {

            // sincronizamos para retener la informacion por medio de active Record
            $usuario->sincronizar($_POST);

            // mostramos las alertas
            $alertas = $usuario->validarNuevaCuenta();

            // validar que no hayan errores para crear el usuario
            if(empty($alertas)){

                // verificar si el usuario ya esta registrado
                $existeUsuario = Usuario::where('email', $usuario->email);
                            
                if($existeUsuario){
                    Usuario::setAlerta('error', 'El usuario ya esta registrado');
                    $alertas = Usuario::getAlertas();
                } else {
                    //hashear el password
                    $usuario->hashPassword();
                    
                    // eliminar password2
                    unset($usuario->password2);

                    // generar Token
                    $usuario->crearToken();

                    // guardamos el usuario
                    $resultado = $usuario->guardar();

                    // // enviar email
                    // $email = new Email($usuario->email, $usuario->nombre, $usuario->token);

                    // $email->enviarConfirmacion();

                    if($resultado){
                        header('location: /mensaje');
                    }
                
                }

            }
        }

        $alertas = Usuario::getAlertas();
        // renderizar vista
        $router->render('auth/crear', [
            "titulo" => "Crear Tu Cuenta",
            'usuario' => $usuario,
            'alertas' => $alertas
        ]);

    }

    public static function olvide(Router $router){

        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST'){

            $usuario = new Usuario($_POST);
            $alertas = $usuario->validarEmail();

            if(empty($alertas)){
                $usuario = Usuario::where('email', $usuario->email);
                
                // validamos que el usuario exista y este confirmado
                if($usuario && $usuario->confirmado === "1"){
                    // generar nuevo token
                    $usuario->crearToken();
                    unset($usuario->password2);

                    // actualizar usuario
                    $usuario->guardar();

                    // enviar email
                    $email = new Email($usuario->email, $usuario->nombre, $usuario->token);
                    $email->enviarInstrucciones();

                    // imprimr alerta
                    Usuario::setAlerta('exito', 'Hemos enviado las instrucciones a tu Email');
                } else {
                    Usuario::setAlerta('error', 'El Usuario no existe o no esta confirmado');
                    $alertas = Usuario::getAlertas();
                }

            }

        }

        $alertas = Usuario::getAlertas();
        $router->render('auth/olvide', [
            'titulo' => 'Olvide Password',
            'alertas' => $alertas
        ]);

    }

    public static function reestablecer(Router $router){

        $alertas = [];
        $token = s($_GET['token']);
        $mostrar = true;

        if(!$token){
            header('location: /');
        }

        // identificar el usuario
        $usuario = Usuario::where('token', $token);

        if(empty($usuario)){
            Usuario::setAlerta('error', 'Token no valido');
            $mostrar = false;
        };

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            // añadir el nuevo password
            $usuario->sincronizar($_POST);

            
            // validar password
            $alertas = $usuario->validarPassword();

            if(empty($alertas)){
                // cambiar contraseña
                $usuario->hashPassword();
                $usuario->token = '';
                unset($usuario->password2);
                
                // guardar en la db
                $resultado = $usuario->guardar();

                if($resultado) {
                    header('location: /');
                }
    
                Usuario::setAlerta('exito', 'Contraseña cambiada correctamente');
            }

        }
        $alertas = Usuario::getAlertas();
        $router->render('auth/reestablecer', [
            'titulo' => 'Reestablecer Password',
            'alertas' => $alertas,
            'mostrar' => $mostrar
        ]);

    }

    public static function mensaje(Router $router){

        $router->render('auth/mensaje', [
            'titulo' => 'Cuenta Creada Exitosamente'
        ]);

    }

    public static function confirmar(Router $router){

        $token = s($_GET['token']);

        if(!$token){
            header('location: /');
        }

        // encontrar al usuario
        $usuario = Usuario::where('token', $token);

        if(empty($usuario)){
            Usuario::setAlerta('error', 'Token no valido');
        } else {
            // confirmar la cuenta
            $usuario->confirmado = 1;
            $usuario->token = '';
            unset($usuario->password2);
            
            // guardar en la db
            $usuario->guardar();

            Usuario::setAlerta('exito', 'Cuenta Comprobada correctamente');
        }

        $alertas = Usuario::getAlertas();

        $router->render('auth/confirmar', [
            'titulo' => 'Confirmar Cuenta',
            'alertas' => $alertas
        ]);

    }

}