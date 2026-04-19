<?php
// index.php — Öğrenci Not Takip Sistemi Router
require_once 'config/data.php';
require_once 'includes/functions.php';

$page = isset($_GET['page']) ? sanitize($_GET['page']) : 'home';

// ============ FORM İŞLEMLERİ (POST) ============
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    switch ($action) {
        case 'setup_profile':
            $name = sanitize($_POST['student_name'] ?? '');
            $level = sanitize($_POST['education_level'] ?? '');
            if ($name && $level && isset($education_levels[$level])) {
                saveProfile($name, $level);
                setFlash('success', "Hoş geldin {$name}! Profilin oluşturuldu. 🎉");
                redirect('index.php?page=home');
            } else {
                setFlash('error', 'Lütfen adınızı ve eğitim seviyesini seçin.');
                redirect('index.php?page=setup');
            }
            break;
            
        case 'add_course':
            $name = sanitize($_POST['course_name'] ?? '');
            $credit = (int)($_POST['course_credit'] ?? 1);
            $profile = getProfile();
            $level = $education_levels[$profile['level']] ?? null;
            
            if ($name && $credit >= 1 && $level && $credit <= $level['credit_range'][1]) {
                addCourse($name, $credit);
                setFlash('success', "\"{$name}\" dersi eklendi! 📚");
            } else {
                setFlash('error', 'Geçersiz ders bilgileri.');
            }
            redirect('index.php?page=courses');
            break;
            
        case 'delete_course':
            $courseId = $_POST['course_id'] ?? '';
            if ($courseId) {
                deleteCourse($courseId);
                setFlash('warning', 'Ders silindi.');
            }
            redirect('index.php?page=courses');
            break;
            
        case 'save_grades':
            $courseId = $_POST['course_id'] ?? '';
            $midterm = $_POST['midterm'] ?? '';
            $final = $_POST['final_grade'] ?? '';
            $homework = $_POST['homework'] ?? '';
            
            if ($courseId) {
                updateGrades($courseId, $midterm, $final, $homework);
                setFlash('success', 'Notlar kaydedildi! ✅');
            }
            redirect('index.php?page=grades');
            break;
            
        case 'reset_all':
            resetAll();
            setFlash('info', 'Tüm veriler sıfırlandı.');
            redirect('index.php?page=setup');
            break;
    }
}

// ============ RESET SAYFASI ============
if ($page === 'reset') {
    // Confirmation page
    $page = 'reset';
}

// ============ PROFİL KONTROLÜ ============
if (!hasProfile() && $page !== 'setup') {
    redirect('index.php?page=setup');
}

// ============ SAYFA YÖNLENDİRME ============
$allowed_pages = [
    'home'         => 'pages/home.php',
    'courses'      => 'pages/courses.php',
    'grades'       => 'pages/grades.php',
    'average'      => 'pages/average.php',
    'videos'       => 'pages/videos.php',
    'goals'        => 'pages/goals.php',
    'achievements' => 'pages/achievements.php',
    'setup'        => 'pages/setup.php',
    'reset'        => 'pages/reset.php'
];

if ($page === 'setup') {
    // Setup sayfası header/footer olmadan gösterilir
    require_once $allowed_pages['setup'];
} elseif (array_key_exists($page, $allowed_pages)) {
    require_once 'includes/header.php';
    if (file_exists($allowed_pages[$page])) {
        require_once $allowed_pages[$page];
    } else {
        echo "<div class='container section'><div class='empty-state'><div class='empty-state-icon'><i class='fa-solid fa-hammer'></i></div><h3>Yapım Aşamasında</h3><p>Bu sayfa henüz hazır değil.</p></div></div>";
    }
    require_once 'includes/footer.php';
} else {
    require_once 'includes/header.php';
    echo "<div class='container section'><div class='empty-state'><div class='empty-state-icon'><i class='fa-solid fa-triangle-exclamation'></i></div><h3>404 — Sayfa Bulunamadı</h3><p>Aradığınız sayfa mevcut değil.</p><a href='index.php?page=home' class='btn btn-primary'><i class='fa-solid fa-home'></i> Ana Sayfa</a></div></div>";
    require_once 'includes/footer.php';
}
?>
