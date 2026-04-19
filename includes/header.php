<?php
// includes/header.php — Öğrenci Not Takip Sistemi Global Header
?><!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Öğrenci Not Takip Sistemi — Derslerini ekle, notlarını gir, ortalamanı hesapla ve önerilen ders videolarını izle.">
    <meta name="theme-color" content="#0f0f18">
    <title>📚 Not Takip — Öğrenci Not Takip Sistemi</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Stylesheets -->
    <link rel="stylesheet" href="assets/css/variables.css">
    <link rel="stylesheet" href="assets/css/base.css">
    <link rel="stylesheet" href="assets/css/components.css">
    <link rel="stylesheet" href="assets/css/advanced-animations.css">
</head>
<body>

<?php require_once 'includes/navbar.php'; ?>
<?php displayFlash(); ?>
<main>
