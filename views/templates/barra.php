<div class="barra">
    <p>Hola: <?php echo $_SESSION['nombre']??'';?></p>
    <a href="/logout" class="boton">Cerra Sesión</a>
</div>

<?php if(isset($_SESSION['admin'])){ ?>
    <div class="barra-servicios">
        <a class="boton" href="/admin">Ver Citas</a>
        <a class="boton" href="/servicios">Ver Servicios</a>
        <a class="boton" href="/servicios/crear">Nuevo Servicio</a>
    </div>
    
<?php } ?>