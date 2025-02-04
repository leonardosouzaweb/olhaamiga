<?php
    // Conexão com o banco de dados
    require_once '../inc/db.php';

    // Buscar todas as categorias cadastradas
    $stmtCategorias = $pdo->query("SELECT * FROM categorias ORDER BY nome ASC");
    $categorias = $stmtCategorias->fetchAll(PDO::FETCH_ASSOC);

    // Função para gerar o slug (exemplo simples)
    function gerarSlug($string) {
        return strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $string)));
    }

    // Verifica se o formulário foi enviado
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $categoria = $_POST['categoria'];
        $nome = $_POST['nome'];
        $slug = gerarSlug($nome); // Gerar o slug
        $descricao = $_POST['descricao'];
        $avaliacao = $_POST['avaliacao'];
        $comoUsar = $_POST['comoUsar'];
        $confiavel = isset($_POST['confiavel']) ? 1 : 0; // Verifica se confiável foi marcado
        $urlInstagram = $_POST['urlInstagram'];
        $urlTwitter = $_POST['urlTwitter'];
        $urlWhatsapp = $_POST['urlWhatsapp'];
        $urlFacebook = $_POST['urlFacebook'];
        $endereco = $_POST['endereco'];
        $og_type = $_POST['og_type'];
        $og_url = $_POST['og_url'];
        $og_description = $_POST['og_description'];
        $twitter_card = $_POST['twitter_card'];
        $twitter_url = $_POST['twitter_url'];
        $twitter_title = $_POST['twitter_title'];
        $twitter_description = $_POST['twitter_description'];
        $descriptionSeo = $_POST['descriptionSeo'];
        $descriptionKeywords = $_POST['descriptionKeywords'];
        $titlePage = $_POST['titlePage'];
        
        // Diretório de upload
        $uploadDir = __DIR__ . '/../assets/uploads/lojas/'; // Caminho absoluto
        $relativeUploadPath = 'assets/uploads/lojas/'; // Caminho relativo para salvar no banco
        
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        // Upload da logo
        $logoLoja = '';
        if (!empty($_FILES['logoLoja']['name'])) {
            $fileName = basename($_FILES['logoLoja']['name']);
            $filePath = $uploadDir . $fileName;
            $relativeFilePath = $relativeUploadPath . $fileName;

            if (move_uploaded_file($_FILES['logoLoja']['tmp_name'], $filePath)) {
                $logoLoja = $relativeFilePath; // Salva o caminho relativo no banco
            }
        }
        
        // Insere no banco
        $stmt = $pdo->prepare("INSERT INTO lojas (slug, logoLoja, categoria, nome, descricao, avaliacao, comoUsar, confiavel, urlInstagram, urlTwitter, urlWhatsapp, urlFacebook, endereco, og_type, og_url, og_description, twitter_card, twitter_url, twitter_title, twitter_description, descriptionSeo, descriptionKeywords, titlePage, created) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");

        // Execute a query com os valores
        $stmt->execute([
            $slug, $logoLoja, $categoria, $nome, $descricao, $avaliacao, $comoUsar, $confiavel, 
            $urlInstagram, $urlTwitter, $urlWhatsapp, $urlFacebook, $endereco, $og_type, $og_url, 
            $og_description, $twitter_card, $twitter_url, $twitter_title, $twitter_description, 
            $descriptionSeo, $descriptionKeywords, $titlePage
        ]);
    }

    // Buscar as lojas cadastradas
    $stmtLojas = $pdo->query("SELECT * FROM lojas ORDER BY nome ASC");
    $lojas = $stmtLojas->fetchAll(PDO::FETCH_ASSOC);

    // Verifica se a loja precisa ser excluída
    if (isset($_GET['excluir'])) {
        $idLoja = (int)$_GET['excluir'];
        // Exclui a loja do banco de dados
        $stmtExcluir = $pdo->prepare("DELETE FROM lojas WHERE id = ?");
        $stmtExcluir->execute([$idLoja]);

        // Redireciona após a exclusão
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }
?>

