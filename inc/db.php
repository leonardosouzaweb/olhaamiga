<?php
// Detecta se está rodando em ambiente local (dev)
$is_local = in_array($_SERVER['HTTP_HOST'], ['localhost', '127.0.0.1']);

// Configuração do banco de dados para ambiente local (dev)
if ($is_local) {
    $host = 'localhost';
    $dbname = 'olhaamiga'; // Nome do banco local
    $username = 'root';
    $password = 'root';
} else {
    // Configuração para produção
    $host = 'sh-pro138.hostgator.com.br';
    $dbname = 'leona497_olha'; // Nome do banco na produção
    $username = 'leona497_olha';
    $password = 'Leopif2kkay@';
}

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro na conexão com o banco de dados: " . $e->getMessage());
}
?>
