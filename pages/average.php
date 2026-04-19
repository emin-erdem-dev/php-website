<?php
// pages/average.php — Ortalama Hesaplama Sayfası
$profile = getProfile();
$courses = getCourses();
$stats = getStats();

global $education_levels, $letter_grades;
$levelInfo = $education_levels[$profile['level']] ?? null;

$gpa = $stats['gpa'];
$overallAvg = $stats['overall_avg'];
?>

<div class="page-hero">
    <div class="container">
        <h1 style="animation: fadeInUp 0.5s ease;">
            <i class="fa-solid fa-calculator" style="color: var(--nt-accent);"></i> Ortalama Hesaplama
        </h1>
        <p style="animation: fadeInUp 0.5s ease 0.1s; animation-fill-mode: both;">
            Tüm derslerinin kredi ağırlıklı ortalamasını görüntüle.
            <?php if ($levelInfo): ?>
            <span class="badge badge-purple" style="margin-left: 8px;">
                <?= $levelInfo['name'] ?> — <?= $levelInfo['gpa_type'] === 'gpa4' ? '4.0 GPA Sistemi' : 'Ağırlıklı Ortalama' ?>
            </span>
            <?php endif; ?>
        </p>
    </div>
</div>

<div class="container section" style="padding-top: 0;">
    <?php if (empty($courses) || $overallAvg === null): ?>
    <div class="empty-state">
        <div class="empty-state-icon"><i class="fa-solid fa-calculator"></i></div>
        <h3>Hesaplanacak not yok</h3>
        <p>Ortalama hesaplanabilmesi için en az bir derse not girmiş olman gerekiyor.</p>
        <a href="index.php?page=grades" class="btn btn-primary">
            <i class="fa-solid fa-pen-to-square"></i> Not Gir
        </a>
    </div>
    <?php else: ?>

    <!-- GPA Gösterimi -->
    <div class="card" style="animation: fadeInUp 0.5s ease 0.2s; animation-fill-mode: both;">
        <div class="gpa-display">
            <?php
            $gpaColor = getGradeColor($overallAvg);
            $rotation = min(($overallAvg / 100) * 360, 360);
            ?>
            <div class="gpa-ring" style="--gpa-color: <?= $gpaColor ?>; --gpa-rotation: <?= $rotation ?>deg; border-color: <?= $gpaColor ?>20;">
                <?php if ($levelInfo['gpa_type'] === 'gpa4' && $gpa !== null): ?>
                <div class="gpa-value"><?= number_format($gpa, 2) ?></div>
                <div class="gpa-label">/ 4.00 GPA</div>
                <?php else: ?>
                <div class="gpa-value"><?= $overallAvg ?></div>
                <div class="gpa-label">/ 100</div>
                <?php endif; ?>
            </div>
            
            <?php if ($levelInfo['gpa_type'] === 'gpa4' && $overallAvg !== null): ?>
            <?php $mainLetter = getLetterGrade($overallAvg); ?>
            <div class="gpa-letter" style="background: <?= $mainLetter['color'] ?>20; color: <?= $mainLetter['color'] ?>;">
                <i class="fa-solid fa-award"></i>
                Harf Notu: <?= $mainLetter['letter'] ?>
            </div>
            <?php endif; ?>

            <p style="margin-top: var(--nt-sp-4); color: var(--nt-text-dim); font-size: var(--nt-fs-sm);">
                <?= getGradeEmoji($overallAvg) ?>
                Genel 100'lük Ortalama: <strong style="color: <?= $gpaColor ?>;"><?= $overallAvg ?></strong>
                — Toplam Kredi: <strong style="color: var(--nt-primary);"><?= $stats['total_credits'] ?></strong>
            </p>
        </div>
    </div>

    <!-- İstatistikler -->
    <div class="stat-grid stagger-in" style="margin-top: var(--nt-sp-8);">
        <div class="stat-card" style="--stat-color: var(--nt-green);">
            <div class="stat-card-icon" style="color: var(--nt-green); background: rgba(16,185,129,0.15);"><i class="fa-solid fa-arrow-up"></i></div>
            <div class="stat-card-value" style="font-size: var(--nt-fs-xl);"><?= $stats['highest_avg'] ?></div>
            <div class="stat-card-label">En Yüksek — <?= htmlspecialchars($stats['highest_course']) ?></div>
        </div>
        <div class="stat-card" style="--stat-color: var(--nt-red);">
            <div class="stat-card-icon" style="color: var(--nt-red); background: rgba(239,68,68,0.15);"><i class="fa-solid fa-arrow-down"></i></div>
            <div class="stat-card-value" style="font-size: var(--nt-fs-xl);"><?= $stats['lowest_avg'] ?></div>
            <div class="stat-card-label">En Düşük — <?= htmlspecialchars($stats['lowest_course']) ?></div>
        </div>
        <div class="stat-card" style="--stat-color: var(--nt-accent);">
            <div class="stat-card-icon" style="color: var(--nt-accent); background: rgba(245,158,11,0.15);"><i class="fa-solid fa-warning"></i></div>
            <div class="stat-card-value" style="font-size: var(--nt-fs-xl);"><?= $stats['low_grade_count'] ?></div>
            <div class="stat-card-label">Başarısız Ders (< 60)</div>
        </div>
        <div class="stat-card" style="--stat-color: var(--nt-blue);">
            <div class="stat-card-icon" style="color: var(--nt-blue); background: rgba(59,130,246,0.15);"><i class="fa-solid fa-check-double"></i></div>
            <div class="stat-card-value" style="font-size: var(--nt-fs-xl);"><?= $stats['graded_count'] ?> / <?= $stats['total_courses'] ?></div>
            <div class="stat-card-label">Not Girilmiş Ders</div>
        </div>
    </div>

    <!-- Ders Detay Tablosu -->
    <div class="section-header" style="margin-top: var(--nt-sp-10);">
        <h2><i class="fa-solid fa-table-list"></i> Ders Detayları</h2>
    </div>

    <div class="nt-table-wrapper" style="animation: fadeInUp 0.5s ease 0.3s; animation-fill-mode: both;">
        <table class="nt-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Ders</th>
                    <th>Kredi</th>
                    <th>Vize</th>
                    <th>Final</th>
                    <th>Ödev</th>
                    <th>Ortalama</th>
                    <?php if ($levelInfo['gpa_type'] === 'gpa4'): ?>
                    <th>Harf Notu</th>
                    <th>Katsayı</th>
                    <th>Kredi × Katsayı</th>
                    <?php else: ?>
                    <th>Kredi × Ortalama</th>
                    <?php endif; ?>
                    <th>Durum</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 0;
                $totalWeighted = 0;
                $totalCreds = 0;
                foreach ($courses as $course):
                    $no++;
                    $avg = calculateCourseAverage($course['grades']);
                    $letter = getLetterGrade($avg);
                    $color = getGradeColor($avg);
                    
                    if ($avg !== null) {
                        if ($levelInfo['gpa_type'] === 'gpa4') {
                            $weighted = $letter['gpa'] * $course['credit'];
                        } else {
                            $weighted = $avg * $course['credit'];
                        }
                        $totalWeighted += $weighted;
                        $totalCreds += $course['credit'];
                    } else {
                        $weighted = null;
                    }
                ?>
                <tr>
                    <td style="color: var(--nt-text-muted);"><?= $no ?></td>
                    <td style="font-weight: 600;"><?= htmlspecialchars($course['name']) ?></td>
                    <td><span class="badge badge-purple"><?= $course['credit'] ?></span></td>
                    <td><?= $course['grades']['midterm'] !== null ? $course['grades']['midterm'] : '<span style="color:var(--nt-text-muted);">—</span>' ?></td>
                    <td><?= $course['grades']['final'] !== null ? $course['grades']['final'] : '<span style="color:var(--nt-text-muted);">—</span>' ?></td>
                    <td><?= $course['grades']['homework'] !== null ? $course['grades']['homework'] : '<span style="color:var(--nt-text-muted);">—</span>' ?></td>
                    <td><strong style="color: <?= $color ?>;"><?= $avg !== null ? $avg : '—' ?></strong></td>
                    <?php if ($levelInfo['gpa_type'] === 'gpa4'): ?>
                    <td><span class="badge" style="background: <?= $letter['color'] ?>20; color: <?= $letter['color'] ?>;"><?= $letter['letter'] ?></span></td>
                    <td style="color: <?= $letter['color'] ?>; font-weight: 600;"><?= $avg !== null ? number_format($letter['gpa'], 1) : '—' ?></td>
                    <td style="font-weight: 600;"><?= $weighted !== null ? number_format($weighted, 2) : '—' ?></td>
                    <?php else: ?>
                    <td style="font-weight: 600;"><?= $weighted !== null ? number_format($weighted, 2) : '—' ?></td>
                    <?php endif; ?>
                    <td style="font-size: 1.3rem;"><?= getGradeEmoji($avg) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr style="background: var(--nt-surface-2); font-weight: 700;">
                    <td colspan="2" style="color: #fff;">TOPLAM / ORTALAMA</td>
                    <td><span class="badge badge-purple"><?= $totalCreds ?></span></td>
                    <td colspan="3"></td>
                    <td style="color: <?= getGradeColor($overallAvg) ?>;"><?= $overallAvg ?></td>
                    <?php if ($levelInfo['gpa_type'] === 'gpa4'): ?>
                    <?php $mainLetter = getLetterGrade($overallAvg); ?>
                    <td><span class="badge" style="background: <?= $mainLetter['color'] ?>20; color: <?= $mainLetter['color'] ?>;"><?= $mainLetter['letter'] ?></span></td>
                    <td style="color: var(--nt-accent);"><?= $gpa !== null ? number_format($gpa, 2) : '—' ?></td>
                    <td style="font-weight: 700;"><?= number_format($totalWeighted, 2) ?></td>
                    <?php else: ?>
                    <td style="font-weight: 700;"><?= number_format($totalWeighted, 2) ?></td>
                    <?php endif; ?>
                    <td></td>
                </tr>
            </tfoot>
        </table>
    </div>

    <!-- Harf Notu Tablosu (Üniversite) -->
    <?php if ($levelInfo['gpa_type'] === 'gpa4'): ?>
    <div class="section-header" style="margin-top: var(--nt-sp-10);">
        <h2><i class="fa-solid fa-chart-bar"></i> Harf Notu Referans Tablosu</h2>
    </div>
    <div class="nt-table-wrapper" style="animation: fadeInUp 0.5s ease 0.4s; animation-fill-mode: both;">
        <table class="nt-table">
            <thead>
                <tr>
                    <th>Harf</th>
                    <th>Puan Aralığı</th>
                    <th>Katsayı (GPA)</th>
                    <th>Durum</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($letter_grades as $lg): ?>
                <tr>
                    <td><span class="badge" style="background: <?= $lg['color'] ?>20; color: <?= $lg['color'] ?>; font-weight: 700;"><?= $lg['letter'] ?></span></td>
                    <td><?= $lg['min'] ?> — <?= $lg['max'] ?></td>
                    <td style="font-weight: 700; color: <?= $lg['color'] ?>;"><?= number_format($lg['gpa'], 1) ?></td>
                    <td>
                        <?php if ($lg['gpa'] >= 3.0): ?>
                            <span style="color: var(--nt-green);">✅ Başarılı</span>
                        <?php elseif ($lg['gpa'] >= 2.0): ?>
                            <span style="color: var(--nt-accent);">⚠️ Geçer</span>
                        <?php elseif ($lg['gpa'] >= 1.0): ?>
                            <span style="color: var(--nt-orange);">🔶 Koşullu</span>
                        <?php else: ?>
                            <span style="color: var(--nt-red);">❌ Başarısız</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>

    <?php endif; ?>
</div>
