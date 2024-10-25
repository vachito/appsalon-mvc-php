<h1 class="nombre-pagina">Recuperar Password</h1>
<p class="descripcion-pagina">Coloca tu nuevo password a continuaación</p>

<?php
    include_once __DIR__ . '/../templates/alertas.php';
?>

<?php if($error) return; ?>
<form class="formulario" method="post">
    <div class="campo">
        <label for="password">Password</label>
        <input 
            type="password"
            id="password"
            name="password"
            placeholder="Tu Password"
        >
    </div>
    <input type="submit" value="Guardar nuevo password" class="boton">
</form>

<div class="acciones">
    <a href="/">¿Ya tienes una cuenta? Inicia sesión</a>
    <a href="/crear-cuenta">¿Aún no tienes una cuenta? Obtener una</a>
</div>