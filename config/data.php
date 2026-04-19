<?php
// config/data.php — Öğrenci Not Takip Sistemi — Sabit Veriler ve Video Veritabanı

// ============ EĞİTİM SEVİYELERİ ============
$education_levels = [
    'ilkokul' => [
        'name' => 'İlkokul',
        'icon' => 'fa-solid fa-school',
        'color' => '#10b981',
        'credit_range' => [1, 3],
        'grade_weights' => ['midterm' => 40, 'final' => 60, 'homework' => 0],
        'gpa_type' => 'simple', // basit 100'lük ortalama
        'description' => '1-4. sınıf temel eğitim'
    ],
    'ortaokul' => [
        'name' => 'Ortaokul',
        'icon' => 'fa-solid fa-book-open-reader',
        'color' => '#3b82f6',
        'credit_range' => [1, 5],
        'grade_weights' => ['midterm' => 40, 'final' => 60, 'homework' => 0],
        'gpa_type' => 'weighted', // ağırlıklı 100'lük
        'description' => '5-8. sınıf ortaöğretim'
    ],
    'lise' => [
        'name' => 'Lise',
        'icon' => 'fa-solid fa-graduation-cap',
        'color' => '#8b5cf6',
        'credit_range' => [1, 5],
        'grade_weights' => ['midterm' => 40, 'final' => 60, 'homework' => 0],
        'gpa_type' => 'weighted',
        'description' => '9-12. sınıf lise eğitimi'
    ],
    'universite' => [
        'name' => 'Üniversite',
        'icon' => 'fa-solid fa-university',
        'color' => '#f59e0b',
        'credit_range' => [1, 8],
        'grade_weights' => ['midterm' => 40, 'final' => 60, 'homework' => 0],
        'gpa_type' => 'gpa4', // 4'lük GPA
        'description' => 'Lisans / Önlisans eğitimi'
    ]
];

// ============ HARF NOTU TABLOSU (Üniversite 4.0 GPA) ============
$letter_grades = [
    ['min' => 90, 'max' => 100, 'letter' => 'AA', 'gpa' => 4.0, 'color' => '#10b981'],
    ['min' => 85, 'max' => 89,  'letter' => 'BA', 'gpa' => 3.5, 'color' => '#22d3ee'],
    ['min' => 80, 'max' => 84,  'letter' => 'BB', 'gpa' => 3.0, 'color' => '#3b82f6'],
    ['min' => 75, 'max' => 79,  'letter' => 'CB', 'gpa' => 2.5, 'color' => '#6366f1'],
    ['min' => 70, 'max' => 74,  'letter' => 'CC', 'gpa' => 2.0, 'color' => '#8b5cf6'],
    ['min' => 65, 'max' => 69,  'letter' => 'DC', 'gpa' => 1.5, 'color' => '#f59e0b'],
    ['min' => 60, 'max' => 64,  'letter' => 'DD', 'gpa' => 1.0, 'color' => '#f97316'],
    ['min' => 50, 'max' => 59,  'letter' => 'FD', 'gpa' => 0.5, 'color' => '#ef4444'],
    ['min' => 0,  'max' => 49,  'letter' => 'FF', 'gpa' => 0.0, 'color' => '#dc2626']
];

