# Öğrenci Not Takip Sistemi

Bu proje, öğrencilerin derslerini, kredilerini, notlarını ve ortalamalarını pratik bir şekilde takip edebileceği, şık tasarımlı bir PHP uygulamasıdır.

## 🚀 Proje Hakkında Neler Yaptık?

- **Analiz ve İnceleme**: Projenin yapısı incelendi. Sistemin, veritabanı gerektirmeyen, verileri güvenli bir şekilde `$_SESSION` (oturum) üzerinde tutan pratik bir mimariye sahip olduğu görüldü.
- **Tasarım Kalitesi**: Mevcut `variables.css` ve `advanced-animations.css` dosyalarındaki modern tasarım ögeleri ve renk paletleri korundu. Kullanıcı arayüzü son derece akıcıdır.
- **Çalıştırma Ortamı**: Sistemin doğrudan çalıştırılabilmesi için (sunucuda veya lokal bilgisayarda PHP yüklü olmasa dahi), projeye özel "Static PHP Binary" (Bağımsız PHP çalıştırıcısı) entegre edildi.
- **Gereksiz Dosya Temizliği**: Test süreçleri sırasında oluşturulan geçici SPA dönüşüm dosyaları (`app-logic.js`, `index.html`) ve indirilen arşiv (`.tar.gz`) dosyaları tamamen temizlendi. Yalnızca projenin saf ve çalışan hali bırakıldı.

## 🛠️ Nasıl Çalıştırılır?

Projenin içinde bir veritabanı yapılandırmasına ihtiyaç yoktur. Doğrudan PHP'nin yerleşik sunucusu ile çalıştırılabilir.

### Yöntem 1: Sisteminizde PHP Yüklüyse
Terminalinizi projenin bulunduğu dizinde açın ve aşağıdaki komutu çalıştırın:
```bash
php -S localhost:8080
```
Ardından tarayıcınızda `http://localhost:8080` adresine gidin.

### Yöntem 2: Static PHP Binary ile (Proje Dizinindeki PHP ile)
Sisteminizde PHP kurulu değilse, projeyle birlikte indirdiğimiz statik PHP dosyasını kullanarak çalıştırabilirsiniz (Linux için):
```bash
./php -S localhost:8080
```

## 📂 Proje Yapısı

- `index.php`: Ana yönlendirici (Router). Gelen istekleri ilgili sayfalara dağıtır.
- `config/data.php`: Eğitim seviyeleri, harf notu aralıkları ve önerilen YouTube ders videolarının veritabanı.
- `includes/functions.php`: Uygulamanın beyni. Not hesaplamaları, GPA hesabı ve Session (oturum) yönetimi fonksiyonları burada yer alır.
- `pages/`: Uygulamanın tüm sayfaları (`home.php`, `courses.php`, `grades.php`, `setup.php` vb.).
- `assets/`: Stil (`css`) dosyaları ve (eğer varsa) JavaScript / Medya dosyaları.

## 🌟 Özellikler

- İlkokul, Ortaokul, Lise ve Üniversite (4'lük GPA) sistemlerine göre otomatik not hesaplama.
- Başarı durumuna göre dinamik olarak ders videosu tavsiyeleri verme.
- Şık animasyonlar ve modern arayüz tasarımı.
