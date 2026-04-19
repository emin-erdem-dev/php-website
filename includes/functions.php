<?php
// includes/functions.php — Öğrenci Not Takip Sistemi Core Functions
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config/data.php';

// ============ SESSİON YÖNETİMİ ============

// Profil kontrol
function hasProfile() {
    return isset($_SESSION['student_profile']);
}

// Profili getir
function getProfile() {
    return $_SESSION['student_profile'] ?? null;
}

// Profili kaydet
function saveProfile($name, $level) {
    $_SESSION['student_profile'] = [
        'name' => $name,
        'level' => $level,
        'created_at' => date('Y-m-d H:i:s')
    ];
}

// ============ DERS YÖNETİMİ ============

// Dersleri getir
function getCourses() {
    return $_SESSION['courses'] ?? [];
}

// Ders ekle
function addCourse($name, $credit) {
    if (!isset($_SESSION['courses'])) {
        $_SESSION['courses'] = [];
    }
    $id = uniqid('course_');
    $_SESSION['courses'][$id] = [
        'id' => $id,
        'name' => trim($name),
        'credit' => (int)$credit,
        'grades' => [
            'midterm' => null,
            'final' => null,
            'homework' => null
        ],
        'created_at' => date('Y-m-d H:i:s')
    ];
    return $id;
}

// Ders sil
function deleteCourse($id) {
    if (isset($_SESSION['courses'][$id])) {
        unset($_SESSION['courses'][$id]);
    }
}

// Not güncelle
function updateGrades($courseId, $midterm, $final, $homework = null) {
    if (isset($_SESSION['courses'][$courseId])) {
        $_SESSION['courses'][$courseId]['grades'] = [
            'midterm' => ($midterm !== '' && $midterm !== null) ? (float)$midterm : null,
            'final' => ($final !== '' && $final !== null) ? (float)$final : null,
            'homework' => ($homework !== '' && $homework !== null) ? (float)$homework : null
        ];
    }
}

// ============ NOT HESAPLAMA ============

// Ders ortalaması hesapla
function calculateCourseAverage($grades) {
    $profile = getProfile();
    if (!$profile) return null;
    
    global $education_levels;
    $level = $education_levels[$profile['level']] ?? null;
    if (!$level) return null;
    
    $weights = $level['grade_weights'];
    $midterm = $grades['midterm'];
    $final = $grades['final'];
    $homework = $grades['homework'];
    
    if ($midterm === null && $final === null) return null;
    
    // Homework varsa ağırlıkları yeniden dağıt
    if ($homework !== null && $homework !== '') {
        $totalWeight = $weights['midterm'] + $weights['final'] + 20;
        $mWeight = $weights['midterm'] / $totalWeight * 100;
        $fWeight = $weights['final'] / $totalWeight * 100;
        $hWeight = 20 / $totalWeight * 100;
        
        $avg = 0;
        if ($midterm !== null) $avg += ($midterm * $mWeight / 100);
        if ($final !== null) $avg += ($final * $fWeight / 100);
        $avg += ($homework * $hWeight / 100);
        
        return round($avg, 2);
    }
    
    $avg = 0;
    if ($midterm !== null && $final !== null) {
        $avg = ($midterm * $weights['midterm'] / 100) + ($final * $weights['final'] / 100);
    } elseif ($midterm !== null) {
        $avg = $midterm;
    } elseif ($final !== null) {
        $avg = $final;
    }
    
    return round($avg, 2);
}

// Harf notu bul
function getLetterGrade($average) {
    global $letter_grades;
    if ($average === null) return ['letter' => '-', 'gpa' => 0, 'color' => '#6b7280'];
    
    foreach ($letter_grades as $grade) {
        if ($average >= $grade['min'] && $average <= $grade['max']) {
            return $grade;
        }
    }
    return ['letter' => 'FF', 'gpa' => 0.0, 'color' => '#dc2626'];
}

// Genel GPA hesapla (kredi ağırlıklı)
function calculateGPA() {
    $profile = getProfile();
    if (!$profile) return null;
    
    global $education_levels;
    $level = $education_levels[$profile['level']] ?? null;
    if (!$level) return null;
    
    $courses = getCourses();
    if (empty($courses)) return null;
    
    $totalCredits = 0;
    $weightedSum = 0;
    $hasGrades = false;
    
    foreach ($courses as $course) {
        $avg = calculateCourseAverage($course['grades']);
        if ($avg === null) continue;
        
        $hasGrades = true;
        $credit = $course['credit'];
        
        if ($level['gpa_type'] === 'gpa4') {
            $letterGrade = getLetterGrade($avg);
            $weightedSum += $letterGrade['gpa'] * $credit;
        } else {
            $weightedSum += $avg * $credit;
        }
        $totalCredits += $credit;
    }
    
    if (!$hasGrades || $totalCredits === 0) return null;
    
    return round($weightedSum / $totalCredits, 2);
}