// ============ ÖNERİLEN DERS VİDEOLARI ============
$course_videos = [
    'matematik' => [
        'keywords' => ['matematik', 'mat', 'calculus', 'analiz', 'cebir', 'lineer', 'sayısal', 'geometri', 'istatistik'],
        'title' => 'Matematik',
        'icon' => 'fa-solid fa-calculator',
        'color' => '#3b82f6',
        'videos' => [
            ['title' => 'Temel Matematik — Sıfırdan', 'id' => 'pTnEG_WGd2Q', 'channel' => 'Matematik Dünyası', 'level' => 'Başlangıç'],
            ['title' => 'Türev ve İntegral Konu Anlatımı', 'id' => 'WUvTyaaNkzM', 'channel' => 'Matematik Kafası', 'level' => 'Orta'],
            ['title' => 'Lineer Cebir — Matrisler', 'id' => 'fNk_zzaMoSs', 'channel' => '3Blue1Brown', 'level' => 'İleri'],
            ['title' => 'Olasılık ve İstatistik', 'id' => 'uzkc-qNVoOk', 'channel' => 'CrashCourse', 'level' => 'Orta'],
        ]
    ],
    'fizik' => [
        'keywords' => ['fizik', 'fiz', 'mekanik', 'elektrik', 'optik', 'termodinamik', 'kuantum'],
        'title' => 'Fizik',
        'icon' => 'fa-solid fa-atom',
        'color' => '#8b5cf6',
        'videos' => [
            ['title' => 'Fizik Temelleri — Newton Yasaları', 'id' => 'kKKM8Y-u7ds', 'channel' => 'Physics Explained', 'level' => 'Başlangıç'],
            ['title' => 'Elektrik ve Manyetizma', 'id' => 'rtlJoXxlSFE', 'channel' => 'Walter Lewin', 'level' => 'Orta'],
            ['title' => 'Termodinamik — Enerji ve Isı', 'id' => 'brpbLGR9PaI', 'channel' => 'CrashCourse', 'level' => 'Orta'],
            ['title' => 'Optik — Işık ve Dalga', 'id' => 'dkP8NUwB2io', 'channel' => 'Science ABC', 'level' => 'İleri'],
        ]
    ],
    'kimya' => [
        'keywords' => ['kimya', 'kim', 'organik', 'anorganik', 'biyokimya'],
        'title' => 'Kimya',
        'icon' => 'fa-solid fa-flask',
        'color' => '#10b981',
        'videos' => [
            ['title' => 'Genel Kimya — Atomlar ve Bağlar', 'id' => 'bka20Q9TN6M', 'channel' => 'CrashCourse', 'level' => 'Başlangıç'],
            ['title' => 'Periyodik Tablo Anlatımı', 'id' => '0RRVV4Diomg', 'channel' => 'TED-Ed', 'level' => 'Başlangıç'],
            ['title' => 'Organik Kimya Temelleri', 'id' => 'lSJpKQrT5gs', 'channel' => 'Organic Chemistry Tutor', 'level' => 'Orta'],
            ['title' => 'Kimyasal Denge', 'id' => 'DP-vWN1yVrU', 'channel' => 'Khan Academy', 'level' => 'İleri'],
        ]
    ],
    'biyoloji' => [
        'keywords' => ['biyoloji', 'biyo', 'bio', 'genetik', 'hücre', 'anatomi', 'ekoloji'],
        'title' => 'Biyoloji',
        'icon' => 'fa-solid fa-dna',
        'color' => '#22c55e',
        'videos' => [
            ['title' => 'Hücre Biyolojisi — Temel Kavramlar', 'id' => 'URUJD5NEXC8', 'channel' => 'Amoeba Sisters', 'level' => 'Başlangıç'],
            ['title' => 'DNA ve Genetik', 'id' => 'zwibgNGe4aQ', 'channel' => 'CrashCourse', 'level' => 'Orta'],
            ['title' => 'Evrim Teorisi', 'id' => 'GcjgWov7mTM', 'channel' => 'Kurzgesagt', 'level' => 'Orta'],
            ['title' => 'Ekoloji ve Ekosistemler', 'id' => 'izRvPaAWgyw', 'channel' => 'National Geographic', 'level' => 'İleri'],
        ]
    ],
    'turkce' => [
        'keywords' => ['türkçe', 'turkce', 'edebiyat', 'dil', 'dilbilgisi', 'kompozisyon', 'yazım'],
        'title' => 'Türkçe / Edebiyat',
        'icon' => 'fa-solid fa-book',
        'color' => '#ef4444',
        'videos' => [
            ['title' => 'Türkçe Dil Bilgisi — Temel Kurallar', 'id' => 'BLr8RnlfnWo', 'channel' => 'Türkçe Dersi', 'level' => 'Başlangıç'],
            ['title' => 'Paragraf Soruları Çözümü', 'id' => 'mFQEHHdCxp0', 'channel' => 'Tonguç Akademi', 'level' => 'Orta'],
            ['title' => 'Edebiyat Akımları', 'id' => 'Xj7vIlmkJnY', 'channel' => 'Edebiyat TV', 'level' => 'İleri'],
            ['title' => 'Sözcükte Anlam', 'id' => 'jDuYZhMZsBo', 'channel' => 'Hocalara Geldik', 'level' => 'Orta'],
        ]
    ],
    'tarih' => [
        'keywords' => ['tarih', 'history', 'inkılap', 'osmanlı', 'cumhuriyet', 'dünya tarihi'],
        'title' => 'Tarih',
        'icon' => 'fa-solid fa-landmark',
        'color' => '#d97706',
        'videos' => [
            ['title' => 'Osmanlı İmparatorluğu Tarihi', 'id' => 'yHTeC_OFDKM', 'channel' => 'Tarih Dersleri', 'level' => 'Başlangıç'],
            ['title' => 'Atatürk ve Cumhuriyet', 'id' => 'vSuGCk7bDG4', 'channel' => 'Bilgi Kaynağı', 'level' => 'Orta'],
            ['title' => 'I. Dünya Savaşı Özet', 'id' => 'dHSQAEam2yc', 'channel' => 'Oversimplified', 'level' => 'Orta'],
            ['title' => 'Uygarlık Tarihi', 'id' => 'xuCn8ux2gbs', 'channel' => 'CrashCourse', 'level' => 'İleri'],
        ]
    ],
    'ingilizce' => [
        'keywords' => ['ingilizce', 'english', 'ing', 'grammar', 'vocabulary', 'yabancı dil'],
        'title' => 'İngilizce',
        'icon' => 'fa-solid fa-language',
        'color' => '#06b6d4',
        'videos' => [
            ['title' => 'İngilizce Gramerin Temelleri', 'id' => 'HZaBHnXC3Fg', 'channel' => 'English with Lucy', 'level' => 'Başlangıç'],
            ['title' => 'Tenses (Zamanlar) Konu Anlatımı', 'id' => 'fAUPJtTFILw', 'channel' => 'JamesESL', 'level' => 'Orta'],
            ['title' => '1000 Temel İngilizce Kelime', 'id' => 'dQw4w9WgXcQ', 'channel' => 'Learn English', 'level' => 'Başlangıç'],
            ['title' => 'İngilizce Konuşma Pratiği', 'id' => 'juKd26qkNAw', 'channel' => 'English Addict', 'level' => 'İleri'],
        ]
    ],
    'programlama' => [
        'keywords' => ['programlama', 'yazılım', 'kodlama', 'python', 'java', 'javascript', 'c++', 'php', 'web', 'bilgisayar'],
        'title' => 'Programlama',
        'icon' => 'fa-solid fa-code',
        'color' => '#a855f7',
        'videos' => [
            ['title' => 'Python — Sıfırdan İleri Seviye', 'id' => 'rfscVS0vtbw', 'channel' => 'freeCodeCamp', 'level' => 'Başlangıç'],
            ['title' => 'JavaScript Temelleri', 'id' => 'PkZNo7MFNFg', 'channel' => 'freeCodeCamp', 'level' => 'Başlangıç'],
            ['title' => 'HTML & CSS — Web Geliştirme', 'id' => 'qz0aGYrrlhU', 'channel' => 'Mosh', 'level' => 'Orta'],
            ['title' => 'PHP ile Web Uygulaması', 'id' => 'OK_JCtrrv-c', 'channel' => 'Traversy Media', 'level' => 'İleri'],
        ]
    ],
    'cografya' => [
        'keywords' => ['coğrafya', 'cografya', 'cog', 'harita', 'iklim', 'nüfus'],
        'title' => 'Coğrafya',
        'icon' => 'fa-solid fa-earth-americas',
        'color' => '#0ea5e9',
        'videos' => [
            ['title' => 'Fiziki Coğrafya Temelleri', 'id' => 'SN1Cg9sBniU', 'channel' => 'Coğrafya Mekanı', 'level' => 'Başlangıç'],
            ['title' => 'İklim Bilgisi ve Rüzgarlar', 'id' => 'FPcnzxPINdI', 'channel' => 'Tonguc', 'level' => 'Orta'],
            ['title' => 'Haritalarda Projeksiyon', 'id' => 'kIID5FDi2JQ', 'channel' => 'Vox', 'level' => 'İleri'],
        ]
    ],
    'felsefe' => [
        'keywords' => ['felsefe', 'mantık', 'etik', 'epistemoloji', 'ontoloji'],
        'title' => 'Felsefe',
        'icon' => 'fa-solid fa-brain',
        'color' => '#ec4899',
        'videos' => [
            ['title' => 'Felsefenin Temelleri', 'id' => '1A_CAkYt3GY', 'channel' => 'CrashCourse', 'level' => 'Başlangıç'],
            ['title' => 'Sokrates ve Antik Yunan', 'id' => 'bJUuOnsRcvc', 'channel' => 'TED-Ed', 'level' => 'Orta'],
            ['title' => 'Varoluşçuluk Nedir?', 'id' => 'YaDvRdLMkHs', 'channel' => 'Kurzgesagt', 'level' => 'İleri'],
        ]
    ]
];

// Ders adından video kategorisi eşleştirme (fuzzy matching)
function matchCourseToVideos($courseName) {
    global $course_videos;
    $courseLower = mb_strtolower(trim($courseName), 'UTF-8');
    
    foreach ($course_videos as $key => $category) {
        foreach ($category['keywords'] as $keyword) {
            $keyLower = mb_strtolower($keyword, 'UTF-8');
            if (mb_strpos($courseLower, $keyLower) !== false || mb_strpos($keyLower, $courseLower) !== false) {
                return $key;
            }
        }
    }
    return null;
}
?>
