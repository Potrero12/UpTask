<div class="contenedor reestablecer">
<?php 
        include_once __DIR__. '/../templates/nombre-sitio.php';
    ?>

    <div class="contenedor-sm">
        <p class="descripcion-pagina">Coloca Tu Nuevo Password</p>

        <?php 
            include_once __DIR__. '/../templates/alertas.php';

            if($mostrar) {
        ?>

        <form class="formulario" method="POST">

            <div class="campo">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Tu Password" />
            </div>

            <input type="submit" class="boton" value="Guardar Password" />
        </form>

        <?php }?>
        <div class="acciones">
            <a href="/crear-cuenta">¿No tienes una cuenta?, Registrate</a>
            <a href="/olvide-password">¿Olvidaste tu password?, Recuperala</a>
        </div>
    </div> <!-- fin contenedor-sm -->
</div>