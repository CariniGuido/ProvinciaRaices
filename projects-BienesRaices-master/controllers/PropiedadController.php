<?php

namespace Controllers;

use MVC\Router;
use Model\Propiedad;
use Model\Vendedores;
use Intervention\Image\ImageManagerStatic as Image;

class PropiedadController {

    public static function index(Router $router) {
        $localidad = $_GET['localidad'] ?? null;
        echo "Valor de localidad: " . $localidad; // Agregar esta línea para imprimir el valor de la variable
        if ($localidad) {
            $propiedades = Propiedad::buscarPorLocalidad($localidad);
        } else {
            $propiedades = Propiedad::all();
        }
    
        $vendedores = Vendedores::all();
        $mensaje = $_GET['resultado'] ?? null;
    
        $router->render('/propiedades/admin', [
            'propiedades' => $propiedades,
            'vendedores' => $vendedores,
            'mensaje' => $mensaje
        ]);
    }
    

    public static function crear(Router $router) {
        $propiedad = new Propiedad;
        $vendedores = Vendedores::all();
        $errores = Propiedad::getErrores();

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $propiedad = new Propiedad($_POST['propiedad']);
            
            if(!is_dir(CARPETA_IMAGENES)) {
                mkdir(CARPETA_IMAGENES);
            }
            
            $nombreImagen = md5(uniqid(rand(), true)) . ".jpg";
            
            if($_FILES['propiedad']['tmp_name']['imagen']) {
                $image = Image::make($_FILES['propiedad']['tmp_name']['imagen'])->fit(800, 600);
                $propiedad->setImagen($nombreImagen);
            }
            
            $errores = $propiedad->validar();
            
            if(empty($errores)) {
                $resultado = $propiedad->guardar();
                
                if($resultado) {
                    if($_FILES['propiedad']['tmp_name']['imagen']) {
                        $image->save(CARPETA_IMAGENES . $nombreImagen);
                    }
                    header('Location: /admin?resultado=1');
                }
            }
        }

        $router->render('/propiedades/crear', [
            'propiedad' => $propiedad,
            'vendedores' => $vendedores,
            'errores' => $errores
        ]);
    }

    public static function actualizar(Router $router) {
        $id = validarORedireccionar('/admin');
        $propiedad = Propiedad::find($id);
        $vendedores = Vendedores::all();
        $errores = Propiedad::getErrores();

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $args = $_POST['propiedad'];
            $propiedad->sincronizar($args);
            $errores = $propiedad->validar();
            $nombreImagen = md5(uniqid(rand(), true)) . ".jpg";
            $carpetaImagenes = CARPETA_IMAGENES;
            
            if($_FILES['propiedad']['tmp_name']['imagen']) {
                $image = Image::make($_FILES['propiedad']['tmp_name']['imagen'])->fit(800, 600);
                $propiedad->setImagen($nombreImagen);
            }
            
            if(empty($errores)) {
                if($_FILES['propiedad']['tmp_name']['imagen']) {
                    $image->save($carpetaImagenes . $nombreImagen);
                }
                $propiedad->guardar();
                header('Location: /admin?resultado=2');
            }
        }

        $router->render('propiedades/actualizar', [
            'propiedad' => $propiedad,
            'vendedores' => $vendedores,
            'errores' => $errores
        ]);
    }

    public static function eliminar() {
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $id = filter_var($id, FILTER_VALIDATE_INT);
            
            if($id) {
                $tipo = $_POST['tipo'];
                
                if(validarTiposContenido($tipo)) {
                    $propiedad = Propiedad::find($id);
                    $propiedad->eliminar();
                    header('Location: /admin?resultado=3');
                }
            }
        }
    }

    public static function buscarLocalidades() {
        $termino = $_GET['term'] ?? '';
        $localidades = Propiedad::obtenerLocalidades($termino);
        echo json_encode($localidades);
    }

    public static function filtrarPorLocalidad(Router $router) {
        $localidad = $_GET['localidad'] ?? null;
        
        if (!$localidad) {
            header('Location: /propiedades');
            return;
        }
        
        $propiedades = Propiedad::buscarPorLocalidad($localidad);

        $router->render('propiedades/filtradas', [
            'propiedades' => $propiedades,
            'localidad' => $localidad,
        ]);
    }
}

?>
