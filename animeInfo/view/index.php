<main>
    <article>
        <section class="container py-4">
            <div class="col-12 col-md position-relative">

                <form id="buscarForm" onsubmit="return false;">
                    <input type="text" class="form-control search-input" id="buscar" name="buscar" placeholder="Search anime" value="<?= isset($_GET['buscar']) ? $_GET['buscar'] : '' ?>">
                    <i class="bi bi-search position-absolute search-icon"></i>
                </form>

                <div class="mt-3">
                    <select id="animesRecientesVistos" class="form-control select">
                        <option class="option" value="">Select last seen animes</option>
                    </select>
                </div>

            </div>

            <div class="row justify-content-between my-4">

                <div class="col-4 col-md-3">
                    <select class="form-control genero" name="genero" id="genero">
                        <option value="" <?= empty($_GET['genero']) ? 'selected' : '' ?>>Genre</option>
                        <?php foreach ($generos as $genero): ?>
                            <option value="<?= $genero['genero'] ?>" <?= isset($_GET['genero']) && $_GET['genero'] == $genero['genero'] ? 'selected' : '' ?>><?= $genero['genero'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-4 col-md-3">
                    <select class="form-control tipo" name="tipo" id="tipo">
                        <option value="" <?= empty($_GET['tipo']) ? 'selected' : '' ?>>Format</option>
                        <option value="TV" <?= isset($_GET['tipo']) && $_GET['tipo'] == 'TV' ? 'selected' : '' ?>>TV</option>
                        <option value="TV_SHORT" <?= isset($_GET['tipo']) && $_GET['tipo'] == 'TV Short' ? 'selected' : '' ?>>TV SHORT</option>
                        <option value="OVA" <?= isset($_GET['tipo']) && $_GET['tipo'] == 'OVA' ? 'selected' : '' ?>>OVA</option>
                        <option value="ONA" <?= isset($_GET['tipo']) && $_GET['tipo'] == 'ONA' ? 'selected' : '' ?>>ONA</option>
                        <option value="MOVIE" <?= isset($_GET['tipo']) && $_GET['tipo'] == 'MOVIE' ? 'selected' : '' ?>>MOVIE</option>
                        <option value="Special" <?= isset($_GET['tipo']) && $_GET['tipo'] == 'Special' ? 'selected' : '' ?>>SPECIAL</option>
                        <option value="Music" <?= isset($_GET['tipo']) && $_GET['tipo'] == 'Music' ? 'selected' : '' ?>>MUSIC</option>
                    </select>
                </div>

                <div class="col-4 col-md-3">
                    <select class="form-control ordenar" name="ordenar" id="ordenar">
                        <option value="ID" <?= isset($_GET['ordenar']) && $_GET['ordenar'] == 'ID' ? 'selected' : '' ?>>ID Ascendent</option>
                        <option value="ID_DESC" <?= isset($_GET['ordenar']) && $_GET['ordenar'] == 'ID_DESC' ? 'selected' : '' ?>>ID Descendent</option>
                        <option value="TRENDING" <?= isset($_GET['ordenar']) && $_GET['ordenar'] == 'TRENDING' ? 'selected' : '' ?>>Trending Ascendent</option>
                        <option value="TRENDING_DESC" <?= isset($_GET['ordenar']) && $_GET['ordenar'] == 'TRENDING_DESC' ? 'selected' : '' ?>>Trending Descendent</option>
                        <option value="POPULARITY" <?= isset($_GET['ordenar']) && $_GET['ordenar'] == 'POPULARITY' ? 'selected' : '' ?>>Popularity Ascendent</option>
                        <option value="POPULARITY_DESC" <?= isset($_GET['ordenar']) && $_GET['ordenar'] == 'POPULARITY_DESC' ? 'selected' : '' ?>>Popularity Descendent</option>
                    </select>
                </div>

            </div>
        </section>

        <section class="container-fluid containerAnime pb-4" id="resultadosAnime">
            <!-- Aquí obtendremos los animes desde AJAX -->
        </section>

        <section class="text-center m-4">
            <button class="verMas" id="verMas">
                <span>See more</span>
            </button>
        </section>
    </article>
</main>

<script src="../view/assets/scripts/accederAnime.js"></script>

<script src="../view/assets/scripts/ajaxVerMas.js"></script>

<script src="../view/assets/scripts/ajaxBuscador.js"></script>
