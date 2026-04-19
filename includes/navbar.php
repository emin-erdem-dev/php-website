<?php
// includes/navbar.php — Öğrenci Not Takip Sistemi Navbar
$page = isset($page) ? $page : (isset($_GET['page']) ? $_GET['page'] : 'home');
$profile = getProfile();
?>
<nav class="nt-navbar" id="mainNavbar">
    <div class="container">
        <a href="index.php?page=home" class="nt-logo">
            <div class="nt-logo-icon"><i class="fa-solid fa-graduation-cap"></i></div>
            <span class="nt-logo-text">Not Takip</span>
        </a>

        <?php if (hasProfile()): ?>
        <div class="nt-nav-links" id="navLinks">
            <a href="index.php?page=home" <?= $page === 'home' ? 'class="active"' : '' ?>>
                <i class="fa-solid fa-chart-pie"></i> Dashboard
            </a>
            <a href="index.php?page=courses" <?= $page === 'courses' ? 'class="active"' : '' ?>>
                <i class="fa-solid fa-book"></i> Derslerim
            </a>
            <a href="index.php?page=grades" <?= $page === 'grades' ? 'class="active"' : '' ?>>
                <i class="fa-solid fa-pen-to-square"></i> Not Girişi
            </a>
            <a href="index.php?page=average" <?= $page === 'average' ? 'class="active"' : '' ?>>
                <i class="fa-solid fa-calculator"></i> Ortalama
            </a>
            <a href="index.php?page=videos" <?= $page === 'videos' ? 'class="active"' : '' ?>>
                <i class="fa-solid fa-video"></i> Videolar
            </a>
            <a href="index.php?page=goals" <?= $page === 'goals' ? 'class="active"' : '' ?>>
                <i class="fa-solid fa-clock"></i> Hedefler
            </a>
            <a href="index.php?page=achievements" <?= $page === 'achievements' ? 'class="active"' : '' ?>>
                <i class="fa-solid fa-medal"></i> Rozetler
            </a>
        </div>

        <div class="nt-nav-actions">
            <?php if ($profile): ?>
            <span style="font-size: var(--nt-fs-xs); color: var(--nt-text-dim);">
                <i class="fa-solid fa-user" style="color: var(--nt-primary);"></i>
                <?= htmlspecialchars($profile['name']) ?>
            </span>
            <?php endif; ?>
            <a href="index.php?page=reset" class="btn-ghost" style="font-size: var(--nt-fs-xs); color: var(--nt-red);" title="Sıfırla">
                <i class="fa-solid fa-power-off"></i>
            </a>
            <button class="nt-mobile-toggle" id="mobileMenuBtn" aria-label="Menu">
                <i class="fa-solid fa-bars"></i>
            </button>
        </div>
        <?php endif; ?>
    </div>
</nav>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const btn = document.getElementById('mobileMenuBtn');
    const nav = document.getElementById('navLinks');
    if (btn && nav) {
        btn.addEventListener('click', function() {
            nav.classList.toggle('show');
            const icon = btn.querySelector('i');
            icon.classList.toggle('fa-bars');
            icon.classList.toggle('fa-xmark');
        });
    }
});
</script>
