<?php
require_once '../inc/db.php';

$urlBase = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

$slugLoja = isset($_GET['loja']) ? $_GET['loja'] : '';

if ($slugLoja) {
    $stmt = $pdo->prepare("SELECT * FROM lojas WHERE slug = ? LIMIT 1");
    $stmt->execute([$slugLoja]);
    $loja = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$loja) {
        die("Loja não encontrada.");
    }

    $stmtCupons = $pdo->prepare("SELECT * FROM cupons WHERE urlLoja = ?");
    $stmtCupons->execute([$loja['slug']]);
    $cupons = $stmtCupons->fetchAll(PDO::FETCH_ASSOC);

    $stmtOfertas = $pdo->prepare("SELECT * FROM ofertas WHERE urlLoja = ?");
    $stmtOfertas->execute([$loja['slug']]);
    $ofertas = $stmtOfertas->fetchAll(PDO::FETCH_ASSOC);
} else {
    // Buscar 6 lojas aleatórias
    $stmtLojas = $pdo->query("SELECT nome, slug, logoLoja FROM lojas ORDER BY RAND() LIMIT 6");
    $lojas = $stmtLojas->fetchAll(PDO::FETCH_ASSOC);
}
?>

<?php include_once '../inc/head.php'; ?>
<body>
    <header>
        <?php include_once '../inc/navbar.php'; ?>
    </header>

    <?php if ($slugLoja): ?>
    <div class="default">
        <div class="container">
            <div class="title">
                <div>
                    <img src="<?php echo $base_url; ?>/<?php echo !empty($loja['logoLoja']) ? htmlspecialchars($loja['logoLoja']) : 'assets/images/uploads/lojas/default.svg'; ?>" alt="<?php echo htmlspecialchars($loja['nome']); ?>">
                </div>

                <div class="description-container">
                    <h2><?= htmlspecialchars($loja['nome']) ?></h2>
                    <p class="description"><?= htmlspecialchars($loja['descricao']) ?></p>
                    <span class="show-more" onclick="toggleDescription(this)">Mais informações</span>
                </div>

                <div>
                    <span>Filtrar por:</span>
                    <div class="btn-group" role="group">
                        <input type="radio" class="btn-check" name="btnradio" id="btnradio1">
                        <label class="btn btn-outline-primary" for="btnradio1">Cupons (0)</label>

                        <input type="radio" class="btn-check" name="btnradio" id="btnradio2">
                        <label class="btn btn-outline-primary" for="btnradio2">Ofertas (0)</label>
                    </div>
                </div>
            </div>

            <h2>😍 Cupons Disponíveis</h2>
            <div class="cuponsIntern mb-5">
                <?php if (!empty($cupons)): ?>
                    <?php foreach ($cupons as $cupom): ?>
                        <div class="shadowCustom">
                            <span class="badge"><?= rtrim(rtrim(number_format($cupom['porcentagemDesconto'], 2, '.', ''), '0'), '.') ?>% <small>OFF</small></span>
                            <h3><?= htmlspecialchars($cupom['titulo']) ?></h3>
                            <p><?= htmlspecialchars($cupom['descricao']) ?></p>
                            <div class="code">
                                <div class="hiddenCode" id="hiddenCode">VER CUPOM</div>
                                <span><?= htmlspecialchars($cupom['codigoCupom']) ?> 
                                    <a href="<?= htmlspecialchars($cupom['urlCupom']) ?>" target="_blank">
                                        <img src="<?= $base_url; ?>/assets/images/icon/icCopy.svg" alt="Icone">
                                    </a>
                                </span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>Não há cupons disponíveis no momento. Volte em breve!</p>
                <?php endif; ?>
            </div>

            <h2>🔥 Ofertas Disponíveis</h2>
            <div class="ofertasIntern">
                <?php if (!empty($ofertas)): ?>
                    <?php foreach ($ofertas as $oferta): ?>
                        <div class="shadowCustom">
                            <span class="badge"><?= rtrim(rtrim(number_format($oferta['porcentagemDesconto'], 2, '.', ''), '0'), '.') ?>% <small>OFF</small></span>
                            <img src="<?= $base_url; ?>/<?= htmlspecialchars($oferta['fotoOferta']) ?>" width="100">
                            <h3><?= htmlspecialchars($oferta['titulo']) ?></h3>
                            <p><?= htmlspecialchars($oferta['descricao']) ?></p>
                            <a href="<?= htmlspecialchars($oferta['urlOferta']) ?>" target="_blank" class="btn">Ver Oferta</a>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>Não há ofertas disponíveis no momento. Fique de olho nas novidades!</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <?php else: ?>
        <div class="default">
            <div class="container">
                <h1>Encontre cupons de desconto em nossas principais lojas</h1>
                <div class="lojasList">
                    <?php foreach ($lojas as $loja): 
                        $urlLoja = $base_url . '/desconto/'. htmlspecialchars($loja['slug']);
                        $logoLoja = !empty($loja['logoLoja']) ? $loja['logoLoja'] : 'assets/images/uploads/lojas/default.svg';
                    ?>
                        <div class="loja-card">
                            <a href="<?= $urlLoja ?>">
                                <img src="<?php echo $base_url; ?>/<?= htmlspecialchars($logoLoja) ?>" alt="<?= htmlspecialchars($loja['nome']) ?>">
                                <span><?= htmlspecialchars($loja['nome']) ?></span>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <footer>
        <?php include_once '../inc/footer.php'; ?>
    </footer>

    <?php include_once '../inc/bottom.php'; ?>
</body>
</html>
