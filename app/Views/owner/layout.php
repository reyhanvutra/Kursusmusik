<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Owner Panel</title>

    <style>
        body{
            margin:0;
            font-family: Arial, sans-serif;
            background:#1e1e1e;
            color:white;
        }

        /* SIDEBAR */
        .sidebar{
            width:220px;
            height:100vh;
            position:fixed;
            background:#111;
            padding-top:20px;
        }

        .sidebar h3{
            text-align:center;
            margin-bottom:30px;
        }

        .sidebar a{
            display:block;
            padding:12px 20px;
            color:#ccc;
            text-decoration:none;
        }

        .sidebar a:hover{
            background:#333;
            color:white;
        }

        /* CONTENT */
        .content{
            margin-left:220px;
            padding:20px;
        }

        /* CARD */
        .card{
            border-radius:10px;
            box-shadow:0 0 10px rgba(0,0,0,0.3);
        }

        /* TOPBAR */
        .topbar{
            background:#111;
            padding:10px 20px;
            margin-bottom:20px;
            display:flex;
            justify-content:space-between;
            align-items:center;
        }

        .btn-logout{
            background:red;
            padding:6px 12px;
            border:none;
            color:white;
            border-radius:5px;
            text-decoration:none;
        }

    </style>

</head>
<body>

    <!-- SIDEBAR -->
    <div class="sidebar">
        <h3>OWNER</h3>

        <a href="/owner/dashboard">Dashboard</a>
        <a href="/owner/laporan">Laporan</a>
        <a href="/owner/kursus">Daftar Kursus</a>
        <a href="/owner/log">Log Activity</a>

        <hr style="border-color:#333;">

        <a href="/logout" style="color:red;">Logout</a>
    </div>

    <!-- CONTENT -->
    <div class="content">

        <!-- TOPBAR -->
        <div class="topbar">
            <div>
                <b>Owner Panel</b>
            </div>

            <div>
                <span><?= session()->get('nama') ?? 'Owner'; ?></span>
            </div>
        </div>

        <!-- ISI -->
        <?= $this->renderSection('content'); ?>

    </div>

</body>
</html>