<?php include_once '../inc/head.php'; ?>
<body>
    <?php include_once '../backoffice/navbarDash.php'; ?>

    <div class="dashboard">
        <!-- /// -->
        <div class="container">
            <h2>Cadastrar Nova Loja</h2>
            <form method="post" enctype="multipart/form-data">
                <div class="block">
                    <div>
                        <label>Logo</label>
                        <input type="file" class="form-control" name="logoLoja" required>
                    </div>

                    <div>
                        <label>Categoria</label>
                        <select class="form-select" name="categoria">
                            <?php foreach ($categorias as $categoria): ?>
                                <option value="<?= htmlspecialchars($categoria['nome'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                                    <?= htmlspecialchars($categoria['nome'] ?? '', ENT_QUOTES, 'UTF-8') ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div>
                        <label>Nome</label>
                        <input type="text" class="form-control" name="nome" required>
                    </div>
                </div>

                <div class="block mt-3 mb-3">
                    <div>
                        <label>Descrição</label>
                        <input class="form-control" name="descricao" required></input>
                    </div>

                    <div>
                        <label>Avaliação</label>
                        <input class="form-control" type="number" step="0.1" name="avaliacao">
                    </div>

                    <div>
                        <label>Como Usar</label>
                        <input class="form-control" name="comoUsar"></input>
                    </div>

                    <div>
                        <label>Confiável</label>
                        <input class="form-control" name="confiavel"></input>
                    </div>
                </div>

                <div class="block mt-3 mb-3">
                    <div>
                        <label>Instagram</label>
                        <input class="form-control" type="text" name="urlInstagram">
                    </div>

                    <div>
                        <label>Twitter</label>
                        <input class="form-control" type="text" name="urlTwitter">
                    </div>

                    <div>
                        <label>WhatsApp</label>
                        <input class="form-control" type="text" name="urlWhatsapp">
                    </div>

                    <div>
                        <label>Facebook</label>
                        <input class="form-control" type="text" name="urlFacebook">
                    </div>
                </div>

                <h2>SEO</h2>
                <div class="block mt-3 mb-3">
                    <div>
                        <label>Endereço</label>
                        <input class="form-control" type="text" name="endereco">
                    </div>

                    <div>
                        <label>SEO Description</label>
                        <input class="form-control" name="descriptionSeo"></input>
                    </div>

                    <div>
                        <label>SEO Keywords</label>
                        <input class="form-control" name="descriptionKeywords"></input>
                    </div>

                    <div>
                        <label>Título da Página</label>
                        <input class="form-control" type="text" name="titlePage">
                    </div>
                </div>

                <div class="block mt-3 mb-3">
                    <div>
                        <label>OG Type</label>
                        <input class="form-control" type="text" name="og_type">
                    </div>

                    <div>
                        <label>OG URL</label>
                        <input class="form-control" type="text" name="og_url">
                    </div>

                    <div>
                        <label>OG Description</label>
                        <input type="text" class="form-control" name="og_description">
                    </div>

                    <div>
                        <label>Twitter Card</label>
                        <input class="form-control" type="text" name="twitter_card">
                    </div>
                </div>

                <div class="block">
                    <div>
                        <label>Twitter URL</label>
                        <input class="form-control" type="text" name="twitter_url">
                    </div>

                    <div>
                        <label>Twitter Title</label>
                        <input class="form-control" type="text" name="twitter_title">
                    </div>

                    <div>
                        <label>Twitter Description</label>
                        <input type="text" class="form-control" name="twitter_description">
                    </div>
                </div>
                <button class="btn btn-primary" type="submit">Cadastrar</button>
            </form>

            <h2>Lojas Cadastradas</h2>
            <div class="tableCustom">
                <div class="title">
                    <ul>
                        <li>Logo</li>
                        <li>Nome</li>
                        <li>Categoria</li>
                        <li>Instagram</li>
                        <li>Twitter</li>
                        <li>WhatsApp</li>
                        <li>Facebook</li>
                        <li>Ações</li>
                    </ul>
                </div>

                <div class="contentTable">
                    <?php foreach ($lojas as $loja): ?>
                        <ul>
                            <li><img src="<?php echo $base_url; ?>/<?= htmlspecialchars($loja['logoLoja'] ?? '', ENT_QUOTES, 'UTF-8') ?>" width="50"></li>
                            <li><?= htmlspecialchars($loja['nome'] ?? '', ENT_QUOTES, 'UTF-8') ?></li>
                            <li><?= htmlspecialchars($loja['categoria'] ?? '', ENT_QUOTES, 'UTF-8') ?></li>
                            <li><a href="<?= htmlspecialchars($loja['urlInstagram'] ?? '', ENT_QUOTES, 'UTF-8') ?>" target="_blank">Ver Url</a></li>
                            <li><a href="<?= htmlspecialchars($loja['urlTwitter'] ?? '', ENT_QUOTES, 'UTF-8') ?>" target="_blank">Ver Url</a></li>
                            <li><a href="<?= htmlspecialchars($loja['urlWhatsapp'] ?? '', ENT_QUOTES, 'UTF-8') ?>" target="_blank">Ver Url</a></li>
                            <li><a href="<?= htmlspecialchars($loja['urlFacebook'] ?? '', ENT_QUOTES, 'UTF-8') ?>" target="_blank">Ver Url</a></li>
                            <li>
                                <a href="<?php echo $base_url; ?>/backoffice/lojas.php?excluir=<?= htmlspecialchars($loja['id'] ?? '', ENT_QUOTES, 'UTF-8') ?>" onclick="return confirm('Tem certeza que deseja excluir esta loja?');">Excluir</a>
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
