<?php
require_once '../inc/db.php';

$username = 'admin';
$password = '2248228';

// Criptografa a senha antes de salvá-la
$hashed_password = password_hash($password, PASSWORD_BCRYPT);

try {
    // Prepara a inserção
    $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (:username, :password)");

    // Associa os valores aos parâmetros
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':password', $hashed_password);

    // Executa a query
    if ($stmt->execute()) {
        echo "Usuário criado com sucesso.";
    } else {
        echo "Erro ao criar usuário.";
    }
} catch (PDOException $e) {
    echo "Erro: " . $e->getMessage();
}
?>
