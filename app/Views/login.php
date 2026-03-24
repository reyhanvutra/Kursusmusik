<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>

<h2>Login</h2>

<?php if(session()->getFlashdata('error')): ?>
<p style="color:red"><?= session()->getFlashdata('error') ?></p>
<?php endif; ?>

<form action="/proses_login" method="post">
    <input type="email" name="email" placeholder="Email"><br><br>
    <input type="password" name="password" placeholder="Password"><br><br>
    <button type="submit">Login</button>
</form>

</body>
</html>