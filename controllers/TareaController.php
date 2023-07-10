<?php

namespace Controllers;

use Model\Proyecto;
use Model\Tarea;

class TareaController {

    public static function index() {

        session_start();

        $proyectoId = $_GET['id'];
        // valida que exista el proyecto
        if(!$proyectoId) {
            header('location: /dashboard');
        }
        
        // valida que el proyecto exista y sea el propietario que esta logeado
        $proyecto = Proyecto::where('url', $proyectoId);
        if(!$proyecto || $proyecto->propietarioId !== $_SESSION['id']){
            header('location: /404');
        }

        // trae las tareas que pertenecen a ese proyecto
        $tareas = Tarea::belongsTo('proyectoId', $proyecto->id);
        
        echo json_encode(['tareas' => $tareas]);

    }

    public static function crear() {

        if($_SERVER['REQUEST_METHOD'] === 'POST'){

            session_start();

            $proyectoId = $_POST['proyectoId'];
            
            $proyecto  = Proyecto::where('url', $proyectoId);

            if(!$proyecto || $proyecto->propietarioId !== $_SESSION['id']) {
                $respuesta = [
                    'tipo' => 'error',
                    'mensaje' => 'Hubo un ERROR al agregar la tarea'
                ];
                // siempre enviar la informacion en json_encode
                echo json_encode($respuesta);
                return;
            };

            // crear la tarea
            $tarea = new Tarea($_POST);
            $tarea->proyectoId = $proyecto->id; //se reescribe el post con el dato que necesitamos
            $resultado = $tarea->guardar();
            $respuesta = [
                'tipo' => 'exito',
                'id' => $resultado['id'],
                'mensaje' => 'Tarea Guardada Correctamente',
                'proyectoId' => $proyecto->id
            ];
            echo json_encode($respuesta);

        }

    }

    public static function actualizar() {

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            
            session_start();

            // validar que existe el proyecto
            $proyecto = Proyecto::where('url', $_POST['proyectoId']);
            if(!$proyecto || $proyecto->propietarioId !== $_SESSION['id']) {
                $respuesta = [
                    'tipo' => 'error',
                    'mensaje' => 'Hubo un ERROR al actualizar la tarea'
                ];
                // siempre enviar la informacion en json_encode
                echo json_encode($respuesta);
                return;
            };

            // actualizar la tarea
            $tarea = new Tarea($_POST);
            $tarea->proyectoId = $proyecto->id;
            $tarea->guardar();
            $respuesta = [
                'tipo' => 'exito',
                'id' => $tarea->id,
                'proyectoId' => $proyecto->id,
                'mensaje' => 'Actualizado Correctamente'
            ];
            echo json_encode(['respuesta' => $respuesta]);

        }

    }

    public static function eliminar() {

        session_start();

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $proyecto = Proyecto::where('url', $_POST['proyectoId']);
            if(!$proyecto || $proyecto->propietarioId !== $_SESSION['id']) {
                $respuesta = [
                    'tipo' => 'error',
                    'mensaje' => 'Hubo un ERROR al actualizar la tarea'
                ];
                // siempre enviar la informacion en json_encode
                echo json_encode($respuesta);
                return;
            };

            $tarea = new Tarea($_POST);
            $resultado = $tarea->eliminar();

            $resultado = [
                'resultado' => $resultado,
                'tipo' => 'exito',
                'mensaje' => 'Eliminado Correctamente'
            ];

            echo json_encode($resultado);


        }

    }
}