// Genel 100'lük ortalama hesapla
function calculateOverallAverage() {
    $courses = getCourses();
    if (empty($courses)) return null;
    
    $totalCredits = 0;
    $weightedSum = 0;
    $hasGrades = false;
    
    foreach ($courses as $course) {
        $avg = calculateCourseAverage($course['grades']);
        if ($avg === null) continue;
        
        $hasGrades = true;
        $credit = $course['credit'];
        $weightedSum += $avg * $credit;
        $totalCredits += $credit;
    }
    
    if (!$hasGrades || $totalCredits === 0) return null;
    
    return round($weightedSum / $totalCredits, 2);
}

// ============ İSTATİSTİKLER ============

// Dashboard istatistikleri
function getStats() {
    $courses = getCourses();
    $totalCourses = count($courses);
    $totalCredits = 0;
    $gradedCount = 0;
    $lowGradeCount = 0;
    $highestAvg = 0;
    $lowestAvg = 100;
    $highestCourse = '';
    $lowestCourse = '';
    
    foreach ($courses as $course) {
        $totalCredits += $course['credit'];
        $avg = calculateCourseAverage($course['grades']);
        if ($avg !== null) {
            $gradedCount++;
            if ($avg > $highestAvg) {
                $highestAvg = $avg;
                $highestCourse = $course['name'];
            }
            if ($avg < $lowestAvg) {
                $lowestAvg = $avg;
                $lowestCourse = $course['name'];
            }
            if ($avg < 60) {
                $lowGradeCount++;
            }
        }
    }
    
    return [
        'total_courses' => $totalCourses,
        'total_credits' => $totalCredits,
        'graded_count' => $gradedCount,
        'low_grade_count' => $lowGradeCount,
        'highest_avg' => $highestAvg,
        'lowest_avg' => ($lowestAvg === 100 && $gradedCount === 0) ? 0 : $lowestAvg,
        'highest_course' => $highestCourse,
        'lowest_course' => $lowestCourse,
        'gpa' => calculateGPA(),
        'overall_avg' => calculateOverallAverage()
    ];
}

// Düşük not alan dersleri getir
function getLowGradeCourses($threshold = 60) {
    $courses = getCourses();
    $lowCourses = [];
    
    foreach ($courses as $course) {
        $avg = calculateCourseAverage($course['grades']);
        if ($avg !== null && $avg < $threshold) {
            $course['average'] = $avg;
            $course['video_match'] = matchCourseToVideos($course['name']);
            $lowCourses[] = $course;
        }
    }
    
    // En düşük nottan en yükseğe sırala
    usort($lowCourses, function($a, $b) {
        return $a['average'] <=> $b['average'];
    });
    
    return $lowCourses;
}

// ============ FLASH MESAJLAR ============

function setFlash($type, $message) {
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

function displayFlash() {
    if (isset($_SESSION['flash'])) {
        $type = $_SESSION['flash']['type'];
        $msg = $_SESSION['flash']['message'];
        
        $colors = [
            'success' => '#10b981',
            'error' => '#ef4444',
            'warning' => '#f59e0b',
            'info' => '#3b82f6'
        ];
        $icons = [
            'success' => 'fa-circle-check',
            'error' => 'fa-circle-xmark',
            'warning' => 'fa-triangle-exclamation',
            'info' => 'fa-circle-info'
        ];
        
        $color = $colors[$type] ?? '#3b82f6';
        $icon = $icons[$type] ?? 'fa-circle-info';
        
        echo "<div class='flash-msg' style='--flash-color: {$color}'>";
        echo "<i class='fa-solid {$icon}'></i>";
        echo "<span>{$msg}</span>";
        echo "<button onclick='this.parentElement.remove()' class='flash-close'><i class='fa-solid fa-xmark'></i></button>";
        echo "</div>";
        
        unset($_SESSION['flash']);
    }
}

// ============ GÜVENLİK ============

function sanitize($data) {
    return htmlspecialchars(strip_tags(trim($data)), ENT_QUOTES, 'UTF-8');
}

// ============ YARDIMCI ============

function redirect($url) {
    header("Location: $url");
    exit;
}

// Not durumu rengi
function getGradeColor($average) {
    if ($average === null) return '#6b7280';
    if ($average >= 85) return '#10b981';
    if ($average >= 70) return '#3b82f6';
    if ($average >= 60) return '#f59e0b';
    if ($average >= 50) return '#f97316';
    return '#ef4444';
}

// Not durumu emoji
function getGradeEmoji($average) {
    if ($average === null) return '➖';
    if ($average >= 90) return '🌟';
    if ($average >= 80) return '😊';
    if ($average >= 70) return '👍';
    if ($average >= 60) return '😐';
    if ($average >= 50) return '😟';
    return '😰';
}

// Sıfırlama
function resetAll() {
    unset($_SESSION['student_profile']);
    unset($_SESSION['courses']);
    unset($_SESSION['flash']);
}
?>
