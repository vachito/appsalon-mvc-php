<h1 class="nombre-pagina">Olvide Password</h1>
<p class="descripcion-pagina">Restablece tu password escribiendo tu email a continuación</p>

<?php
    include_once __DIR__ . '/../templates/alertas.php';
?>

<form action="/olvide" class="formulario" method="post">
    <div class="campo">
        <label for="email">Email:</label>
        <input 
            type="email"
            id="email"
            name="email"
            placeholder="Tu email"
            value=""
        />
    </div>

    <input type="submit" value="Enviar Instrucciones" class="boton">
</form> 

<div class="acciones">
    <a href="/">¿Ya tienes una cuenta? Inicia sesión</a>
    <a href="/crear-cuenta">¿Aun no tienes una cuenta? Crear Una</a>
</div>