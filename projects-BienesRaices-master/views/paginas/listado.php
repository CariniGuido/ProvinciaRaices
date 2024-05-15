<div class="contenedor-anuncios">
    <?php foreach ($propiedades as $propiedad) :?>
        <div class="anuncio">
            <picture>
                <source srcset="/public/imagenes/<?php echo $propiedad->imagen?>" type="image/jpeg">
                <img loading="lazy" src="/public/imagenes/<?php echo $propiedad->imagen?>" alt="anuncio">
            </picture>
            <div class="contenido-anuncio">
                <h3><?php echo $propiedad->titulo?></h3>
                <p><?php echo $propiedad->descripcion?></p>
                <p class="precio"><?php echo $propiedad->precio?></p>
                <ul class="iconos-caracteristicas">
                    <li>
                        <img class="icono" loading="lazy" src="/public/build/img/icono_wc.svg" alt="icono wc">
                        <p><?php echo $propiedad->wc?></p>
                    </li>
                    <li>
                        <img class="icono" loading="lazy" src="/public/build/img/icono_estacionamiento.svg" alt="icono estacionamiento">
                        <p><?php echo $propiedad->estacionamiento?></p>
                    </li>
                    <li>
                        <img class="icono" loading="lazy" src="/public/build/img/icono_dormitorio.svg" alt="icono dormitorio">
                        <p><?php echo $propiedad->habitaciones?></p>
                    </li>
                  
                 
               

               
                </ul>
                <img class="icono" loading="lazy" src="public/build/img/19406483-removebg-preview.png" alt="icono ubicación"  height="5%">
                <p class="texto-local" style="display:flex; justify-content: center"  ><?php echo $propiedad->localidad?></p>
                <a href="/propiedad?id=<?php echo $propiedad->id?>" class="boton boton-amarillo-block">Ver Propiedad</a>
            </div><!--.contenido-anuncio-->
 <!-- Formulario para filtrar por localidad -->
<form action="/propiedades/filtrar" method="GET">
    <input type="text" name="localidad" placeholder="Ingrese la localidad">
    <button type="submit">Filtrar</button>
</form>

<!-- Mostrar propiedades -->
<?php foreach ($propiedades as $propiedad) : ?>
    <!-- Código para mostrar cada propiedad -->
<?php endforeach; ?>

        </div><!--.anuncio-->
    <?php endforeach;?>
</div><!--.contenedor-anuncios-->
