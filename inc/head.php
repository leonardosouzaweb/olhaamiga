<?php
    $host = $_SERVER['HTTP_HOST'];
    $is_local = in_array($host, ['localhost', '127.0.0.1']);
    
    $protocol = (!$is_local && (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')) ? "https" : "http";
    
    // Define a base URL
    $base_url = $is_local ? "/olhaamiga" : $protocol . "://" . $host;
    
    // Define a URL da página atual
    $page_url = isset($page_url) ? $page_url : $protocol . "://" . $host . $_SERVER['REQUEST_URI'];
    

    $page_title = isset($page_title) ? $page_title : "Olha Amiga | Cupons de Descontos e Ofertas para Mulheres!";
    $page_description = isset($page_description) ? $page_description : "Economize com os melhores cupons de desconto e ofertas exclusivas para mulheres. Descubra promoções incríveis agora!";
    $page_keywords = isset($page_keywords) ? $page_keywords : "cupons de desconto, ofertas para mulheres, promoções, cashback, economizar, compras online";
    $page_image = isset($page_image) ? $page_image : "$base_url/assets/images/logo-share.png";
    $page_type = isset($page_type) ? $page_type : "website";

    $is_home = ($page_url == $base_url);
    $is_store = strpos($page_url, "/desconto/") !== false;
    $is_category = strpos($page_url, "/cupom/") !== false;
    $is_offer = strpos($page_url, "/cupom/") !== false && !empty($offer_name);

    // Estrutura base
    $json_ld = [
        "@context" => "https://schema.org",
        "@type" => "WebSite",
        "name" => "Olha Amiga",
        "url" => $base_url,
        "description" => isset($page_description) ? $page_description : "Os melhores cupons de desconto exclusivos para mulheres.",
        "publisher" => [
            "@type" => "Organization",
            "name" => "Olha Amiga",
            "logo" => "$base_url/assets/images/logo.png"
        ],
        "image" => isset($page_image) ? $page_image : "$base_url/assets/images/share.jpg"
    ];

    // Se for uma página de Loja (exemplo: Natura)
    if ($is_store) {
        $store_name = isset($store_name) ? $store_name : "Loja Parceira";
        $json_ld["@type"] = "Store";
        $json_ld["name"] = $store_name;
        $json_ld["url"] = $page_url;
        $json_ld["image"] = isset($page_image) ? $page_image : "$base_url/assets/images/store-default.jpg";
        $json_ld["sameAs"] = [
            "https://www.facebook.com/{$store_name}",
            "https://www.instagram.com/{$store_name}"
        ];
        $json_ld["offers"] = [
            "@type" => "Offer",
            "name" => "Cupons de desconto para " . $store_name,
            "url" => $page_url,
            "priceCurrency" => "BRL",
            "availability" => "https://schema.org/InStock",
            "validFrom" => date("Y-m-d")
        ];
    }

    // Se for uma categoria de cupons
    if ($is_category) {
        $category_name = isset($category_name) ? $category_name : "Cupons e Descontos";
        $json_ld["@type"] = "CollectionPage";
        $json_ld["name"] = $category_name;
        $json_ld["url"] = $page_url;
    }

    // Se for um cupom de desconto específico
    if ($is_offer) {
        $json_ld["@type"] = "Offer";
        $json_ld["name"] = isset($offer_name) ? $offer_name : "Oferta Especial";
        $json_ld["url"] = $page_url;
        $json_ld["price"] = isset($offer_price) ? $offer_price : "0";
        $json_ld["priceCurrency"] = "BRL";
        $json_ld["availability"] = "https://schema.org/InStock";
        $json_ld["validFrom"] = isset($offer_start_date) ? $offer_start_date : date("Y-m-d");
        $json_ld["validThrough"] = isset($offer_end_date) ? $offer_end_date : date("Y-m-d", strtotime("+7 days"));
    }
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">

    <!-- Título e Meta Description -->
    <title><?php echo htmlspecialchars($page_title, ENT_QUOTES, 'UTF-8'); ?></title>
    <meta name="description" content="<?php echo htmlspecialchars($page_description, ENT_QUOTES, 'UTF-8'); ?>">
    <meta name="keywords" content="<?php echo htmlspecialchars($page_keywords, ENT_QUOTES, 'UTF-8'); ?>">
    <meta name="author" content="Olha Amiga">
    <meta name="robots" content="index, follow">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="<?php echo htmlspecialchars($page_type, ENT_QUOTES, 'UTF-8'); ?>">
    <meta property="og:url" content="<?php echo htmlspecialchars($page_url, ENT_QUOTES, 'UTF-8'); ?>">
    <meta property="og:title" content="<?php echo htmlspecialchars($page_title, ENT_QUOTES, 'UTF-8'); ?>">
    <meta property="og:description" content="<?php echo htmlspecialchars($page_description, ENT_QUOTES, 'UTF-8'); ?>">
    <meta property="og:image" content="<?php echo htmlspecialchars($page_image, ENT_QUOTES, 'UTF-8'); ?>">

    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:url" content="<?php echo htmlspecialchars($page_url, ENT_QUOTES, 'UTF-8'); ?>">
    <meta name="twitter:title" content="<?php echo htmlspecialchars($page_title, ENT_QUOTES, 'UTF-8'); ?>">
    <meta name="twitter:description" content="<?php echo htmlspecialchars($page_description, ENT_QUOTES, 'UTF-8'); ?>">
    <meta name="twitter:image" content="<?php echo htmlspecialchars($page_image, ENT_QUOTES, 'UTF-8'); ?>">

    <!-- URL Canônica Corrigida -->
    <link rel="canonical" href="<?php echo htmlspecialchars($page_url, ENT_QUOTES, 'UTF-8'); ?>">

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="<?php echo $base_url; ?>/assets/images/favicon.png">
    <link rel="apple-touch-icon" sizes="180x180" href="<?php echo $base_url; ?>/assets/images/apple-touch-icon.png">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@100..1000&display=swap" rel="stylesheet">

    <!-- CSS -->
    <link rel="stylesheet" href="<?php echo $base_url; ?>/assets/css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Progressive Web App (PWA) -->
    <link rel="manifest" href="<?php echo $base_url; ?>/manifest.php">
    <meta name="theme-color" content="#ED4C67">

    <script>
        if ("serviceWorker" in navigator) {
            navigator.serviceWorker.register("<?php echo $base_url; ?>/service-worker.js")
                .then(reg => console.log("Service Worker registrado!", reg))
                .catch(err => console.log("Erro ao registrar o Service Worker", err));
        }
    </script>

    <!-- Dados Estruturados JSON-LD -->
    <script type="application/ld+json">
        <?php echo json_encode($json_ld, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE); ?>
    </script>

</head>
