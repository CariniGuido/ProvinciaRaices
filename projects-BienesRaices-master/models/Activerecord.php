<?php

namespace Model;

class ActiveRecord {

    //Base de datos
    protected static $db;
    protected static $columnasDB = [];
    protected static $tabla = "";

    //Errores
    protected static $errores = [];

    //Definir la conexion a la base de datos
    public static function setDB($database) {
        self::$db = $database;
    }

    public function guardar() {
        if($this->id) {
            $this->actualizar();
        } else {
            $this->crear();
        }
    }

    public function crear() {
        $atributos = $this->sanitizarAtributos();
        $query = "INSERT INTO " . static::$tabla . " (" . join(', ', array_keys($atributos)) . ") VALUES ('" . join("', '", array_values($atributos)) . "')";
        $resultado = self::$db->query($query);
        if($resultado) {
            header('Location: /admin?resultado=1');
        }
    }

    public function actualizar() {
        $atributos = $this->sanitizarAtributos();
        $valores = [];
        foreach($atributos as $key => $value) {
            $valores[] = "{$key}='{$value}'";
        }
        $query = "UPDATE " . static::$tabla . " SET " . join(', ', $valores) . " WHERE id = '" . self::$db->escape_string($this->id) . "' LIMIT 1";
        $resultado = self::$db->query($query);
        if($resultado) {
            header('Location: /admin?resultado=2');
        }
    }

    public function eliminar() {
        $query = "DELETE FROM " . static::$tabla . " WHERE id = " .  self::$db->escape_string($this->id) . " LIMIT 1";
        $resultado = self::$db->query($query);
        if($resultado) {
            header('Location: /admin?resultado=3');
        }
    }

    public function atributos() {
        $atributos = [];
        foreach (static::$columnasDB as $columna) {
            if($columna === 'id') continue;
            $atributos[$columna] = $this->$columna;
        }
        return $atributos;
    }

    public function sanitizarAtributos() {
        $atributos = $this->atributos();
        $sanitizado = [];
        foreach($atributos as $key => $value) {
            $sanitizado[$key] = self::$db->escape_string($value);
        }
        return $sanitizado;
    }

    public function setImagen($imagen) {
        if(empty(!$this->id)) { 
            $this->borrarImagen();
        }
        if($imagen){
            $this->imagen = $imagen;
        } 
    }

    public function borrarImagen() {
        $archivoExiste = file_exists(CARPETA_IMAGENES . $this->imagen);
        if($archivoExiste) {
            unlink(CARPETA_IMAGENES  . $this->imagen);
        }
    }

    public static function getErrores() {
        return static::$errores;
    }

    public function validar() {
        static::$errores = [];
        return static::$errores;
    }

    public static function all() {
        $query = "SELECT * FROM " . static::$tabla;
        return self::consultarSQL($query);
    }

    public static function get($cantidad) {
        $query = "SELECT * FROM " . static::$tabla . " LIMIT " . $cantidad;
        return self::consultarSQL($query);
    }

    public static function find($id) {
        $query = "SELECT * FROM " . static::$tabla . " WHERE id = ${id}";
        $resultado = self::consultarSQL($query);
        return array_shift($resultado);
    }

    public static function consultarSQL($query, $parametros = []) {
        // Preparar la consulta
        $stmt = self::$db->prepare($query);

        if ($parametros) {
            $types = str_repeat('s', count($parametros));
            $stmt->bind_param($types, ...$parametros);
        }

        // Ejecutar la consulta
        $stmt->execute();

        // Obtener el resultado
        $resultado = $stmt->get_result();

        if ($resultado === false) {
            echo "Error en la consulta SQL: " . self::$db->error;
            echo "<br>Consulta: " . $query;
            print_r($parametros);
            die();
        }

        // Fetch all results
        $registros = [];
        while ($registro = $resultado->fetch_assoc()) {
            $registros[] = static::crearObjeto($registro);
        }

        $stmt->close();
        return $registros;
    }

    protected static function crearObjeto($registro) {
        $objeto = new static;
        foreach($registro as $key => $value) {
            if(property_exists($objeto, $key)) {
                $objeto->$key = $value;
            }
        }
        return $objeto;
    }

    public function sincronizar( $args = []) {
        foreach($args as $key => $value) {
            if(property_exists($this, $key) && !is_null($value)) {
                $this->$key = $value;
            };
        }
    }
}
?>
