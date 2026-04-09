<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        *, *::before, *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        :root {
            --bg-base:        #121212; 
            --bg-surface:     #121212; 
            --bg-sidebar:     #121212;
            
            /* Merah gelap sesuai Figma */
            --accent:         #800000; 
            --accent-glow:    rgba(128, 0, 0, 0.5);

            --text-primary:   #ffffff;
            --text-secondary: #9e9e9e;
            
            --sidebar-w:      80px; 
            --transition:     0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        body {
            display: flex;
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--bg-base);
            color: var(--text-primary);
            min-height: 100vh;
            overflow: auto;
        }

        /* ══════════════════════════════
           SIDEBAR (POSISI TENGAH)
        ══════════════════════════════ */
        .sidebar {
            width: var(--sidebar-w);
            background: var(--bg-sidebar);
            height: 100vh;
            position: fixed;
            left: 0; top: 0;
            display: flex;
            flex-direction: column;
            /* Membuat isi sidebar berada di tengah vertikal */
            justify-content: center; 
            align-items: center;
            z-index: 100;
        }

        .nav-group {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 25px; /* Jarak antar icon sesuai figma */
        }

        .nav-icon {
            width: 48px;
            height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            text-decoration: none;
            font-size: 20px;
            transition: var(--transition);
            position: relative;
            z-index: 1;
        }

        /* ── EFEK LINGKARAN ABSTRAK (ACTIVE) ── */
        .nav-icon.active {
            color: #fff;
            background: var(--accent);
            /* Bentuk tidak beraturan (blob) seperti Figma */
            border-radius: 30% 70% 70% 30% / 30% 30% 70% 70%;
            box-shadow: 0 4px 15px var(--accent-glow);
            animation: morph 3s ease-in-out infinite both alternate;
        }

        /* Animasi halus agar bentuk abstraknya sedikit bergerak */
        @keyframes morph {
            0% { border-radius: 30% 70% 70% 30% / 30% 30% 70% 70%; }
            100% { border-radius: 50% 50% 20% 80% / 25% 80% 20% 75%; }
        }

        .nav-divider {
            width: 30px;
            height: 1px;
            background: rgba(255,255,255,0.1);
            margin: 5px 0;
        }

        /* ══════════════════════════════
           MAIN & TOPBAR
        ══════════════════════════════ */
        .main {
            margin-left: var(--sidebar-w);
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            height: auto;
        }

        .topbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 30px 40px;
            background: var(--bg-surface);
        }

        .topbar-title h1 {
            font-size: 22px;
            font-weight: 700;
        }

        .topbar-title p {
            font-size: 14px;
            color: var(--text-secondary);
        }

        .btn-logout {
            padding: 8px 24px;
            background: var(--accent);
            color: #fff;
            border-radius: 6px;
            text-decoration: none;
            font-size: 13px;
            font-weight: 600;
            transition: var(--transition);
        }

        .btn-logout:hover {
            opacity: 0.8;
        }

        .content {
            flex: 1;
            padding: 0 40px 40px 40px;
        }
        /* ALERT GLOBAL */
.global-alert {
    position: fixed;
    top: 20px;
    right: 20px;
    z-index: 9999;
    min-width: 250px;
}

/* ALERT BASE */
.alert {
    padding: 12px 16px;
    border-radius: 10px;
    font-size: 14px;
    animation: fadeIn 0.4s ease;
}

/* SUCCESS */
.alert.success {
    background: #1e7e34;
    color: #d4edda;
    border-left: 5px solid #28a745;
}

