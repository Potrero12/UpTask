<?php

namespace Controllers;

use Model\Proyecto;
use Model\Usuario;
use MVC\Router;

class DashboardController {

    public static function index(Router $router){

        session_start();
        isAuth();

        $id = $_SESSION['id'];
        $proyectos = Proyecto::belongsTo('propietarioId', $id);

        $router->render('dashboard/index', [
            'titulo' => 'Proyectos',
            'proyectos' => $proyectos
        ]);
    }

    public static function crear_proyecto(Router $router){

        session_start();
        isAuth();

        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $proyecto = new Proyecto($_POST);
            
            // validacion
            $alertas = $proyecto->validarProyecto();

            if(empty($alertas)){
                // generar url unica
                $proyecto->url = md5(uniqid());

                // almacenar el id del creador
                $proyecto->propietarioId = $_SESSION['id'];
                
                // guardar el proyecto
                $resultado = $proyecto->guardar();

                if($resultado){
                    // redireccionar
                    header('location: /proyecto?id='.$proyecto->url);
                } else {
                    Usuario::setAlerta('error', 'Error al guardar, ponerse en contacto con el desarrollador');
                }
            }

        }
        $alertas = Usuario::getAlertas();
        $router->render('dashboard/crear-proyecto', [
            'titulo' => 'Crear Proyecto',
            'alertas' => $alertas
        ]);
    }

    public static function proyecto(Router $router){

        session_start();
        isAuth();

        $token = $_GET['id'];
        if(!$token){
            header('location: /dashboard');
        }

        // revisar que la persona que visita el proyecto sea el propietario
        $proyecto = Proyecto::where('url', $token);
        if($proyecto->propietarioId !== $_SESSION['id']){
            header('location: /dashboard');
        }


        $router->render('dashboard/proyecto', [
            'titulo' => $proyecto->proyecto
        ]);
    }

    public static function perfil(Router $router){

        session_start();
        isAuth();

        $id = $_SESSION['id'];
        $alertas = [];

        $usuario = Usuario::find($id);

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $usuario->sincronizar($_POST);

            $alertas = $usuario->validar_perfil();

            if(empty($alertas)){

                $existeUsuario = Usuario::where('email', $usuario->email);

                if($existeUsuario && $existeUsuario->id !== $usuario->id){
                    Usuario::setAlerta('error', 'El usuario ya existe con esa cuenta');
                } else {

                    // guardamos el usuario
                    $usuario->guardar();
                    
                    // creamos la nueva alerta para la modificacion del nombre
                    Usuario::setAlerta('exito', 'Datos Actualizados Correctamente');

                    // asignar el nombre nuevo a la barra
                    $_SESSION['nombre'] = $usuario->nombre;
                }

            }
        }

        $alertas = Usuario::getAlertas();
        $router->render('dashboard/perfil', [
            'titulo' => 'Perfil',
            'alertas' => $alertas,
            'usuario' => $usuario
        ]);
    }

    public static function cambiar_password(Router $router){

        session_start();
        isAuth();

        $alertas = [];
        
        if($_SERVER['REQUEST_METHOD'] === 'POST'){

            $usuario = Usuario::find($_SESSION['id']);
            $usuario->sincronizar($_POST);

            $alertas = $usuario->nuevo_password();
            
            if(empty($alertas)){
                $resultado = $usuario->comprobarPassword();
                
                if($resultado){
                    // asginar el nuevo password
                    $usuario->password = $usuario->password_nueva;
                    
                    unset($usuario->password_actual);
                    unset($usuario->password_nueva);

                    // hashear el nuevo password
                    $usuario->hashPassword();
                    // actualiza la password
                    $resultado = $usuario->guardar();
                    if($resultado){
                        Usuario::setAlerta('exito', 'Password Guardado Correctamente');
                    }

                } else {
                    Usuario::setAlerta('error', 'Password Incorrecto');
                }
            }

        }

        $alertas = Usuario::getAlertas();
        $router->render('dashboard/cambiar-password', [
            'titulo' => 'Cambiar Password',
            'alertas' => $alertas

        ]);
    }

}