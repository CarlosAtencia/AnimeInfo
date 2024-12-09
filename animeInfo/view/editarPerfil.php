<main>
    <div class="containerSection">
        <form action="#" method="post" enctype="multipart/form-data" id="formularioEditarPerfil">

            <div class="profilePhoto">
                <img id="verFotoPerfil" src="data:image/jpeg;base64,<?= $usuario->__get('fotoPerfil'); ?>">

                <input type="file" name="img" id="img" accept="image/*">
                <label class="changePhoto" for="img">Upload photo</label>
                <div id="imagenError" class="error"></div>
            </div>

            <div class="inputs">
                <label for="nombreUsuario">Username</label>
                <input type="text" name="nombreUsuario" id="nombreUsuario" value="<?= $_POST["nombreUsuario"] ?? $usuario->__get('nombreUsuario') ?>">
                <div id="nombreUsuarioError" class="error"></div>

                <label for="correo">Email</label>
                <input type="email" name="correo" id="correo" value="<?= $_POST["correo"] ?? $usuario->__get('correo') ?>">
                <div id="correoError" class="error"></div>

                <label for="passwd">Password</label>
                <input type="password" name="passwd" id="passwd" placeholder="Leave blank to keep the current password">
                <div id="passwdError" class="error"></div>
            </div>

            <div class="inputSubmit">
                <input type="submit" value="Update profile" name="editarPerfil">
            </div>

            <p class="text"><?= $resultadoFormulario ?></p>
        </form>
    </div>
</main>

<script src="../view/assets/scripts/validarInput.js"></script>

<script src="../view/assets/scripts/editarPerfil.js"></script>