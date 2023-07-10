<div class="contenedor login">
<?php 
        include_once __DIR__. '/../templates/nombre-sitio.php';
    ?>

    <div class="contenedor-sm">
        <p class="descripcion-pagina">Iniciar Sesión</p>

        <?php
            include_once __DIR__.'/../templates/alertas.php';
        ?>

        <form class="formulario" method="POST" action="/">
            <div class="campo">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="Tu Email" />
            </div>

            <div class="campo">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Tu Password" />
            </div>

            <input type="submit" class="boton" value="Inciar Sesión" />
        </form>
        <div class="acciones">
            <a href="/crear-cuenta">¿No tienes una cuenta?, Registrate</a>
            <a href="/olvide-password">¿Olvidaste tu password?, Recuperala</a>
        </div>
    </div> <!-- fin contenedor-sm -->
</div>