<!DOCTYPE html>
<html>
<head>
    <title>Admin Panel</title>
    <style>
        body {
            display: flex;
            font-family: Arial;
        }
        .sidebar {
            width: 200px;
            background: #2c3e50;
            height: 100vh;
            color: white;
            padding: 20px;
        }
        .sidebar a {
            display: block;
            color: white;
            margin: 10px 0;
            text-decoration: none;
        }
        .content {
            flex: 1;
            padding: 20px;
        }
        .card {
            display: inline-block;
            width: 200px;
            padding: 20px;
            margin: 10px;
            background: #3498db;
            color: white;
            border-radius: 10px;
        }
    </style>
</head>
<body>

<div class="sidebar">
    <h3>Admin</h3>
    <a href="/admin/dashboard">Dashboard</a>
    <a href="/admin/user">Data User</a>
    <a href="/admin/kursus">Data Kursus</a>
    <a href="/admin/setting">Setting Biaya Pendaftaran</a>
    <a href="/logout">Logout</a>
</div>

<div class="content">
    <?= $this->renderSection('content'); ?>
</div>

</body>
</html>