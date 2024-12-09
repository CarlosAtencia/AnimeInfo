<main>
    <div class="imgContainer">
        <div class="backgroundImage" style="background-image: url('../view/assets/images/fondo<?= $fondoAleatorio; ?>.jpg');"></div>
    </div>
    <article>
        <section class="containerSection">
            <div class="containerSectionHeader">
                <a>Login</a>
                <a href="../controller/signUpController.php">Sign Up</a>
            </div>
            <form id="formularioIniciarSesion" action="#" method="post">
                <label for="correo">Email</label>
                <input type="email" name="correo" id="correo" value="<?= $_POST["correo"] ?? "" ?>">
                <span class="errorMessage" id="correoError"></span>

                <label for="passwd">Password</label>
                <input type="password" name="passwd" id="passwd">
                <span class="errorMessage" id="passwdError"></span>

                <input type="submit" value="Login" name="login">
                <p class="text"><?= $resultadoFormulario ?></p>
            </form>
        </section>
    </article>
</main>

<script src="../view/assets/scripts/validarInput.js"></script>

<script src="../view/assets/scripts/comprobarLogin.js"></script>