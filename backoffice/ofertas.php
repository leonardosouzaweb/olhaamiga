<?php
    // Conexão com o banco de dados
    require_once '../inc/db.php';

    // Buscar todas as lojas cadastradas (usando slug e nome, pois idLoja não existe)
    $stmtLojas = $pdo->query("SELECT nome, slug FROM lojas ORDER BY nome ASC");
    $lojas = $stmtLojas->fetchAll(PDO::FETCH_ASSOC);
    
    // Buscar todas as categorias cadastradas
    $stmtCategorias = $pdo->query("SELECT * FROM categorias ORDER BY nome ASC");
    $categorias = $stmtCategorias->fetchAll(PDO::FETCH_ASSOC);

    // Verifica se o formulário foi enviado
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $urlLoja = $_POST['urlLoja'];
        $titulo = $_POST['titulo'];
        $descricao = $_POST['descricao'];
        $urlOferta = $_POST['urlOferta'];
        $categoria_id = $_POST['categoria_id']; // Associação com categoria
        
        // Diretório de upload
        $uploadDir = __DIR__ . '/../assets/uploads/ofertas/';
        $relativeUploadPath = 'assets/uploads/ofertas/';
        
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        // Upload da imagem da oferta
        $fotoOferta = '';
        if (!empty($_FILES['fotoOferta']['name'])) {
            $fileName = basename($_FILES['fotoOferta']['name']);
            $filePath = $uploadDir . $fileName;
            $relativeFilePath = $relativeUploadPath . $fileName;

            if (move_uploaded_file($_FILES['fotoOferta']['tmp_name'], $filePath)) {
                $fotoOferta = $relativeFilePath;
            }
        }
        
        // Inserir no banco
        $stmt = $pdo->prepare("INSERT INTO ofertas (urlLoja, titulo, descricao, fotoOferta, urlOferta, categoria_id, created) 
                       VALUES (?, ?, ?, ?, ?, ?, NOW())");
        $stmt->execute([$urlLoja, $titulo, $descricao, $fotoOferta, $urlOferta, $categoria_id]);
    }

    // Excluir oferta
    if (isset($_GET['excluir'])) {
        $idOferta = $_GET['excluir'];
        $stmt = $pdo->prepare("DELETE FROM ofertas WHERE id = ?");
        $stmt->execute([$idOferta]);
        header("Location: ofertas.php");
        exit;
    }

    // Buscar todas as ofertas cadastradas e exibir a categoria associada
    $stmtOfertas = $pdo->query("
        SELECT ofertas.id, ofertas.titulo, ofertas.descricao, ofertas.fotoOferta, lojas.nome AS loja, categorias.nome AS categoria 
        FROM ofertas 
        INNER JOIN lojas ON ofertas.urlLoja = lojas.slug 
        INNER JOIN categorias ON ofertas.categoria_id = categorias.id
        ORDER BY lojas.nome ASC
    ");
    $ofertas = $stmtOfertas->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include_once '../inc/head.php'; ?>
<body>
    <?php include_once '../backoffice/navbarDash.php'; ?>

    <div class="dashboard">
        <div class="container">
            <h2>Cadastrar Nova Oferta</h2>
            <form method="post" enctype="multipart/form-data">
                <div class="block mt-3 mb-3">
                    <div>
                        <label>Loja:</label>
                        <select class="form-select" name="urlLoja" required>
                            <?php foreach ($lojas as $loja): ?>
                                <option value="<?= htmlspecialchars($loja['slug'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                                    <?= htmlspecialchars($loja['nome'] ?? '', ENT_QUOTES, 'UTF-8') ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div>
                        <label>Categoria:</label>
                        <select class="form-select" name="categoria_id" required>
                            <?php foreach ($categorias as $categoria): ?>
                                <option value="<?= htmlspecialchars($categoria['id'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                                    <?= htmlspecialchars($categoria['nome'] ?? '', ENT_QUOTES, 'UTF-8') ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="block">
                    <div>
                        <label>Título:</label>
                        <input type="text" class="form-control" name="titulo" required>
                    </div>
                </div>

                <div class="block mt-3 mb-3">
                    <div>
                        <label>Foto da Oferta:</label>
                        <input class="form-control" type="file" name="fotoOferta" required>
                    </div>

                    <div>
                        <label>URL da Oferta:</label>
                        <input class="form-control" type="text" name="urlOferta" required>
                    </div>
                </div>

                <div class="block mt-3 mb-3">
                    <div class="w100">
                        <label>Descrição:</label>
                        <textarea class="form-control" name="descricao"></textarea>
                    </div>
                </div>
                <button class="btn btn-primary" type="submit">Cadastrar</button>
            </form>


            <h2>Ofertas Cadastradas</h2>
            <div class="tableCustom">
                <div class="title">
                    <ul>
                        <li>ID</li>
                        <li>Imagem</li>
                        <li>Título</li>
                        <li>Descrição</li>
                        <li>Loja</li>
                        <li>Categoria</li>
                        <li>Ações</li>
                    </ul>
                </div>

                <div class="contentTable">
                    <?php foreach ($ofertas as $oferta): ?>
                    <ul>
                        <li><?= htmlspecialchars($oferta['id']) ?></li>
                        <li><img src="<?php echo $base_url; ?>/<?= htmlspecialchars($oferta['fotoOferta'] ?? '', ENT_QUOTES, 'UTF-8') ?>" width="50"></li>
                        <li><?= htmlspecialchars($oferta['titulo'] ?? '', ENT_QUOTES, 'UTF-8') ?></li>
                        <li><?= htmlspecialchars($oferta['descricao'] ?? '', ENT_QUOTES, 'UTF-8') ?></li>
                        <li><?= htmlspecialchars($oferta['loja'] ?? '', ENT_QUOTES, 'UTF-8') ?></li>
                        <li><?= htmlspecialchars($oferta['categoria'] ?? '', ENT_QUOTES, 'UTF-8') ?></li>
                        <li>
                            <a href="<?php echo $base_url; ?>/backoffice/ofertas.php?excluir=<?= htmlspecialchars($oferta['id'] ?? '', ENT_QUOTES, 'UTF-8') ?>" 
                               onclick="return confirm('Tem certeza que deseja excluir esta oferta?');">Excluir</a>
                        </li>
                    </ul>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
    <?php include_once '../inc/bottom.php'; ?>
</body>
</html>
