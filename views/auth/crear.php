<div class="contenedor crear">
    <?php 
        include_once __DIR__. '/../templates/nombre-sitio.php';
    ?>

    <div class="contenedor-sm">
        <p class="descripcion-pagina">Crea Tu Cuenta</p>

        <?php
            include_once __DIR__.'/../templates/alertas.php';
        ?>

        <form class="formulario" method="POST" action="/crear-cuenta">
            <div class="campo">
                <label for="nombre">Nombre</label>
                <input type="text" id="nombre" name="nombre" value="<?php echo $usuario->nombre ?>" placeholder="Tu Nombre" />
            </div>

            <div class="campo">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="<?php echo $usuario->email ?>" placeholder="Tu Email" />
            </div>

            <div class="campo">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Tu Password" />
            </div>

            <div class="campo">
                <label for="password2">Repetir Password</label>
                <input type="password" id="password2" name="password2" placeholder="Repite Tu Password" />
            </div>

            <input type="submit" class="boton" value="Crear Cuenta" />
        </form>
        <div class="acciones">
            <a href="/">¿Ya Tienes Una Cuenta?, Incia Sesión</a>
            <a href="/olvide-password">¿Olvidaste tu password?, Recuperala</a>
        </div>
    </div> <!-- fin contenedor-sm -->
</div>