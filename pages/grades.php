<?php
// pages/grades.php — Not Girişi Sayfası
$profile = getProfile();
$courses = getCourses();

global $education_levels;
$levelInfo = $education_levels[$profile['level']] ?? null;

$editCourseId = $_GET['edit'] ?? null;
?>

<div class="page-hero">
    <div class="container">
        <h1 style="animation: fadeInUp 0.5s ease;">
            <i class="fa-solid fa-pen-to-square" style="color: var(--nt-green);"></i> Not Girişi
        </h1>
        <p style="animation: fadeInUp 0.5s ease 0.1s; animation-fill-mode: both;">
            Derslerine vize, final ve ödev notlarını gir. Ortalama otomatik hesaplanır.
            <span class="badge badge-info" style="margin-left: 8px;">
                Vize %40 — Final %60
            </span>
        </p>
    </div>
</div>

<div class="container section" style="padding-top: 0;">
    <?php if (empty($courses)): ?>
    <div class="empty-state">
        <div class="empty-state-icon"><i class="fa-solid fa-clipboard-list"></i></div>
        <h3>Önce ders ekle!</h3>
        <p>Not girebilmen için en az bir ders eklemiş olman gerekiyor.</p>
        <a href="index.php?page=courses" class="btn btn-primary">
            <i class="fa-solid fa-plus"></i> Ders Ekle
        </a>
    </div>

    <?php else: ?>

    <!-- Not girişi kartları — her ders için -->
    <div class="course-grid stagger-in">
        <?php foreach ($courses as $course):
            $avg = calculateCourseAverage($course['grades']);
            $color = getGradeColor($avg);
            $letter = getLetterGrade($avg);
            $isEditing = ($editCourseId === $course['id']);
        ?>
        <div class="card <?= $isEditing ? '' : '' ?>" id="course-<?= $course['id'] ?>" style="<?= $isEditing ? 'border-color: var(--nt-primary); box-shadow: var(--nt-shadow-glow);' : '' ?>">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: var(--nt-sp-5);">
                <div>
                    <h4 style="margin:0; display: flex; align-items: center; gap: 8px;">
                        <?= htmlspecialchars($course['name']) ?>
                        <span style="font-size: 1.2rem;"><?= getGradeEmoji($avg) ?></span>
                    </h4>
                    <span class="badge badge-purple" style="margin-top: 4px;"><i class="fa-solid fa-coins"></i> <?= $course['credit'] ?> Kredi</span>
                </div>
                <?php if ($avg !== null): ?>
                <div class="grade-circle" style="--grade-color: <?= $color ?>; --grade-color-soft: <?= $color ?>15;">
                    <?= $avg ?>
                </div>
                <?php endif; ?>
            </div>

            <form method="POST" action="index.php?page=grades">
                <input type="hidden" name="action" value="save_grades">
                <input type="hidden" name="course_id" value="<?= $course['id'] ?>">
                
                <div class="form-row-3">
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fa-solid fa-file-lines" style="color: var(--nt-blue); margin-right: 4px;"></i> Vize
                        </label>
                        <input type="number" name="midterm" class="form-input" 
                               value="<?= $course['grades']['midterm'] !== null ? $course['grades']['midterm'] : '' ?>"
                               placeholder="0-100" min="0" max="100" step="0.1">
                        <div class="form-hint">Ağırlık: %40</div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fa-solid fa-file-check" style="color: var(--nt-green); margin-right: 4px;"></i> Final
                        </label>
                        <input type="number" name="final_grade" class="form-input"
                               value="<?= $course['grades']['final'] !== null ? $course['grades']['final'] : '' ?>"
                               placeholder="0-100" min="0" max="100" step="0.1">
                        <div class="form-hint">Ağırlık: %60</div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fa-solid fa-clipboard" style="color: var(--nt-accent); margin-right: 4px;"></i> Ödev
                        </label>
                        <input type="number" name="homework" class="form-input"
                               value="<?= $course['grades']['homework'] !== null ? $course['grades']['homework'] : '' ?>"
                               placeholder="Opsiyonel" min="0" max="100" step="0.1">
                        <div class="form-hint">Opsiyonel</div>
                    </div>
                </div>

                <?php if ($avg !== null): ?>
                <div style="display: flex; align-items: center; gap: var(--nt-sp-4); margin-bottom: var(--nt-sp-4); padding: var(--nt-sp-3) var(--nt-sp-4); background: var(--nt-surface-2); border-radius: var(--nt-radius-md);">
                    <div class="progress-bar" style="flex: 1;">
                        <div class="progress-fill" style="width: <?= min($avg, 100) ?>%; --progress-color: <?= $color ?>;"></div>
                    </div>
                    <span style="font-weight: 700; color: <?= $color ?>; font-size: var(--nt-fs-sm); white-space: nowrap;">
                        <?= $avg ?>
                        <?php if ($levelInfo && $levelInfo['gpa_type'] === 'gpa4'): ?>
                         / <span style="color: <?= $letter['color'] ?>;"><?= $letter['letter'] ?></span>
                        <?php endif; ?>
                    </span>
                </div>
                <?php endif; ?>

                <button type="submit" class="btn btn-success btn-sm">
                    <i class="fa-solid fa-save"></i> Kaydet
                </button>
            </form>
        </div>
        <?php endforeach; ?>
    </div>

    <?php endif; ?>
</div>

<?php if ($editCourseId): ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const el = document.getElementById('course-<?= $editCourseId ?>');
    if (el) { el.scrollIntoView({ behavior: 'smooth', block: 'center' }); }
});
</script>
<?php endif; ?>
