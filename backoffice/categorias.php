<?php
    // Conexão com o banco de dados
    require_once '../inc/db.php';

    // Diretório de upload
    $uploadDir = __DIR__ . '/../assets/uploads/categorias/'; // Caminho absoluto
    $relativeUploadPath = 'assets/uploads/categorias/'; // Caminho relativo para salvar no banco

    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    // Função para gerar um slug a partir do nome da categoria
    function gerarSlug($string) {
        $slug = strtolower(trim($string));
        $slug = preg_replace('/[áàâãä]/u', 'a', $slug);
        $slug = preg_replace('/[éèêë]/u', 'e', $slug);
        $slug = preg_replace('/[íìîï]/u', 'i', $slug);
        $slug = preg_replace('/[óòôõö]/u', 'o', $slug);
        $slug = preg_replace('/[úùûü]/u', 'u', $slug);
        $slug = preg_replace('/[ç]/u', 'c', $slug);
        $slug = preg_replace('/[^a-z0-9]/u', '-', $slug);
        $slug = preg_replace('/-+/', '-', $slug);
        return $slug;
    }

    // Verifica se o formulário foi enviado
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $nome = $_POST['nome'];
        $descricao = $_POST['descricao'] ?? ''; // Campo de descrição
        $slug = gerarSlug($nome); // Gerar slug automaticamente

        // Upload da imagem da categoria
        $imagem = '';
        if (!empty($_FILES['imagem']['name'])) {
            $fileName = basename($_FILES['imagem']['name']);
            $filePath = $uploadDir . $fileName;
            $relativeFilePath = $relativeUploadPath . $fileName;

            if (move_uploaded_file($_FILES['imagem']['tmp_name'], $filePath)) {
                $imagem = $relativeFilePath; // Salva o caminho relativo no banco
            }
        }

        // Inserir no banco de dados
        $stmt = $pdo->prepare("INSERT INTO categorias (nome, slug, descricao, imagem, created) VALUES (?, ?, ?, ?, NOW())");
        $stmt->execute([$nome, $slug, $descricao, $imagem]);
    }

    if (isset($_GET['excluir'])) {
        $idCategoria = $_GET['excluir'];

        // Deletar imagem associada
        $stmt = $pdo->prepare("SELECT imagem FROM categorias WHERE id = ?");
        $stmt->execute([$idCategoria]);
        $categoria = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($categoria && !empty($categoria['imagem']) && file_exists(__DIR__ . '/../' . $categoria['imagem'])) {
            unlink(__DIR__ . '/../' . $categoria['imagem']); // Remove a imagem do servidor
        }

        // Excluir categoria
        $stmt = $pdo->prepare("DELETE FROM categorias WHERE id = ?");
        $stmt->execute([$idCategoria]);

        header("Location: categorias.php");
        exit;
    }

    $stmtCategorias = $pdo->query("SELECT * FROM categorias ORDER BY nome ASC");
    $categorias = $stmtCategorias->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include_once '../inc/head.php'; ?>
<body>
    <?php include_once '../backoffice/navbarDash.php'; ?>

    <div class="dashboard">
        <div class="container">
            <h2>Cadastrar Nova Categoria</h2>
            <form method="post" enctype="multipart/form-data">
                <div class="block">
                    <div>
                        <label>Nome da Categoria:</label>
                        <input type="text" class="form-control" name="nome" required>
                    </div>

                    <div>
                        <label>Imagem da Categoria:</label>
                        <input type="file" class="form-control" name="imagem">
                    </div>
                </div>

                <div class="block mt-3 mb-3">
                    <div class="w100">
                        <label>Descrição:</label>
                        <textarea class="form-control" name="descricao" rows="3"></textarea>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">Cadastrar</button>
            </form>

            <h2>Categorias Cadastradas</h2>
            <div class="tableCustom">
                <div class="title">
                    <ul>
                        <li>ID</li>
                        <li>Categoria</li>
                        <li>Slug</li>
                        <li>Descrição</li>
                        <li>Imagem</li>
                        <li>Ações</li>
                    </ul>
                </div>

                <div class="contentTable">
                    <?php foreach ($categorias as $categoria): ?>
                        <ul>
                            <li><?= htmlspecialchars($categoria['id'] ?? '', ENT_QUOTES, 'UTF-8') ?></li>
                            <li><?= htmlspecialchars($categoria['nome'] ?? '', ENT_QUOTES, 'UTF-8') ?></li>
                            <li><?= htmlspecialchars($categoria['slug'] ?? '', ENT_QUOTES, 'UTF-8') ?></li>
                            <li><?= htmlspecialchars($categoria['descricao'] ?? '', ENT_QUOTES, 'UTF-8') ?></li>
                            <li>
                                <?php if (!empty($categoria['imagem'])): ?>
                                    <img src="<?php echo $base_url; ?>/<?= htmlspecialchars($categoria['imagem'] ?? '', ENT_QUOTES, 'UTF-8') ?>" width="50">
                                <?php else: ?>
                                    Sem imagem
                                <?php endif; ?>
                            </li>
                            <li>
                                <a href="<?php echo $base_url; ?>/backoffice/categorias.php?excluir=<?= htmlspecialchars($categoria['id'] ?? '', ENT_QUOTES, 'UTF-8') ?>" 
                                   onclick="return confirm('Tem certeza que deseja excluir esta categoria?');">Excluir</a>
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
