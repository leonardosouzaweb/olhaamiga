<?php 
    session_start();
    if (!isset($_SESSION['user_id'])) {
        header('Location: login.php');
        exit;
    }
?>
<?php include_once '../inc/head.php'?>
<body>
    <?php include_once '../backoffice/navbarDash.php'; ?>

    <div class="dashboard">
        <div class="container">
            <h2>Informações</h2>
        </div>
    </div>
    <?php include_once '../inc/bottom.php'; ?>
</body>
</html>