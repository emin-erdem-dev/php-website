<?php
// pages/achievements.php — Başarılarım / Oyunlaştırma
$profile = getProfile();
$courses = getCourses();
$stats = getStats();

// Rozet Şartları / Oyunlaştırma Mantığı
$badgeData = [
    'first_step' => [
        'title' => 'İlk Adım',
        'desc' => 'Ders takibine başladın ve en az bir ders ekledin.',
        'icon' => 'fa-solid fa-shoe-prints',
        'color' => '#3b82f6', // blue
        'unlocked' => count($courses) > 0
    ],
    'grade_starter' => [
        'title' => 'Öğrenci Doğuyor',
        'desc' => 'Sisteme ilk sınav notunu kaydettin.',
        'icon' => 'fa-solid fa-pen',
        'color' => '#10b981', // green
        'unlocked' => $stats['graded_count'] > 0
    ],
    'nerd' => [
        'title' => 'İnek Öğrenci',
        'desc' => 'Bir dersten 90 veya üstü ortalama yakaladın!',
        'icon' => 'fa-solid fa-glasses',
        'color' => '#f59e0b', // orange
        'unlocked' => $stats['highest_avg'] >= 90
    ],
    'heavy_load' => [
        'title' => 'Ağır Yük',
        'desc' => 'Toplamda 5 veya daha fazla ders girildi.',
        'icon' => 'fa-solid fa-weight-hanging',
        'color' => '#8b5cf6', // purple
        'unlocked' => count($courses) >= 5
    ],
    'perfect_gpa' => [
        'title' => 'Kusursuz Seçkin',
        'desc' => 'Genel ortalaman 95\'in veya 3.8 GPA\'nın üzerinde.',
        'icon' => 'fa-solid fa-crown',
        'color' => '#facc15', // yellow/gold
        'unlocked' => ($stats['overall_avg'] >= 95 || ($stats['gpa'] !== null && $stats['gpa'] >= 3.8)) && $stats['graded_count'] >= 2
    ],
    'survivor' => [
        'title' => 'Hayatta Kalan',
        'desc' => 'Bir dersin ortalaması 50-60 sınırında!',
        'icon' => 'fa-solid fa-heart-pulse',
        'color' => '#ef4444', // red
        'unlocked' => $stats['lowest_avg'] >= 50 && $stats['lowest_avg'] <= 60 && $stats['graded_count'] > 0
    ]
];

$unlockedCount = array_reduce($badgeData, function($carry, $item) {
    return $carry + ($item['unlocked'] ? 1 : 0);
}, 0);
$totalBadges = count($badgeData);
$progressPercent = ($unlockedCount / $totalBadges) * 100;

?>

<div class="page-hero">
    <div class="container" style="text-align: center;">
        <h1 style="animation: fadeInUp 0.5s ease;">
            <i class="fa-solid fa-medal" style="color: var(--nt-accent);"></i> Başarı Rozetleri
        </h1>
        <p style="margin: 0 auto; animation: fadeInUp 0.5s ease 0.1s; animation-fill-mode: both;">
            Notlarını yükseltip hedeflere ulaştıkça yeni yeteneklerin kilitlerini aç. <br>
            Şu ana kadar <strong> <?= $unlockedCount ?> / <?= $totalBadges ?> </strong> rozet kazandın!
        </p>

        <div style="max-width: 400px; margin: var(--nt-sp-5) auto 0; animation: fadeInUp 0.5s ease 0.2s; animation-fill-mode: both;">
            <div class="progress-bar" style="height: 12px;">
                <div class="progress-fill" style="width: <?= $progressPercent ?>%; --progress-color: var(--nt-accent);"></div>
            </div>
        </div>
    </div>
</div>

<div class="container section" style="padding-top: 0;">
    <div class="course-grid stagger-in">
        <?php foreach ($badgeData as $key => $badge): ?>
        
        <div class="achieve-card <?= $badge['unlocked'] ? 'unlocked' : '' ?>" style="--achieve-color: <?= $badge['color'] ?>; --achieve-glow: 0 0 25px <?= $badge['color'] ?>40;">
            <div class="achieve-icon">
                <?php if ($badge['unlocked']): ?>
                    <i class="<?= $badge['icon'] ?>"></i>
                <?php else: ?>
                    <i class="fa-solid fa-lock" style="font-size: 1.2rem; opacity: 0.5;"></i>
                <?php endif; ?>
            </div>
            
            <div class="achieve-title">
                <?= $badge['unlocked'] ? $badge['title'] : '?????' ?>
            </div>
            <div class="achieve-desc">
                <?= $badge['unlocked'] ? $badge['desc'] : 'Bu rozetin kilidini açmak için okumaya devam et.' ?>
            </div>

            <?php if ($badge['unlocked']): ?>
            <div style="margin-top: var(--nt-sp-4);">
                <span class="badge" style="background: <?= $badge['color'] ?>20; color: <?= $badge['color'] ?>;">
                    <i class="fa-solid fa-check"></i> Kazanıldı
                </span>
            </div>
            <?php endif; ?>
        </div>
        
        <?php endforeach; ?>
    </div>
</div>
