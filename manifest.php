<?php
// Detecta se está em localhost ou produção
$base_url = (strpos($_SERVER['HTTP_HOST'], 'localhost') !== false)
    ? "http://localhost/olhaamiga"
    : "https://olhaamiga.com.br";

$manifest = [
    "name" => "Olha Amiga",
    "short_name" => "Olha Amiga",
    "description" => "Os melhores cupons e ofertas para mulheres!",
    "start_url" => "/",
    "scope" => "/",
    "display" => "standalone",
    "background_color" => "#ffffff",
    "theme_color" => "#ED4C67",
    "icons" => [
        [
            "src" => "$base_url/assets/icons/icon-192x192.png",
            "sizes" => "192x192",
            "type" => "image/png",
            "purpose" => "any"
        ],
        [
            "src" => "$base_url/assets/icons/icon-512x512.png",
            "sizes" => "512x512",
            "type" => "image/png",
            "purpose" => "any"
        ],
        [
            "src" => "$base_url/assets/icons/icon-192x192-maskable.png",
            "sizes" => "192x192",
            "type" => "image/png",
            "purpose" => "maskable"
        ],
        [
            "src" => "$base_url/assets/icons/icon-512x512-maskable.png",
            "sizes" => "512x512",
            "type" => "image/png",
            "purpose" => "maskable"
        ]
    ],
    "screenshots" => [
        [
            "src" => "$base_url/assets/icons/screenshot1.png",
            "sizes" => "1080x1920",
            "type" => "image/png",
            "form_factor" => "wide"
        ],
        [
            "src" => "$base_url/assets/icons/screenshot2.png",
            "sizes" => "1080x1920",
            "type" => "image/png"
        ]
    ]
];

// Define o cabeçalho para JSON
header("Content-Type: application/json");

// Converte o array PHP para JSON e exibe
echo json_encode($manifest, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
