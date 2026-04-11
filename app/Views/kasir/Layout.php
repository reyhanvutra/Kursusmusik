<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kasir Panel | Premium System</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700;800;900&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --primary-red: #990000;
            --bg-dark: #0f0f0f;
            --navbar-bg: rgba(13, 13, 13, 0.8);
            --card-obsidian: #1a1a1a;
            --text-main: #ffffff;
            --text-dim: #888;
        }

        body {
            background-color: var(--bg-dark);
            color: var(--text-main);
            font-family: 'Inter', sans-serif;
            margin: 0;
            padding: 0;
            overflow-x: hidden;
            /* Garis halus background agar tidak flat */
            background-image: radial-gradient(circle at 50% 50%, #1a1a1a 0%, #0f0f0f 100%);
            min-height: 100vh;
        }

        /* --- NAVBAR PREMIUM --- */
        .navbar {
            background: var(--navbar-bg);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            padding: 20px 50px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.03);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .brand-section {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .brand-logo {
            background: var(--primary-red);
            width: 35px;
            height: 35px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 900;
            font-size: 18px;
            box-shadow: 0 0 20px rgba(153, 0, 0, 0.4);
        }

        .navbar h3 {
            font-family: 'Montserrat', sans-serif;
            font-weight: 800;
            font-size: 20px;
            letter-spacing: -0.5px;
            margin: 0;
            background: linear-gradient(to right, #fff, #666);
            background-clip: text;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .nav-user-info {
            display: flex;
            align-items: center;
            gap: 25px;
        }

        .logout-link {
            color: var(--text-dim);
            text-decoration: none;
            font-size: 12px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            transition: 0.3s;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .logout-link:hover {
            color: var(--primary-red);
        }

        /* --- MAIN WRAPPER --- */
        .main-content {
            padding: 40px 0;
            animation: fadeIn 0.8s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* --- GLOBAL UTILITIES (Agar seragam di semua page) --- */
        .page-header {
            font-family: 'Montserrat', sans-serif;
            font-weight: 900;
        }

        /* Scrollbar Premium */
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #0f0f0f; }
        ::-webkit-scrollbar-thumb { 
            background: #252525; 
            border-radius: 10px; 
        }
        ::-webkit-scrollbar-thumb:hover { background: var(--primary-red); }

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
        .brand-link {
    text-decoration: none;
    color: inherit;
    transition: 0.3s;
}

.brand-link:hover {
    color: #ff0000; /* biar ada efek hover */
}
    </style>
</head>
<body>

<nav class="navbar">
    <div class="brand-section">
        <h2>  <a href="/kasir/dashboard" class="brand-link">
            Welcome Kasir Dashboard <?= session()->get('nama'); ?>
        </a></h2>
    </div>

     <div class="user-profile">
            <div style="text-align: right;">
                <div style="font-weight: 800; font-size: 14px;"><?= session()->get('nama'); ?></div>
                <small style="color: var(--text-secondary); font-size: 11px;">Kasir</small>
            </div>
            
            <div class="user-avatar">
                <i class="fa-solid fa-user-tie"></i>
            </div>

            <a href="/logout" class="btn-logout-top" title="Keluar">
                <i class="fa-solid fa-right-from-bracket"></i>
            </a>
</nav>

<main class="main-content">
    <?= $this->renderSection('content'); ?>
</main>

<footer style="padding: 40px; text-align: center; color: #333; font-size: 11px; font-weight: 700; letter-spacing: 1px;">
    &copy; KURSUS MUSIK VOLTMUSIC.
</footer>

</body>
</html>