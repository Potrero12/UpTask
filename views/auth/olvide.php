<div class="contenedor olvide">
    <?php 
        include_once __DIR__. '/../templates/nombre-sitio.php';
    ?>

    <div class="contenedor-sm">
        <p class="descripcion-pagina">Recupera Tu Password de UpTask</p>

        <?php 
            include_once __DIR__. '/../templates/alertas.php';
        ?>

        <form class="formulario" method="POST" action="/olvide-password">

            <div class="campo">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="Tu Email" />
            </div>

            <input type="submit" class="boton" value="Recuperar Password" />
        </form>
        <div class="acciones">
            <a href="/">¿Ya Tienes Una Cuenta?, Incia Sesión</a>
            <a href="/crear-cuenta">¿No tienes una cuenta?, Registrate</a>
        </div>
    </div> <!-- fin contenedor-sm -->
</div>