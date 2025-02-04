<?php
require_once '../inc/db.php';

// Buscar todas as lojas cadastradas
$stmtLojas = $pdo->query("SELECT * FROM lojas ORDER BY nome ASC");
$lojas = $stmtLojas->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include_once '../inc/head.php'; ?>
<body>
    <header>
        <?php include_once '../inc/navbar.php'; ?>
    </header>

    <h1>Lista de Lojas</h1>
    
    <?php if (!empty($lojas)): ?>
        <ul>
            <?php foreach ($lojas as $loja): ?>
                <li>
                    <a href="/olha/desconto/<?= htmlspecialchars($loja['slug']) ?>">
                        <strong><?= htmlspecialchars($loja['nome']) ?></strong>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>Nenhuma loja cadastrada.</p>
    <?php endif; ?>
    
    <?php include_once '../inc/bottom.php'; ?>
</body>
</html>
