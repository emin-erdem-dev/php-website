<?php
// pages/reset.php — Sıfırlama Onay Sayfası
?>

<div class="container section">
    <div class="card" style="max-width: 500px; margin: var(--nt-sp-16) auto; text-align: center; animation: fadeInUp 0.5s ease;">
        <div style="font-size: 3rem; margin-bottom: var(--nt-sp-6);">⚠️</div>
        <h2>Sıfırlamak istediğine emin misin?</h2>
        <p style="margin-bottom: var(--nt-sp-6);">
            Bu işlem tüm derslerini, notlarını ve profil bilgilerini silecek. 
            <strong style="color: var(--nt-red);">Bu işlem geri alınamaz!</strong>
        </p>
        
        <div style="display: flex; gap: var(--nt-sp-4); justify-content: center;">
            <a href="index.php?page=home" class="btn btn-secondary">
                <i class="fa-solid fa-arrow-left"></i> Vazgeç
            </a>
            <form method="POST" action="index.php">
                <input type="hidden" name="action" value="reset_all">
                <button type="submit" class="btn btn-danger">
                    <i class="fa-solid fa-trash"></i> Evet, Sıfırla
                </button>
            </form>
        </div>
    </div>
</div>
