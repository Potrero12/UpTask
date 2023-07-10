<?php
    include_once __DIR__ . '/header-dashboard.php';
?>

<div class="contenedor-sm">

    <?php include_once __DIR__ . '/../templates/alertas.php'; ?>

    <a href="/perfil" class="enlace">Volver al Perfil</a>

    <form class="formulario" method="POST" action="/cambiar-password">
        <div class="campo">
            <label for="password_actual">Password Actual</label>
            <input type="password" id="password_actual" name="password_actual"  placeholder="Tu Password Actual"/>
        </div>

        <div class="campo">
            <label for="password_nueva">Password Nueva</label>
            <input type="password" id="password_nueva" name="password_nueva"  placeholder="Tu Nueva Password"/>
        </div>

        <input type="submit" value="Cambiar Password" />

    </form>
</div>

<?php
    include_once __DIR__ . '/footer-dashboard.php';
?>