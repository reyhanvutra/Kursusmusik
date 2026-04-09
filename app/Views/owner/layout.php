<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Owner Panel | Music Course</title>
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
            --accent:         #990000; 
            --accent-glow:    rgba(153, 0, 0, 0.5);
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
            overflow-x: hidden;
        }

        /* --- SIDEBAR --- */
        .sidebar {
            width: var(--sidebar-w);
            background: var(--bg-sidebar);
            height: 100vh;
            position: fixed;
            left: 0; top: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            z-index: 100;
            border-right: 1px solid rgba(255,255,255,0.05);
        }

        .nav-group {
            flex: 1; /* Mengambil ruang agar menu bisa di tengah vertikal */
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 25px;
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
        }

        .nav-icon:hover {
            color: var(--accent);
        }

        .nav-icon.active {
            color: #fff;
            background: var(--accent);
            border-radius: 30% 70% 70% 30% / 30% 30% 70% 70%;
            box-shadow: 0 4px 15px var(--accent-glow);
            animation: morph 3s ease-in-out infinite both alternate;
        }

        @keyframes morph {
            0% { border-radius: 30% 70% 70% 30% / 30% 30% 70% 70%; }
            100% { border-radius: 50% 50% 20% 80% / 25% 80% 20% 75%; }
        }

        /* --- MAIN CONTENT & TOPBAR --- */
        .main {
            margin-left: var(--sidebar-w);
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
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
            font-weight: 800;
            letter-spacing: 1px;
            text-transform: uppercase;
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

<?php $currentUri = uri_string(); ?>

<aside class="sidebar">

    <nav class="nav-group">
        <a href="/owner/dashboard" 
           class="nav-icon <?= (str_contains($currentUri, 'dashboard')) ? 'active' : '' ?>" title="Dashboard">
            <i class="fa-solid fa-house"></i>
        </a>

        <a href="/owner/laporan" 
           class="nav-icon <?= (str_contains($currentUri, 'laporan')) ? 'active' : '' ?>" title="Laporan">
            <i class="fa-solid fa-file-invoice-dollar"></i>
        </a>

        <a href="/owner/kursus" 
           class="nav-icon <?= (str_contains($currentUri, 'kursus')) ? 'active' : '' ?>" title="Data Kursus">
            <i class="fa-solid fa-music"></i>
        </a>

        <a href="/owner/datasiswa" 
           class="nav-icon <?= (str_contains($currentUri, 'datasiswa')) ? 'active' : '' ?>" title="Data Siswa">
            <i class="fa-solid fa-user-graduate"></i>
        </a>

        <a href="/owner/log" 
           class="nav-icon <?= (str_contains($currentUri, 'log')) ? 'active' : '' ?>" title="Log Aktivitas">
            <i class="fa-solid fa-clock-rotate-left"></i>
        </a>
    </nav>
</aside>

<div class="main">
    <header class="topbar">
        <div class="topbar-title">
            <h1>Welcome <?= session()->get('nama') ?? 'Owner'; ?></h1>
            <p style="font-size: 13px; color: var(--text-secondary);">
                Halo, <?= session()->get('nama') ?? 'Owner'; ?>! Selamat bekerja kembali.
            </p>
        </div>
        
        <div class="user-profile">
            <div style="text-align: right;">
                <div style="font-weight: 800; font-size: 14px;"><?= session()->get('nama'); ?></div>
                <small style="color: var(--text-secondary); font-size: 11px;">Owner</small>
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

</body>
</html>