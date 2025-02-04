<?php include_once 'inc/head.php'; ?>
<?php require_once 'inc/db.php'; ?>

<?php
// Buscar todas as lojas cadastradas no banco
$stmtLojas = $pdo->query("SELECT nome, slug, logoLoja FROM lojas ORDER BY nome ASC");
$lojas = $stmtLojas->fetchAll(PDO::FETCH_ASSOC);

// Buscar no banco um limite máximo de 20 lojas para destaque
$stmtLojasDestaque = $pdo->query("SELECT nome, slug FROM lojas ORDER BY RAND() LIMIT 20");
$lojasDestaque = $stmtLojasDestaque->fetchAll(PDO::FETCH_ASSOC);

// Buscar os últimos 20 cupons cadastrados
$stmtCupons = $pdo->query("
    SELECT cupons.id, cupons.titulo, cupons.descricao, cupons.codigoCupom, cupons.urlCupom, cupons.logoLoja, 
           lojas.nome AS loja, lojas.slug AS slugLoja
    FROM cupons
    INNER JOIN lojas ON cupons.urlLoja = lojas.slug
    ORDER BY cupons.created DESC
    LIMIT 20
");
$cupons = $stmtCupons->fetchAll(PDO::FETCH_ASSOC);
?>

<body>
    <header>
        <?php include_once 'inc/navbar.php'; ?>
    </header>

    <section class="c1">
        <div class="container text-center">
            <h1>Cupons de Desconto, Produtos e Cashback</h1>
            <p>Economize na sua compra usando os melhores Cupons de Desconto e Cashback!</p>

            <div class="cards">
                <?php foreach ($lojas as $loja): ?>
                    <a href="<?php echo $base_url; ?>/desconto/<?php echo htmlspecialchars($loja['slug']); ?>" class="loja-card">
                        <img src="<?php echo $base_url; ?>/<?php echo !empty($loja['logoLoja']) ? htmlspecialchars($loja['logoLoja']) : 'assets/images/uploads/lojas/default.svg'; ?>" alt="<?php echo htmlspecialchars($loja['nome']); ?>">
                        <span><?php echo htmlspecialchars($loja['nome']); ?></span>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <section class="c2">
        <div class="container">
            <h2>Os melhores Cupons de Desconto</h2>
            <div class="block">
                <div>
                <?php foreach ($cupons as $cupom): ?>
                    <div class="coupon shadowCustom">
                        <div class="logo">
                            <img src="<?php echo $base_url; ?>/<?php echo !empty($cupom['logoLoja']) ? htmlspecialchars($cupom['logoLoja']) : 'assets/images/uploads/lojas/default.svg'; ?>" alt="<?php echo htmlspecialchars($cupom['loja']); ?>">
                        </div>
                        <div class="description">
                            <h3><?php echo htmlspecialchars($cupom['titulo']); ?></h3>
                            <p><?php echo htmlspecialchars($cupom['descricao']); ?></p>
                            <small>
                                <img src="<?php echo $base_url; ?>/assets/images/icon/icCheck.svg"> Verificado &nbsp;
                                <img src="<?php echo $base_url; ?>/assets/images/icon/icUp.svg">
                                <?php 
                                    $cuponsUsados = rand(5, 50);
                                    echo $cuponsUsados . " cupons usados hoje";
                                ?>
                            </small>
                        </div>
                        <div class="share">
                            <button 
                                data-bs-toggle="modal" 
                                data-bs-target="#modalDiscount"
                                data-titulo="<?php echo htmlspecialchars($cupom['titulo']); ?>"
                                data-descricao="<?php echo htmlspecialchars($cupom['descricao']); ?>"
                                data-codigo="<?php echo htmlspecialchars($cupom['codigoCupom']); ?>"
                                data-url="<?php echo htmlspecialchars($cupom['urlCupom']); ?>"
                                data-logo="<?php echo !empty($cupom['logoLoja']) ? htmlspecialchars($cupom['logoLoja']) : 'assets/images/uploads/lojas/default.svg'; ?>"
                                onclick="abrirModal(this)">
                                Ver desconto
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
                </div>

                <div class="colun2">
                    <span><img src="<?php echo $base_url; ?>/assets/images/icon/icUse.svg"> Como usar os cupons de descontos</span>
                    <span><img src="<?php echo $base_url; ?>/assets/images/icon/icAll.svg"> Veja todas ofertas e cupons de desconto</span>
                    <nav>
                        <h4>Cupons de lojas em destaque</h4>
                        <ul>
                            <?php foreach ($lojasDestaque as $loja): ?>
                                <li>
                                    <a href="<?php echo $base_url; ?>/desconto/<?php echo htmlspecialchars($loja['slug']); ?>">
                                        <?php echo htmlspecialchars($loja['nome']); ?>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </section>

    <footer>
        <?php include_once 'inc/footer.php'; ?>
    </footer>

    <!-- MODAL DISCOUNT -->
    <div class="modal fade" id="modalDiscount" tabindex="-1" aria-labelledby="modalDiscountLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <img src="<?php echo $base_url; ?>/assets/images/icon/icClose.svg">
                    </button>

                    <div class="logo">
                        <img id="modalLogo">
                    </div>
                    <h5 id="modalTitulo"></h5>
                    <p id="modalDescricao"></p>

                    <span>Copie e cole o código no carrinho de compras</span>
                    <div class="code">
                        <span id="modalCodigo"><img src="<?php echo $base_url; ?>/assets/images/icon/icCopy.svg"></span>
                    </div>

                    <div class="share">
                        <div>
                            <small>Aproveite a melhor experiência com nossos cupons, compartilhe com suas amigas!</small>
                        </div>

                        <div>
                            <img src="<?php echo $base_url; ?>/assets/images/icon/icWhatsapp.svg">
                            <img src="<?php echo $base_url; ?>/assets/images/icon/icFacebook.svg">
                            <img src="<?php echo $base_url; ?>/assets/images/icon/icInstagram.svg">
                            <img src="<?php echo $base_url; ?>/assets/images/icon/icTwitter.svg">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include_once 'inc/bottom.php'; ?>
</body>
</html>