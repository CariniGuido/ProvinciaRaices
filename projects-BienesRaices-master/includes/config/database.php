<?php 

function conectarDB() : mysqli {
    $db = mysqli_connect('localhost:3308', 'bienes', 'bienes', 'bienes2');
    if(!$db) {
        echo "Error no se pudo conectar";
        exit;
    }
    return $db;
}