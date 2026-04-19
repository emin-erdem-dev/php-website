<?php
// pages/goals.php — Hedefler ve Pomodoro Zamanlayıcı
$profile = getProfile();
?>

<div class="flow-bg"></div> <!-- Animated generic background -->

<div class="page-hero">
    <div class="container" style="text-align: center;">
        <h1 style="animation: fadeInUp 0.5s ease;">
            <i class="fa-solid fa-clock" style="color: var(--nt-cyan);"></i> Pomodoro & Odaklanma
        </h1>
        <p style="margin: 0 auto; animation: fadeInUp 0.5s ease 0.1s; animation-fill-mode: both;">
            Ders çalışırken zamanı yönet, hedefine ulaş!
        </p>
    </div>
</div>

<div class="container section" style="padding-top: 0; display: flex; flex-direction: column; align-items: center;">

    <!-- POMODORO TIMER -->
    <div class="pomodoro-container stagger-in">
        <div class="pomodoro-circle" id="timerRing">
            <svg class="pomodoro-svg" viewBox="0 0 250 250">
                <circle class="pomodoro-bg" cx="125" cy="125" r="110"></circle>
                <circle class="pomodoro-progress" cx="125" cy="125" r="110" id="progressCircle"></circle>
            </svg>
            <div class="pomodoro-time">
                <h2 id="timeDisplay">25:00</h2>
                <span id="sessionLabel">Odaklanma Modu</span>
            </div>
        </div>
    </div>

    <!-- CONTROLS -->
    <div style="display: flex; gap: var(--nt-sp-4); margin-top: var(--nt-sp-6); animation: fadeInUp 0.5s ease 0.3s; animation-fill-mode: both;">
        <button id="btnStart" class="btn btn-primary btn-lg" style="width: 140px;">
            <i class="fa-solid fa-play"></i> Başlat
        </button>
        <button id="btnPause" class="btn btn-secondary btn-lg" style="width: 140px; display: none;">
            <i class="fa-solid fa-pause"></i> Duraklat
        </button>
        <button id="btnReset" class="btn btn-danger btn-lg btn-icon" title="Sıfırla">
            <i class="fa-solid fa-rotate-right"></i>
        </button>
    </div>

    <!-- MODE SWITCHER -->
    <div class="tabs" style="margin-top: var(--nt-sp-8); justify-content: center; animation: fadeInUp 0.5s ease 0.4s; animation-fill-mode: both;">
        <button class="tab-btn active" data-time="25" data-mode="pomodoro">Pomodoro (25 dk)</button>
        <button class="tab-btn" data-time="5" data-mode="shortBreak">Kısa Mola (5 dk)</button>
        <button class="tab-btn" data-time="15" data-mode="longBreak">Uzun Mola (15 dk)</button>
    </div>

    <!-- HEDEFLER LISTESI -->
    <div class="card card-glass" style="max-width: 600px; width: 100%; margin-top: var(--nt-sp-12); animation: fadeInUp 0.5s ease 0.5s; animation-fill-mode: both;">
        <h3 style="margin-bottom: var(--nt-sp-4);"><i class="fa-solid fa-bullseye" style="color: var(--nt-accent);"></i> Günlük Hedefler</h3>
        <div style="display: flex; gap: 8px; margin-bottom: var(--nt-sp-4);">
            <input type="text" id="goalInput" class="form-input" placeholder="Kendine küçük bir hedef koy: Örn '3 sayfa fizik'">
            <button id="btnAddGoal" class="btn btn-primary">Ekle</button>
        </div>
        <ul id="goalList" style="list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 10px;"></ul>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    // ---- TIMER LOGIC ----
    let countdown;
    let timerDuration = 25 * 60; 
    let currentTimer = timerDuration;
    let isRunning = false;
    let currentMode = 'pomodoro';

    const display = document.getElementById('timeDisplay');
    const label = document.getElementById('sessionLabel');
    const circle = document.getElementById('progressCircle');
    const ring = document.getElementById('timerRing');
    const circumference = 2 * Math.PI * 110;

    circle.style.strokeDasharray = circumference;
    circle.style.strokeDashoffset = 0;

    function formatTime(seconds) {
        const m = Math.floor(seconds / 60);
        const s = seconds % 60;
        return `${m < 10 ? '0' : ''}${m}:${s < 10 ? '0' : ''}${s}`;
    }

    function updateDisplay() {
        display.textContent = formatTime(currentTimer);
        const percent = ((timerDuration - currentTimer) / timerDuration);
        const offset = circumference * percent;
        circle.style.strokeDashoffset = offset;
    }

    function setMode(mode, mins) {
        clearInterval(countdown);
        isRunning = false;
        currentMode = mode;
        timerDuration = mins * 60;
        currentTimer = timerDuration;
        updateDisplay();
        
        document.getElementById('btnStart').style.display = 'inline-flex';
        document.getElementById('btnPause').style.display = 'none';
        ring.classList.remove('pomodoro-pulse');

        if (mode === 'pomodoro') {
            label.textContent = 'Odaklanma Modu';
            document.documentElement.style.setProperty('--pomodoro-color', 'var(--nt-cyan)');
        } else if (mode === 'shortBreak') {
            label.textContent = 'Kısa Mola';
            document.documentElement.style.setProperty('--pomodoro-color', 'var(--nt-green)');
        } else {
            label.textContent = 'Uzun Mola';
            document.documentElement.style.setProperty('--pomodoro-color', 'var(--nt-blue)');
        }

        document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
        document.querySelector(`[data-mode="${mode}"]`).classList.add('active');
    }

    function timer() {
        clearInterval(countdown);
        const now = Date.now();
        const then = now + currentTimer * 1000;
        
        countdown = setInterval(() => {
            const secondsLeft = Math.round((then - Date.now()) / 1000);
            if (secondsLeft < 0) {
                clearInterval(countdown);
                isRunning = false;
                ring.classList.remove('pomodoro-pulse');
                
                // Play notification
                let audio = new Audio('https://assets.mixkit.co/sfx/preview/mixkit-software-interface-start-2574.mp3');
                audio.play().catch(e => console.log('Audio error:', e));

                return;
            }
            currentTimer = secondsLeft;
            updateDisplay();
        }, 1000);
    }

    document.getElementById('btnStart').addEventListener('click', () => {
        if (!isRunning && currentTimer > 0) {
            timer();
            isRunning = true;
            document.getElementById('btnStart').style.display = 'none';
            document.getElementById('btnPause').style.display = 'inline-flex';
            ring.classList.add('pomodoro-pulse');
        }
    });

    document.getElementById('btnPause').addEventListener('click', () => {
        clearInterval(countdown);
        isRunning = false;
        document.getElementById('btnStart').style.display = 'inline-flex';
        document.getElementById('btnPause').style.display = 'none';
        ring.classList.remove('pomodoro-pulse');
    });

    document.getElementById('btnReset').addEventListener('click', () => {
        setMode(currentMode, timerDuration / 60);
    });

    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            setMode(this.dataset.mode, parseInt(this.dataset.time));
        });
    });

    // ---- TODO LIST LOGIC ----
    const goalInput = document.getElementById('goalInput');
    const btnAddGoal = document.getElementById('btnAddGoal');
    const goalList = document.getElementById('goalList');

    // Load goals from local storage (UI only)
    let goals = JSON.parse(localStorage.getItem('nt_goals') || '[]');
    
    function renderGoals() {
        goalList.innerHTML = '';
        goals.forEach((g, idx) => {
            const li = document.createElement('li');
            li.style.display = 'flex';
            li.style.alignItems = 'center';
            li.style.gap = '10px';
            li.style.padding = '10px 14px';
            li.style.background = 'var(--nt-surface-2)';
            li.style.borderRadius = 'var(--nt-radius-sm)';
            li.style.transition = 'all 0.3s ease';
            if (g.done) {
                li.style.opacity = '0.5';
                li.style.textDecoration = 'line-through';
            }
            
            li.innerHTML = `
                <input type="checkbox" ${g.done ? 'checked' : ''} style="accent-color: var(--nt-primary); width: 18px; height: 18px; cursor: pointer;">
                <span style="flex:1; font-weight: 500;">${g.text}</span>
                <button class="btn-ghost" style="padding: 4px; color: var(--nt-red);"><i class="fa-solid fa-trash"></i></button>
            `;
            
            // Toggle
            li.querySelector('input').addEventListener('change', (e) => {
                goals[idx].done = e.target.checked;
                saveGoals();
                renderGoals();
            });
            
            // Delete
            li.querySelector('button').addEventListener('click', () => {
                goals.splice(idx, 1);
                saveGoals();
                renderGoals();
            });
            
            goalList.appendChild(li);
        });
    }

    function saveGoals() {
        localStorage.setItem('nt_goals', JSON.stringify(goals));
    }

    btnAddGoal.addEventListener('click', () => {
        const val = goalInput.value.trim();
        if (val) {
            goals.push({ text: val, done: false });
            goalInput.value = '';
            saveGoals();
            renderGoals();
        }
    });
    
    goalInput.addEventListener('keypress', (e) => {
        if (e.key === 'Enter') btnAddGoal.click();
    });

    // Initial render
    updateDisplay();
    renderGoals();
});
</script>
