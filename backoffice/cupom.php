<?php
// Conexão com o banco de dados
require_once '../inc/db.php';

// Buscar todas as lojas cadastradas
$stmtLojas = $pdo->query("SELECT nome, slug FROM lojas ORDER BY nome ASC");
$lojas = $stmtLojas->fetchAll(PDO::FETCH_ASSOC);

// Buscar todas as categorias cadastradas
$stmtCategorias = $pdo->query("SELECT id, nome FROM categorias ORDER BY nome ASC");
$categorias = $stmtCategorias->fetchAll(PDO::FETCH_ASSOC);

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $urlLoja = $_POST['urlLoja']; // Agora usamos a URL da loja
    $titulo = $_POST['titulo'];
    $descricao = $_POST['descricao'];
    $codigoCupom = $_POST['codigoCupom'];
    $urlCupom = $_POST['urlCupom'];
    $categoria_id = $_POST['categoria_id'];

    // Diretório de upload
    $uploadDir = __DIR__ . '/../assets/uploads/cupom/';
    $relativeUploadPath = 'assets/uploads/cupom/';

    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    // Upload da imagem do cupom
    $logoLoja = '';
    if (!empty($_FILES['logoLoja']['name'])) {
        $fileName = basename($_FILES['logoLoja']['name']);
        $filePath = $uploadDir . $fileName;
        $relativeFilePath = $relativeUploadPath . $fileName;

        if (move_uploaded_file($_FILES['logoLoja']['tmp_name'], $filePath)) {
            $logoLoja = $relativeFilePath;
        }
    }

    // Inserir no banco
    $stmt = $pdo->prepare("INSERT INTO cupons (urlLoja, logoLoja, titulo, descricao, codigoCupom, urlCupom, categoria_id, created) 
                           VALUES (?, ?, ?, ?, ?, ?, ?, NOW())");
    $stmt->execute([$urlLoja, $logoLoja, $titulo, $descricao, $codigoCupom, $urlCupom, $categoria_id]);
}

// Excluir cupom
if (isset($_GET['excluir'])) {
    $idCupom = $_GET['excluir'];
    $stmt = $pdo->prepare("DELETE FROM cupons WHERE id = ?");
    $stmt->execute([$idCupom]);
    header("Location: cupom.php");
    exit;
}

// Buscar todos os cupons cadastrados e exibir a categoria associada
$stmtCupons = $pdo->query("
    SELECT cupons.id, cupons.titulo, cupons.codigoCupom, cupons.logoLoja, lojas.nome AS loja, categorias.nome AS categoria 
    FROM cupons 
    INNER JOIN lojas ON cupons.urlLoja = lojas.slug 
    INNER JOIN categorias ON cupons.categoria_id = categorias.id
    ORDER BY lojas.nome ASC
");
$cupons = $stmtCupons->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include_once '../inc/head.php'; ?>
<body>
    <?php include_once '../backoffice/navbarDash.php'; ?>
    <div class="dashboard">
        <div class="container">
            <h2>Cadastrar Novo Cupom</h2>
            <form method="post" enctype="multipart/form-data">
                <div class="block mt-3 mb-3">
                    <div>
                        <label>Loja:</label>
                        <select class="form-select" name="urlLoja" id="urlLojaSelect" required>
                            <option value="">Selecione uma loja</option>
                            <?php foreach ($lojas as $loja): ?>
                                <option value="<?= htmlspecialchars($loja['slug'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                                    <?= htmlspecialchars($loja['nome']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div>
                        <label>Categoria:</label>
                        <select class="form-select" name="categoria_id" required>
                            <?php foreach ($categorias as $categoria): ?>
                                <option value="<?= htmlspecialchars($categoria['id'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                                    <?= htmlspecialchars($categoria['nome']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="block">
                    <div>
                        <label>Logo:</label>
                        <input type="file" class="form-control" name="logoLoja">
                    </div>
                </div>

                <div class="block mt-3 mb-3">
                    <div>
                        <label>Título:</label>
                        <input type="text" class="form-control" name="titulo" required>
                    </div>

                    <div>
                        <label>Código do Cupom:</label>
                        <input type="text" class="form-control" name="codigoCupom" required>
                    </div>

                    <div>
                        <label>URL do Cupom:</label>
                        <input type="text" class="form-control" name="urlCupom" required>
                    </div>
                </div>

                <div class="block">
                    <div class="w100">
                        <label>Descrição:</label>
                        <textarea class="form-control" name="descricao"></textarea>
                    </div>
                </div>
    
                <button type="submit" class="btn btn-primary">Cadastrar</button>
            </form>

            <h2>Cupons Cadastrados</h2>
            <div class="tableCustom">
                <div class="title">
                    <ul>
                        <li>ID</li>
                        <li>Imagem</li>
                        <li>Título</li>
                        <li>Código</li>
                        <li>Loja</li>
                        <li>Categoria</li>
                        <li>Ações</li>
                    </ul>
                </div>

                <div class="contentTable">
                    <?php foreach ($cupons as $cupom): ?>
                        <ul>
                            <li><?= htmlspecialchars($cupom['id']) ?></li>
                            <li><img src="<?php echo $base_url; ?>/<?= htmlspecialchars($cupom['logoLoja'] ?? '', ENT_QUOTES, 'UTF-8') ?>" width="50"></li>
                            <li><?= htmlspecialchars($cupom['titulo'] ?? '', ENT_QUOTES, 'UTF-8') ?></li>
                            <li><?= htmlspecialchars($cupom['codigoCupom'] ?? '', ENT_QUOTES, 'UTF-8') ?></li>
                            <li><?= htmlspecialchars($cupom['loja'] ?? '', ENT_QUOTES, 'UTF-8') ?></li>
                            <li><?= htmlspecialchars($cupom['categoria'] ?? '', ENT_QUOTES, 'UTF-8') ?></li>
                            <li>
                                <a href="<?php echo $base_url; ?>/backoffice/cupom.php?excluir=<?= htmlspecialchars($cupom['id'] ?? '', ENT_QUOTES, 'UTF-8') ?>" onclick="return confirm('Tem certeza que deseja excluir este cupom?');">Excluir</a>
                            </li>
                        </ul>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
