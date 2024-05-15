<?php
namespace Controllers;

use MVC\Router;
use Model\Propiedad;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;


class PaginasController {


    public static function index(Router $router) {
        $propiedades = Propiedad::get(3);
        $inicio = true;
        $router->render('/paginas/index', [
            'propiedades' => $propiedades,
            'inicio' => $inicio
        ]);
    }
    
    public static function nosotros(Router $router) {
        $router->render('/paginas/nosotros');
    }

    public static function propiedades(Router $router) {
        $propiedades = Propiedad::all();
        $router->render('paginas/propiedades', [
            'propiedades' => $propiedades
        ]);
    }

    public static function propiedad(Router $router) {
        $id = validarORedireccionar('/propiedades');
        $propiedad = Propiedad::find($id);
        $router->render('paginas/propiedad', [
            'propiedad' => $propiedad
       ]);
    }

    public static function blog(Router $router) {
        $router->render('paginas/blog');
    }

    public static function entrada(Router $router) {
        $router->render('paginas/entrada');
    }

    public static function contacto(Router $router) {

        $mensaje = null;
        if($_SERVER['REQUEST_METHOD'] === 'POST') {

            $respuestas = $_POST['contacto'];
        
            //Crear una instancia de PHPMailer
            $mail = new PHPMailer();
            //$mail->SMTPDebug = SMTP::DEBUG_SERVER;


            //Configurar SMTP
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'guidocarini50@gmail.com';
            $mail->Password = 'bvobqguiyzytdyfs';
            
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; 
            $mail->Port = '465';

            //Configurar el contenido del mail
            $mail->setFrom('guidocarini50@gmail.com');
            $mail->addAddress('carinidemarchi@gmail.com', 'Admin BienesRaices');
            $mail->Subject = 'Nuevo mail desde BienesRaices';

            //Habilitar HTML
            $mail->isHTML(true);
            $mail->CharSet = 'UTF-8';

            //Definir el contenido
            $contenido = '<html>';
            $contenido .='<p>Tienes un nuevo mensaje</p>';
            $contenido .='<p>Nombre: ' . $respuestas['nombre'] .'<p>';
            $contenido .='<p>Mensaje: ' . $respuestas['mensaje'] .'<p>';

            //Enviar de forma condicional algunos campos de email o telefono
            $contenido .='<p>Tipo de contacto: ' . $respuestas['contacto'] .'<p>';
            if($respuestas['contacto'] === 'telefono'){
                $contenido .='<p>Telefono: ' . $respuestas['telefono'] .'<p>';
                $contenido .='<p>Fecha: ' . $respuestas['fecha'] .'<p>';
                $contenido .='<p>Hora: ' . $respuestas['hora'] .'<p>';                
            }else {
                $contenido .='<p>Email: ' . $respuestas['email'] .'<p>';  
            }           
            $contenido .='<p>Tipo de transaccion: ' . $respuestas['tipo'] .'<p>';
            $contenido .='<p>Precio o presupuesto: $' . $respuestas['precio'] .'<p>';
            $contenido .='</html>';
            $mail->Body = $contenido;
            $mail->AltBody = 'Esto es texto alternativo sin HTML';

            //Enviar el email
            if($mail->send()) {
                $mensaje = 'Mensaje enviado correctamente';
            } else {
                $mensaje = 'Se ha producido un error mientras se enviaba el mensaje';
            }

        }
        $router->render('paginas/contacto', [
            'mensaje' => $mensaje
        ]);
    }
}