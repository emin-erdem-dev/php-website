<?php
// pages/videos.php — Ders Videoları ve Önerileri
$profile = getProfile();
$courses = getCourses();
$lowCourses = getLowGradeCourses(70); // 70 altı düşük kabul ediyoruz

global $course_videos;

// Belirli bir kategori seçildiyse filtrele
$selectedCategory = $_GET['course'] ?? null;

// Ders adlarından eşleşen kategorileri bul
$matchedCategories = [];
foreach ($courses as $course) {
    $match = matchCourseToVideos($course['name']);
    if ($match && !isset($matchedCategories[$match])) {
        $avg = calculateCourseAverage($course['grades']);
        $matchedCategories[$match] = [
            'course_name' => $course['name'],
            'average' => $avg,
            'is_low' => ($avg !== null && $avg < 70)
        ];
    }
}
?>

<div class="page-hero">
    <div class="container">
        <h1 style="animation: fadeInUp 0.5s ease;">
            <i class="fa-solid fa-video" style="color: var(--nt-red);"></i> Ders Videoları
        </h1>
        <p style="animation: fadeInUp 0.5s ease 0.1s; animation-fill-mode: both;">
            Düşük notalanrın var mı? Önerilen eğitim videolarını izleyerek konuları kavra.
            Tüm video kategorilerini de keşfet.
        </p>
    </div>
</div>

