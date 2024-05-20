<?php

// Importamos la conexión
require 'includes/config/database.php';
$db = conectarDB();

// Escribimos la consulta
$limite = 10; // Ajusta el límite según lo necesites
$query = "SELECT * FROM propiedades LIMIT $limite";

// Ejecutamos la consulta
$resultado = mysqli_query($db, $query);

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Anuncios</title>
    <link rel="stylesheet" href="build/css/app.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="public/js/autocomplete.js"></script>
</head>
<body>
    <header>
        <div class="barra">
            <h1>Bienes Raíces</h1>
        </div>
    </header>

    <main class="contenedor seccion">
        <h1>Casas y Departamentos en Venta</h1>

        <form method="GET" action="/propiedades/filtrar">
            <input type="text" name="localidad" id="buscarLocalidad" placeholder="Buscar por localidad">
            <button type="submit">Buscar</button>
        </form>

        <div class="contenedor-anuncios">
            <?php while ($propiedad = mysqli_fetch_assoc($resultado)) : ?>
                <div class="anuncio">
                    <picture>
                        <source srcset="imagenes/<?php echo $propiedad['imagen']; ?>" type="image/jpeg">
                        <img loading="lazy" src="imagenes/<?php echo $propiedad['imagen']; ?>" alt="anuncio">
                    </picture>

                    <div class="contenido-anuncio">
                        <h3><?php echo $propiedad['titulo']; ?></h3>
                        <p><?php echo $propiedad['descripcion']; ?></p>
                        <p class="precio"><?php echo $propiedad['precio']; ?></p>
                        <ul class="iconos-caracteristicas">
                            <li>
                                <img class="icono" loading="lazy" src="build/img/icono_wc.svg" alt="icono wc">
                                <p><?php echo $propiedad['wc']; ?></p>
                            </li>
                            <li>
                                <img class="icono" loading="lazy" src="build/img/icono_estacionamiento.svg" alt="icono estacionamiento">
                                <p><?php echo $propiedad['estacionamiento']; ?></p>
                            </li>
                            <li>
                                <img class="icono" loading="lazy" src="build/img/icono_dormitorio.svg" alt="icono dormitorio">
                                <p><?php echo $propiedad['habitaciones']; ?></p>
                            </li>
                            <li>
                                <div><p class="texto-local"><?php echo $propiedad['localidad']; ?></p></div>
                                <img class="icono" loading="lazy" src="build/img/19406483-removebg-preview.png" alt="icono ubicación">
                            </li>
                        </ul>
                        <a href="anuncio.php?anuncio=<?php echo $propiedad['id']; ?>" class="boton boton-amarillo-block">Ver Propiedad</a>
                    </div><!--.contenido-anuncio-->
                </div><!--.anuncio-->
            <?php endwhile; ?>
        </div><!--.contenedor-anuncios-->
    </main>
</body>
</html>
