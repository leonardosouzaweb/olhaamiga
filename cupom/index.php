<?php
require_once '../inc/db.php';

// Capturar a categoria da URL (exemplo: /olha/cupom/alimentos)
$slugCategoria = isset($_GET['categoria']) ? $_GET['categoria'] : '';

// Se uma categoria for selecionada, buscar cupons e ofertas
if ($slugCategoria) {
    // Buscar a categoria pelo slug
    $stmtCategoria = $pdo->prepare("SELECT * FROM categorias WHERE slug = ?");
    $stmtCategoria->execute([$slugCategoria]);
    $categoria = $stmtCategoria->fetch(PDO::FETCH_ASSOC);

    if (!$categoria) {
        die("Categoria não encontrada.");
    }

    // Buscar cupons e ofertas associadas à categoria
    $stmtCupons = $pdo->prepare("SELECT * FROM cupons WHERE categoria_id = ?");
    $stmtCupons->execute([$categoria['id']]);
    $cupons = $stmtCupons->fetchAll(PDO::FETCH_ASSOC);

    $stmtOfertas = $pdo->prepare("SELECT * FROM ofertas WHERE categoria_id = ?");
    $stmtOfertas->execute([$categoria['id']]);
    $ofertas = $stmtOfertas->fetchAll(PDO::FETCH_ASSOC);
}

// Se nenhuma categoria for selecionada, buscar todas as categorias ordenadas
if (!$slugCategoria) {
    $stmtCategorias = $pdo->query("SELECT * FROM categorias ORDER BY nome ASC");
    $categorias = $stmtCategorias->fetchAll(PDO::FETCH_ASSOC);

    // Agrupar categorias por letra inicial
    $categoriasAgrupadas = [];
    foreach ($categorias as $categoria) {
        $letra = strtoupper(mb_substr($categoria['nome'], 0, 1));
        if (!isset($categoriasAgrupadas[$letra])) {
            $categoriasAgrupadas[$letra] = [];
        }
        $categoriasAgrupadas[$letra][] = $categoria;
    }

    // Criar um índice de letras baseado nas categorias existentes
    $letrasDisponiveis = array_keys($categoriasAgrupadas);
    sort($letrasDisponiveis);
}
?>

<?php include_once '../inc/head.php'; ?>
<body>
    <header>
        <?php include_once '../inc/navbar.php'; ?>
    </header>

    <div class="default">
        <div class="container">
            <?php if (!$slugCategoria): ?>
                <h1>Cupons de desconto por categorias</h1>

                <!-- Barra de navegação alfabética -->
                <div class="categoria-nav">
                    <?php foreach ($letrasDisponiveis as $letra): ?>
                        <a href="#<?= htmlspecialchars($letra) ?>"><?= htmlspecialchars($letra) ?></a>
                    <?php endforeach; ?>
                </div>

                <!-- Listagem de categorias agrupadas -->
                <div class="categorias-lista">
                    <?php foreach ($categoriasAgrupadas as $letra => $listaCategorias): ?>
                        <div class="block">
                            <div>
                                <h2 id="<?= htmlspecialchars($letra) ?>"><?= htmlspecialchars($letra) ?></h2>
                            </div>
                            <div>
                                <?php foreach ($listaCategorias as $categoria): 
                                    $urlCategoria = "/olha/cupom/" . htmlspecialchars($categoria['slug']);
                                ?>
                                <a href="<?= $urlCategoria ?>">
                                    <?= htmlspecialchars($categoria['nome']) ?>
                                </a>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="title">
                    <div>
                        <?php if (!empty($categoria['imagem'])): ?>
                            <div class="categoria-imagem">
                                <img src="/olha/<?= htmlspecialchars($categoria['imagem']) ?>" alt="<?= htmlspecialchars($categoria['nome']) ?>">
                            </div>
                        <?php endif; ?>
                    </div>

                    <div>
                        <h2>Cupons e Ofertas para <?= htmlspecialchars($categoria['nome']) ?></h2>
                        <?php if (!empty($categoria['descricao'])): ?>
                            <p class="categoria-descricao"><?= nl2br(htmlspecialchars($categoria['descricao'])) ?></p>
                        <?php endif; ?>
                    </div>
                </div>
            
                <h2>Cupons Disponíveis</h2>
                <div class="cuponsIntern mb-5">
                    <?php if (!empty($cupons)): ?>
                        <?php foreach ($cupons as $cupom): ?>
                            <div class="shadowCustom">
                                <h3><?= htmlspecialchars($cupom['titulo']) ?></h3>
                                <p><?= htmlspecialchars($cupom['descricao']) ?></p>
                                <div class="code">
                                    <div class="hiddenCode">Ver código</div>
                                    <span><?= htmlspecialchars($cupom['codigoCupom']) ?> 
                                        <a href="<?= htmlspecialchars($cupom['urlCupom']) ?>" target="_blank">
                                            <img src="<?= $base_url; ?>/assets/images/icon/icCopy.svg">
                                        </a>
                                    </span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>Não há cupons disponíveis no momento. Volte em breve!</p>
                    <?php endif; ?>
                </div>

                <h2>Ofertas Disponíveis</h2>
                <div class="ofertasIntern">
                    <?php if (!empty($ofertas)): ?>
                        <?php foreach ($ofertas as $oferta): ?>
                            <div class="shadowCustom">
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

                <a href="/olha/cupom/">Voltar para as categorias</a>
            <?php endif; ?>
        </div>
    </div>

    <footer>
        <?php include_once '../inc/footer.php'; ?>
    </footer>

    <?php include_once '../inc/bottom.php'; ?>
</body>
</html>