<div class="container section" style="padding-top: 0;">
    
    <!-- Düşük Not Uyarısı -->
    <?php if (!empty($lowCourses)): ?>
    <div class="alert-box alert-box-warning" style="animation: fadeInUp 0.4s ease;">
        <i class="fa-solid fa-triangle-exclamation" style="font-size: 1.3rem;"></i>
        <div>
            <strong>📢 Düşük not alan dersler tespit edildi!</strong>
            <div style="margin-top: 4px; font-size: var(--nt-fs-xs); color: var(--nt-text-soft);">
                <?php foreach ($lowCourses as $lc): ?>
                    <span style="margin-right: 12px;">
                        <?= htmlspecialchars($lc['name']) ?>: <strong style="color: <?= getGradeColor($lc['average']) ?>;"><?= $lc['average'] ?></strong>
                        <?php if ($lc['video_match']): ?>
                        — <a href="index.php?page=videos&course=<?= urlencode($lc['video_match']) ?>" style="color: var(--nt-cyan);">Video İzle →</a>
                        <?php endif; ?>
                    </span>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Kategori/Sekmeler -->
    <div class="tabs" style="animation: fadeInUp 0.4s ease 0.1s; animation-fill-mode: both;">
        <a href="index.php?page=videos" class="tab-btn <?= !$selectedCategory ? 'active' : '' ?>">
            <i class="fa-solid fa-globe"></i> Tümü
        </a>
        <?php foreach ($course_videos as $catKey => $cat): ?>
        <a href="index.php?page=videos&course=<?= urlencode($catKey) ?>" class="tab-btn <?= $selectedCategory === $catKey ? 'active' : '' ?>"
           style="<?= isset($matchedCategories[$catKey]) && $matchedCategories[$catKey]['is_low'] ? 'border-color: rgba(239,68,68,0.3); color: var(--nt-red);' : '' ?>">
            <i class="<?= $cat['icon'] ?>" style="color: <?= $cat['color'] ?>;"></i>
            <?= $cat['title'] ?>
            <?php if (isset($matchedCategories[$catKey]) && $matchedCategories[$catKey]['is_low']): ?>
                <span style="font-size: 0.7rem;">⚠️</span>
            <?php endif; ?>
        </a>
        <?php endforeach; ?>
    </div>

    <!-- Video İçeriği -->
    <?php
    $categoriesToShow = [];
    if ($selectedCategory && isset($course_videos[$selectedCategory])) {
        $categoriesToShow[$selectedCategory] = $course_videos[$selectedCategory];
    } else {
        // Önce düşük notlu derslere ait kategorileri göster
        foreach ($matchedCategories as $catKey => $info) {
            if ($info['is_low'] && isset($course_videos[$catKey])) {
                $categoriesToShow[$catKey] = $course_videos[$catKey];
            }
        }
        // Sonra diğer tüm kategoriler
        foreach ($course_videos as $catKey => $cat) {
            if (!isset($categoriesToShow[$catKey])) {
                $categoriesToShow[$catKey] = $cat;
            }
        }
    }
    ?>

    <?php foreach ($categoriesToShow as $catKey => $category): ?>
    <div style="margin-bottom: var(--nt-sp-10); animation: fadeInUp 0.5s ease;">
        <div class="section-header">
            <h2 style="display: flex; align-items: center; gap: 10px;">
                <span style="width: 36px; height: 36px; border-radius: var(--nt-radius-md); background: <?= $category['color'] ?>20; color: <?= $category['color'] ?>; display: flex; align-items: center; justify-content: center; font-size: 1rem;">
                    <i class="<?= $category['icon'] ?>"></i>
                </span>
                <?= $category['title'] ?>
                <?php if (isset($matchedCategories[$catKey])): ?>
                    <?php if ($matchedCategories[$catKey]['is_low']): ?>
                        <span class="badge badge-danger">
                            <i class="fa-solid fa-arrow-down"></i> Düşük Not: <?= $matchedCategories[$catKey]['average'] ?>
                        </span>
                    <?php else: ?>
                        <span class="badge badge-success">
                            <i class="fa-solid fa-check"></i> Dersinizle eşleşiyor
                        </span>
                    <?php endif; ?>
                <?php endif; ?>
            </h2>
        </div>

        <div class="video-grid stagger-in">
            <?php foreach ($category['videos'] as $video): ?>
            <div class="video-card" id="video-<?= $video['id'] ?>">
                <div class="video-card-thumb">
                    <img src="https://img.youtube.com/vi/<?= $video['id'] ?>/mqdefault.jpg" 
                         alt="<?= htmlspecialchars($video['title']) ?>"
                         loading="lazy">
                    <button class="video-card-play" onclick="openVideo('<?= $video['id'] ?>', '<?= htmlspecialchars(addslashes($video['title'])) ?>')">
                        <i class="fa-solid fa-play"></i>
                    </button>
                </div>
                <div class="video-card-body">
                    <div class="video-card-title"><?= htmlspecialchars($video['title']) ?></div>
                    <div class="video-card-meta">
                        <span><i class="fa-brands fa-youtube" style="color: #ff0000;"></i> <?= htmlspecialchars($video['channel']) ?></span>
                        <span class="badge" style="background: <?= $category['color'] ?>20; color: <?= $category['color'] ?>; font-size: 0.6rem;">
                            <?= $video['level'] ?>
                        </span>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endforeach; ?>

    <!-- Önerilen Kategorileri Göster (eşleşmeyenler) -->
    <?php if ($selectedCategory === null && empty($matchedCategories)): ?>
    <div class="alert-box alert-box-info" style="margin-top: var(--nt-sp-4);">
        <i class="fa-solid fa-info-circle"></i>
        <div>
            <strong>İpucu:</strong> Ders isimlerini tanınabilir bir şekilde yazarsan (Örn: "Matematik", "Fizik", "İngilizce"), 
            sistem otomatik olarak uygun videoları önerir!
        </div>
    </div>
    <?php endif; ?>
</div>

<!-- Video Modal -->
<div id="videoModal" class="video-modal-overlay" style="display: none;">
    <div class="video-modal">
        <div class="video-modal-header">
            <h3 id="videoModalTitle">Video</h3>
            <button class="btn-ghost" onclick="closeVideo()" style="padding: 4px 8px;">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <div class="video-modal-body">
            <iframe id="videoIframe" src="" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
        </div>
    </div>
</div>

<script>
function openVideo(videoId, title) {
    const modal = document.getElementById('videoModal');
    const iframe = document.getElementById('videoIframe');
    const titleEl = document.getElementById('videoModalTitle');
    
    iframe.src = 'https://www.youtube.com/embed/' + videoId + '?autoplay=1';
    titleEl.textContent = title;
    modal.style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function closeVideo() {
    const modal = document.getElementById('videoModal');
    const iframe = document.getElementById('videoIframe');
    
    iframe.src = '';
    modal.style.display = 'none';
    document.body.style.overflow = '';
}

// ESC tuşu ile kapat
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeVideo();
});

// Modal dışına tıklama
document.getElementById('videoModal').addEventListener('click', function(e) {
    if (e.target === this) closeVideo();
});
</script>
