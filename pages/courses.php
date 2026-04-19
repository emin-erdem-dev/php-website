<?php
// pages/courses.php — Ders Yönetimi Sayfası
$profile = getProfile();
$courses = getCourses();

global $education_levels;
$levelInfo = $education_levels[$profile['level']] ?? null;
$maxCredit = $levelInfo ? $levelInfo['credit_range'][1] : 5;
$minCredit = $levelInfo ? $levelInfo['credit_range'][0] : 1;
?>

<div class="page-hero">
    <div class="container">
        <h1 style="animation: fadeInUp 0.5s ease;">
            <i class="fa-solid fa-book" style="color: var(--nt-primary);"></i> Derslerim
        </h1>
        <p style="animation: fadeInUp 0.5s ease 0.1s; animation-fill-mode: both;">
            Derslerini ekle, düzenle ve yönet. Her dersin adını ve kredi değerini sen belirlersin.
        </p>
    </div>
</div>

<div class="container section" style="padding-top: 0;">
    <!-- Ders Ekleme Formu -->
    <div class="card" style="margin-bottom: var(--nt-sp-8); animation: fadeInUp 0.5s ease 0.2s; animation-fill-mode: both;">
        <h3 style="margin-bottom: var(--nt-sp-5); display: flex; align-items: center; gap: 10px;">
            <i class="fa-solid fa-plus-circle" style="color: var(--nt-cyan);"></i> Yeni Ders Ekle
        </h3>
        <form method="POST" action="index.php?page=courses">
            <input type="hidden" name="action" value="add_course">
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Ders Adı</label>
                    <input type="text" name="course_name" class="form-input" placeholder="Örn: Matematik, Fizik, İngilizce..." required autocomplete="off" maxlength="80">
                    <div class="form-hint">Dersin adını kendin yaz (herhangi bir ders olabilir)</div>
                </div>
                <div class="form-group">
                    <label class="form-label">Kredi</label>
                    <select name="course_credit" class="form-select" required>
                        <?php for ($i = $minCredit; $i <= $maxCredit; $i++): ?>
                        <option value="<?= $i ?>"><?= $i ?> Kredi</option>
                        <?php endfor; ?>
                    </select>
                    <div class="form-hint">
                        <?= $levelInfo['name'] ?> seviyesi: <?= $minCredit ?> - <?= $maxCredit ?> kredi
                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">
                <i class="fa-solid fa-plus"></i> Ders Ekle
            </button>
        </form>
    </div>

    <!-- Ders Listesi -->
    <?php if (!empty($courses)): ?>
    <div class="section-header">
        <h2><i class="fa-solid fa-list"></i> Kayıtlı Dersler <span class="badge badge-purple" style="font-size: var(--nt-fs-xs);"><?= count($courses) ?> ders</span></h2>
    </div>

    <div class="course-grid stagger-in">
        <?php foreach ($courses as $course):
            $avg = calculateCourseAverage($course['grades']);
            $color = getGradeColor($avg);
            $letter = getLetterGrade($avg);
            $videoMatch = matchCourseToVideos($course['name']);
        ?>
        <div class="course-card <?= ($avg !== null && $avg < 60) ? 'low-grade-pulse' : '' ?>" style="--course-color: <?= $color ?>;">
            <div class="course-card-header">
                <div>
                    <div class="course-card-name"><?= htmlspecialchars($course['name']) ?></div>
                    <div class="course-card-credit">
                        <span class="badge badge-purple"><i class="fa-solid fa-coins"></i> <?= $course['credit'] ?> Kredi</span>
                        <?php if ($videoMatch): ?>
                        <a href="index.php?page=videos&course=<?= urlencode($videoMatch) ?>" class="badge badge-info" style="text-decoration: none;">
                            <i class="fa-solid fa-video"></i> Video Mevcut
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
                <span style="font-size: 1.5rem;"><?= getGradeEmoji($avg) ?></span>
            </div>
            
            <div class="course-card-grades">
                <div class="course-card-grade-item">
                    <div class="course-card-grade-label">Vize</div>
                    <div class="course-card-grade-value"><?= $course['grades']['midterm'] !== null ? $course['grades']['midterm'] : '—' ?></div>
                </div>
                <div class="course-card-grade-item">
                    <div class="course-card-grade-label">Final</div>
                    <div class="course-card-grade-value"><?= $course['grades']['final'] !== null ? $course['grades']['final'] : '—' ?></div>
                </div>
                <div class="course-card-grade-item">
                    <div class="course-card-grade-label">Ödev</div>
                    <div class="course-card-grade-value"><?= $course['grades']['homework'] !== null ? $course['grades']['homework'] : '—' ?></div>
                </div>
            </div>

            <!-- Ortalama Progress Bar -->
            <?php if ($avg !== null): ?>
            <div style="margin-bottom: var(--nt-sp-3);">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 4px;">
                    <span style="font-size: var(--nt-fs-xs); color: var(--nt-text-dim);">Ortalama</span>
                    <span style="font-size: var(--nt-fs-sm); font-weight: 700; color: <?= $color ?>;"><?= $avg ?>
                        <?php if ($levelInfo && $levelInfo['gpa_type'] === 'gpa4'): ?>
                        <span style="color: <?= $letter['color'] ?>; margin-left: 4px;">(<?= $letter['letter'] ?>)</span>
                        <?php endif; ?>
                    </span>
                </div>
                <div class="progress-bar">
                    <div class="progress-fill" style="width: <?= min($avg, 100) ?>%; --progress-color: <?= $color ?>;"></div>
                </div>
            </div>
            <?php endif; ?>

            <div class="course-card-footer">
                <a href="index.php?page=grades&edit=<?= $course['id'] ?>" class="btn btn-secondary btn-sm">
                    <i class="fa-solid fa-pen"></i> Not Gir
                </a>
                <form method="POST" action="index.php?page=courses" style="display:inline;" onsubmit="return confirm('Bu dersi silmek istediğine emin misin?')">
                    <input type="hidden" name="action" value="delete_course">
                    <input type="hidden" name="course_id" value="<?= $course['id'] ?>">
                    <button type="submit" class="btn btn-danger btn-sm btn-icon" title="Sil">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                </form>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <?php else: ?>
    <div class="empty-state">
        <div class="empty-state-icon"><i class="fa-solid fa-book-open"></i></div>
        <h3>Henüz ders eklenmedi</h3>
        <p>Yukarıdaki formu kullanarak derslerini ekle. Her dersin adını ve kredi değerini kendin belirleyebilirsin.</p>
    </div>
    <?php endif; ?>
</div>
