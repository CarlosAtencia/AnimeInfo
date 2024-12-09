<main>
    <div class="imgContainer">
        <div class="backgroundImage" style="background-image: url('../view/assets/images/fondo<?= $fondoAleatorio; ?>.jpg');"></div>
    </div>
    <div class="containerSection">

        <div class="containerSectionHeader">
            <a href="../controller/loginController.php">Login</a>
            <a>Sign Up</a>
        </div>

        <form id="formularioRegistrarse" action="#" method="post">

            <label for="nombreUsuario">Username</label>
            <input type="text" name="nombreUsuario" id="nombreUsuario" value="<?= $_POST["nombreUsuario"] ?? "" ?>">
            <span class="errorMessage" id="nombreUsuarioError"></span>

            <label for="correo">Email</label>
            <input type="email" name="correo" id="correo" value="<?= $_POST["correo"] ?? "" ?>">
            <span class="errorMessage" id="correoError"></span>

            <label for="password">Password</label>
            <input type="password" name="passwd" id="passwd">
            <span class="errorMessage" id="passwdError"></span>

            <label for="passwordConfirmar">Confirm password</label>
            <input type="password" name="passwdConfirmar" id="passwdConfirmar">
            <span class="errorMessage" id="passwdConfirmarError"></span>

            <input type="submit" value="Sign Up" name="signUp">
            <p class="text"><?= $resultadoFormulario ?></p>

        </form>
    </div>
</main>

<script src="../view/assets/scripts/validarInput.js"></script>

<script src="../view/assets/scripts/comprobarSignUp.js"></script>