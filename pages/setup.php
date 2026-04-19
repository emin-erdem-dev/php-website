<?php
// pages/setup.php — Profil Oluşturma (İlk Kayıt) Sayfası
require_once 'config/data.php';
require_once 'includes/functions.php';

// Zaten profili varsa ana sayfaya yönlendir
if (hasProfile()) {
    redirect('index.php?page=home');
}

global $education_levels;
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🎓 Hoş Geldiniz — Not Takip Sistemi</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="assets/css/variables.css">
    <link rel="stylesheet" href="assets/css/base.css">
    <link rel="stylesheet" href="assets/css/components.css">
</head>
<body>

<?php displayFlash(); ?>

<div class="setup-page">
    <div class="setup-container" style="animation: fadeInUp 0.6s ease;">
        <div class="setup-header">
            <div class="setup-logo">
                <i class="fa-solid fa-graduation-cap"></i>
            </div>
            <h1>Öğrenci Not Takip</h1>
            <p>Derslerini ekle, notlarını gir, ortalamanı hesapla ve önerilen ders videolarını izle!</p>
        </div>

        <form method="POST" action="index.php?page=setup" id="setupForm">
            <input type="hidden" name="action" value="setup_profile">

            <!-- İsim -->
            <div class="form-group">
                <label class="form-label"><i class="fa-solid fa-user" style="color: var(--nt-primary); margin-right: 6px;"></i>Adın Soyadın</label>
                <input type="text" name="student_name" class="form-input" placeholder="Örn: Ahmet Yılmaz" required autocomplete="off" maxlength="60">
            </div>

            <!-- Eğitim Seviyesi -->
            <div class="form-group">
                <label class="form-label"><i class="fa-solid fa-school" style="color: var(--nt-primary); margin-right: 6px;"></i>Eğitim Seviyen</label>
                <div class="level-grid">
                    <?php foreach ($education_levels as $key => $level): ?>
                    <div class="level-option">
                        <input type="radio" name="education_level" value="<?= $key ?>" id="level_<?= $key ?>" required>
                        <label for="level_<?= $key ?>">
                            <div class="level-option-icon" style="background: <?= $level['color'] ?>20; color: <?= $level['color'] ?>;">
                                <i class="<?= $level['icon'] ?>"></i>
                            </div>
                            <span class="level-option-name"><?= $level['name'] ?></span>
                            <span class="level-option-desc"><?= $level['description'] ?></span>
                            <span class="level-option-desc" style="color: var(--nt-primary);">
                                Kredi: <?= $level['credit_range'][0] ?> - <?= $level['credit_range'][1] ?>
                            </span>
                        </label>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <button type="submit" class="btn btn-primary btn-lg" style="width: 100%; margin-top: var(--nt-sp-4);">
                <i class="fa-solid fa-rocket"></i> Başla
            </button>
        </form>
    </div>
</div>

<!-- Arka plan parçacıkları -->
<style>
.setup-page::after {
    content: '';
    position: absolute;
    bottom: -100px;
    right: -100px;
    width: 400px;
    height: 400px;
    background: radial-gradient(circle, rgba(6, 182, 212, 0.08) 0%, transparent 70%);
    pointer-events: none;
}
</style>

</body>
</html>
