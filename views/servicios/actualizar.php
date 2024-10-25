<h1 class="nombre-pagina">Actualizar Servicio</h1>
<p class="descripcion-pagina">Modifica los valores del fomrulario</p>

<?php
    include __DIR__.'/../templates/barra.php';
    include __DIR__.'/../templates/alertas.php';
?>

<form method="post" class="formulario">
    <?php
        include_once __DIR__.'/formulario.php';
    ?>

    <input type="submit" class="boton" value="Actualizar">
</form>