/* ERROR */
.alert.error {
    background: #7e1e1e;
    color: #f8d7da;
    border-left: 5px solid #dc3545;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(-10px); }
    to { opacity: 1; transform: translateY(0); }
}

        .user-profile {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .user-avatar {
            width: 42px;
            height: 42px;
            background: var(--accent);
            border-radius: 12px;
            display: grid;
            place-items: center;
            color: #fff;
            font-size: 18px;
        }

        /* Tombol Logout di Kanan Atas */
        .btn-logout-top {
            width: 42px;
            height: 42px;
            display: grid;
            place-items: center;
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 12px;
            color: var(--text-secondary);
            text-decoration: none;
            transition: var(--transition);
            margin-left: 5px;
        }

        .btn-logout-top:hover {
            background: var(--accent);
            color: #fff;
            border-color: transparent;
            box-shadow: 0 8px 20px var(--accent-glow);
            transform: translateY(-2px);
        }

        .content {
            flex: 1;
            padding: 0 40px 40px 40px;
        }
    </style>
</head>
<body>

<?php
$currentUri = uri_string(); 
?>

<aside class="sidebar">
    <nav class="nav-group">
        <a href="/admin/dashboard"
           class="nav-icon <?= (str_contains($currentUri, 'dashboard')) ? 'active' : '' ?>"
           data-tip="Dashboard">
            <i class="fa-solid fa-house"></i>
        </a>

        <a href="/admin/user"
           class="nav-icon <?= (str_contains($currentUri, 'user')) ? 'active' : '' ?>"
           data-tip="Data User">
            <i class="fa-regular fa-user"></i>
        </a>

        <a href="/admin/kursus"
           class="nav-icon <?= (str_contains($currentUri, 'kursus')) ? 'active' : '' ?>"
           data-tip="Data Kursus">
            <i class="fa-solid fa-music"></i> </a>

             <a href="/admin/kategori"
           class="nav-icon <?= (str_contains($currentUri, 'kategori')) ? 'active' : '' ?>"
           data-tip="Kategori Kursus">
            <i class="fa-solid fa-tags"></i>
        </a>

        <a href="/admin/siswa"
           class="nav-icon <?= (str_contains($currentUri, 'siswa')) ? 'active' : '' ?>"
           data-tip="Data Siswa">
            <i class="fa-solid fa-user-graduate"></i>
        </a>

         <a href="/admin/setting"
           class="nav-icon <?= (str_contains($currentUri, 'setting')) ? 'active' : '' ?>"
           data-tip="Setting Biaya">
            <i class="fa-solid fa-sliders"></i>
        </a>
        <a href="/admin/mentor"
           class="nav-icon <?= (str_contains($currentUri, 'mentor')) ? 'active' : '' ?>"
           data-tip="Data Mentor">
            <i class="fa-solid fa-chalkboard-teacher"></i>
        </a>
    </nav>
</aside>

<div class="main">

    <!-- 🔥 GLOBAL ALERT -->
    <?php if(session()->getFlashdata('success')): ?>
        <div class="alert success global-alert">
            <?= session()->getFlashdata('success'); ?>
        </div>
    <?php endif; ?>

    <?php if(session()->getFlashdata('error')): ?>
        <div class="alert error global-alert">
            <?= session()->getFlashdata('error'); ?>
        </div>
    <?php endif; ?>

    <header class="topbar">
        <div class="topbar-title">
            <h1><?= $page_title ?? 'Welcome Admin Dashboard' ?></h1>
            <p><?= $page_subtitle ?? 'Selamat Datang ' . (session()->get('nama') ?? 'Nama Anda') ?></p>
        </div>

           <div class="user-profile">
            <div style="text-align: right;">
                <div style="font-weight: 800; font-size: 14px;"><?= session()->get('nama'); ?></div>
                <small style="color: var(--text-secondary); font-size: 11px;">Admin</small>
            </div>
            
            <div class="user-avatar">
                <i class="fa-solid fa-user-tie"></i>
            </div>

            <a href="/logout" class="btn-logout-top" title="Keluar">
                <i class="fa-solid fa-right-from-bracket"></i>
            </a>
        </div>
    </header>

    <main class="content">
        <?= $this->renderSection('content'); ?>
    </main>
</div>
<script>
setTimeout(() => {
    const alerts = document.querySelectorAll('.alert');
    
    alerts.forEach(alert => {
        alert.style.transition = "0.5s";
        alert.style.opacity = "0";
        alert.style.transform = "translateY(-10px)";
        
        setTimeout(() => {
            alert.remove();
        }, 500);
    });

}, 3000);
</script>
</body>

</html>