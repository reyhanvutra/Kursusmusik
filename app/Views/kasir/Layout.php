<!DOCTYPE html>
<html>
<head>
    <title>Kasir</title>
    <style>
        body{
            background:#1e1e1e;
            color:white;
            font-family: Arial;
            margin:0;
        }

        .navbar{
            background:#111;
            padding:15px 30px;
            display:flex;
            justify-content:space-between;
            align-items:center;
        }

        .btn{
            padding:10px 15px;
            border-radius:8px;
            text-decoration:none;
            color:white;
        }

        .btn-red{ background:#c00000; }
        .btn-red:hover{ background:#a00000; }

        .container{
            padding:30px;
        }

        .card{
            border-radius:12px;
            padding:20px;
        }

        .card-dark{ background:#2b2b2b; }
        .card-light{ background:#3a3a3a; }

        table{
            width:100%;
            border-collapse: collapse;
        }

        th, td{
            padding:10px;
            text-align:left;
        }

        th{
            background:#444;
        }

        tr{
            border-bottom:1px solid #555;
        }

        .badge{
            background:#555;
            padding:3px 8px;
            border-radius:5px;
            margin-right:3px;
            display:inline-block;
        }
    </style>
</head>
<body>

<div class="navbar">
    <h3>Kasir Panel</h3>
    <a href="/logout" class="btn btn-red">Logout</a>
</div>

<?= $this->renderSection('content'); ?>

</body>
</html>