<?php
// pages/home.php — Dashboard (Ana Sayfa)
$profile = getProfile();
$stats = getStats();
$courses = getCourses();
$lowCourses = getLowGradeCourses(60);

global $education_levels;
$levelInfo = $education_levels[$profile['level']] ?? null;
?>

<div class="page-hero">
    <div class="container">
        <h1 style="animation: fadeInUp 0.5s ease;">
            Merhaba, <?= htmlspecialchars($profile['name']) ?>!
            <span style="font-size: 0.6em; display: inline-block; margin-left: 8px;">
                <?php if ($stats['overall_avg'] !== null): ?>
                    <?= getGradeEmoji($stats['overall_avg']) ?>
                <?php else: ?>
                    👋
                <?php endif; ?>
            </span>
        </h1>
        <p style="animation: fadeInUp 0.5s ease 0.1s; animation-fill-mode: both;">
            <span class="badge badge-purple" style="margin-right: 8px;">
                <i class="<?= $levelInfo['icon'] ?? 'fa-solid fa-school' ?>"></i>
                <?= $levelInfo['name'] ?? 'Öğrenci' ?>
            </span>
            Not takip paneline hoş geldin. Derslerini ekle, notlarını gir ve ortalamanı takip et.
        </p>
    </div>
</div>

<div class="container section" style="padding-top: 0;">
    <!-- İstatistik Kartları -->
    <div class="stat-grid stagger-in">
        <!-- Toplam Ders -->
        <div class="stat-card" style="--stat-color: var(--nt-primary); --stat-color-soft: var(--nt-primary-soft);">
            <div class="stat-card-icon"><i class="fa-solid fa-book"></i></div>
            <div class="stat-card-value"><?= $stats['total_courses'] ?></div>
            <div class="stat-card-label">Toplam Ders</div>
        </div>

        <!-- Toplam Kredi -->
        <div class="stat-card" style="--stat-color: var(--nt-blue); --stat-color-soft: rgba(59,130,246,0.15);">
            <div class="stat-card-icon" style="color: var(--nt-blue);"><i class="fa-solid fa-coins"></i></div>
            <div class="stat-card-value"><?= $stats['total_credits'] ?></div>
            <div class="stat-card-label">Toplam Kredi</div>
        </div>

        <!-- Genel Ortalama -->
        <div class="stat-card" style="--stat-color: <?= getGradeColor($stats['overall_avg']) ?>; --stat-color-soft: <?= getGradeColor($stats['overall_avg']) ?>20;">
            <div class="stat-card-icon" style="color: <?= getGradeColor($stats['overall_avg']) ?>;"><i class="fa-solid fa-chart-line"></i></div>
            <div class="stat-card-value"><?= $stats['overall_avg'] !== null ? $stats['overall_avg'] : '—' ?></div>
            <div class="stat-card-label">Genel Ortalama (100'lük)</div>
        </div>

        <!-- GPA veya Not Sayısı -->
        <?php if ($levelInfo && $levelInfo['gpa_type'] === 'gpa4'): ?>
        <div class="stat-card" style="--stat-color: var(--nt-accent); --stat-color-soft: rgba(245,158,11,0.15);">
            <div class="stat-card-icon" style="color: var(--nt-accent);"><i class="fa-solid fa-star"></i></div>
            <div class="stat-card-value"><?= $stats['gpa'] !== null ? number_format($stats['gpa'], 2) : '—' ?></div>
            <div class="stat-card-label">GPA (4.0)</div>
        </div>
        <?php else: ?>
        <div class="stat-card" style="--stat-color: var(--nt-green); --stat-color-soft: rgba(16,185,129,0.15);">
            <div class="stat-card-icon" style="color: var(--nt-green);"><i class="fa-solid fa-check-double"></i></div>
            <div class="stat-card-value"><?= $stats['graded_count'] ?> / <?= $stats['total_courses'] ?></div>
            <div class="stat-card-label">Notu Girilmiş Ders</div>
        </div>
        <?php endif; ?>
    </div>

    <!-- Düşük Not Uyarısı -->
    <?php if (!empty($lowCourses)): ?>
    <div class="alert-box alert-box-warning" style="animation: fadeInUp 0.5s ease 0.3s; animation-fill-mode: both;">
        <i class="fa-solid fa-triangle-exclamation" style="font-size: 1.2rem;"></i>
        <div>
            <strong><?= count($lowCourses) ?> derste notun düşük!</strong>
            <span style="color: var(--nt-text-soft); display: block; font-size: var(--nt-fs-xs); margin-top: 2px;">
                <?php foreach ($lowCourses as $i => $lc): ?>
                    <?= htmlspecialchars($lc['name']) ?> (<?= $lc['average'] ?>)<?= $i < count($lowCourses) - 1 ? ', ' : '' ?>
                <?php endforeach; ?>
                — <a href="index.php?page=videos" style="color: var(--nt-accent); font-weight: 600;">Önerilen videoları izle →</a>
            </span>
        </div>
    </div>
    <?php endif; ?>

    <!-- Hızlı İşlemler -->
    <div class="section-header" style="margin-top: var(--nt-sp-6);">
        <h2><i class="fa-solid fa-bolt"></i> Hızlı İşlemler</h2>
    </div>

    <div class="stat-grid stagger-in" style="grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));">
        <a href="index.php?page=courses" class="stat-card" style="text-decoration: none; cursor: pointer; --stat-color: var(--nt-cyan);">
            <div class="stat-card-icon" style="color: var(--nt-cyan); background: rgba(34,211,238,0.15);">
                <i class="fa-solid fa-plus"></i>
            </div>
            <div class="stat-card-label" style="color: #fff; font-weight: 600; font-size: var(--nt-fs-md);">Ders Ekle</div>
            <div class="stat-card-label">Yeni ders oluştur</div>
        </a>

        <a href="index.php?page=grades" class="stat-card" style="text-decoration: none; cursor: pointer; --stat-color: var(--nt-green);">
            <div class="stat-card-icon" style="color: var(--nt-green); background: rgba(16,185,129,0.15);">
                <i class="fa-solid fa-pen-to-square"></i>
            </div>
            <div class="stat-card-label" style="color: #fff; font-weight: 600; font-size: var(--nt-fs-md);">Not Gir</div>
            <div class="stat-card-label">Sınav notlarını gir</div>
        </a>

        <a href="index.php?page=average" class="stat-card" style="text-decoration: none; cursor: pointer; --stat-color: var(--nt-accent);">
            <div class="stat-card-icon" style="color: var(--nt-accent); background: rgba(245,158,11,0.15);">
                <i class="fa-solid fa-calculator"></i>
            </div>
            <div class="stat-card-label" style="color: #fff; font-weight: 600; font-size: var(--nt-fs-md);">Ortalama</div>
            <div class="stat-card-label">GPA hesapla</div>
        </a>

        <a href="index.php?page=videos" class="stat-card" style="text-decoration: none; cursor: pointer; --stat-color: var(--nt-red);">
            <div class="stat-card-icon" style="color: var(--nt-red); background: rgba(239,68,68,0.15);">
                <i class="fa-solid fa-video"></i>
            </div>
            <div class="stat-card-label" style="color: #fff; font-weight: 600; font-size: var(--nt-fs-md);">Ders Videoları</div>
            <div class="stat-card-label">Önerilen videolar</div>
        </a>
    </div>

    <!-- Son Eklenen Dersler -->
    <?php if (!empty($courses)): ?>
    <div class="section-header" style="margin-top: var(--nt-sp-10);">
        <h2><i class="fa-solid fa-clock-rotate-left"></i> Derslerim</h2>
        <a href="index.php?page=courses" class="btn btn-secondary btn-sm"><i class="fa-solid fa-arrow-right"></i> Tümünü Gör</a>
    </div>

    <div class="nt-table-wrapper" style="animation: fadeInUp 0.5s ease;">
        <table class="nt-table">
            <thead>
                <tr>
                    <th>Ders</th>
                    <th>Kredi</th>
                    <th>Vize</th>
                    <th>Final</th>
                    <th>Ödev</th>
                    <th>Ortalama</th>
                    <?php if ($levelInfo && $levelInfo['gpa_type'] === 'gpa4'): ?>
                    <th>Harf</th>
                    <?php endif; ?>
                    <th>Durum</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach (array_slice(array_reverse($courses), 0, 5) as $course):
                    $avg = calculateCourseAverage($course['grades']);
                    $letter = getLetterGrade($avg);
                    $color = getGradeColor($avg);
                ?>
                <tr>
                    <td style="font-weight: 600;"><?= htmlspecialchars($course['name']) ?></td>
                    <td><span class="badge badge-purple"><?= $course['credit'] ?></span></td>
                    <td><?= $course['grades']['midterm'] !== null ? $course['grades']['midterm'] : '<span style="color:var(--nt-text-muted);">—</span>' ?></td>
                    <td><?= $course['grades']['final'] !== null ? $course['grades']['final'] : '<span style="color:var(--nt-text-muted);">—</span>' ?></td>
                    <td><?= $course['grades']['homework'] !== null ? $course['grades']['homework'] : '<span style="color:var(--nt-text-muted);">—</span>' ?></td>
                    <td><strong style="color: <?= $color ?>;"><?= $avg !== null ? $avg : '—' ?></strong></td>
                    <?php if ($levelInfo && $levelInfo['gpa_type'] === 'gpa4'): ?>
                    <td><span class="badge" style="background: <?= $letter['color'] ?>20; color: <?= $letter['color'] ?>;"><?= $letter['letter'] ?></span></td>
                    <?php endif; ?>
                    <td><?= getGradeEmoji($avg) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>
</div>
