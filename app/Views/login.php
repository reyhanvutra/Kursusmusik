<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://fonts.googleapis.com/css2?family=Anton&family=Montserrat:wght@200;300;400;500&display=swap" rel="stylesheet">
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body, html {
            height: 100%;
            width: 100%;
            overflow: hidden;
            background: #000;
        }

        /* ── Rectangle 2: Red gradient ── */
        .bg-red {
            position: absolute;
            width: 1062px;
            height: 1054px;
            left: 378px;
            top: -11px;
            background: linear-gradient(180deg, #2A0202 32.69%, #9C0606 65.38%);
            z-index: 1;
        }

        /* ── Rectangle 1: Black wavy panel ──
             Lekukan digeser ke kanan (~55% layar) agar
             tepat membelah stiker headset di tengah        */
        .bg-black-wave {
            position: absolute;
            width: 900px;          /* sedikit lebih lebar */
            height: 1035px;
            left: -13px;
            top: -11px;
            background: #090808;
            /* Kurva kanan melewati ~55% width layar (≈830px dari kiri) */
            clip-path: path('M0,0 L830,0 Q900,190 840,370 Q770,555 860,730 Q920,900 855,1035 L0,1035 Z');
            border-radius: 15px;
            z-index: 2;
        }

        /* ── Login Form ── */
        .login-wrapper {
            position: absolute;
            top: 50%;
            left: 80px;
            transform: translateY(-50%);
            z-index: 10;
            width: 340px;
            color: white;
        }

        /* Judul LOGIN — blocky bold seperti Figma */
        .login-wrapper h1 {
            font-family: 'Anton', sans-serif;
            font-size: 3.8rem;
            font-weight: 400;
            letter-spacing: 3px;
            text-transform: uppercase;
            color: #ffffff;
            margin-bottom: 6px;
            line-height: 1;
        }

        /* Subtitle */
        .login-wrapper p {
            font-family: 'Montserrat', sans-serif;
            color: #666;
            font-size: 0.72rem;
            font-weight: 300;
            letter-spacing: 0.5px;
            margin-bottom: 38px;
        }

        .input-group {
            margin-bottom: 28px;
            position: relative;
        }

        .input-group input {
            width: 100%;
            padding: 10px 0;
            background: transparent;
            border: none;
            border-bottom: 1px solid #2e2e2e;
            color: #ddd;
            outline: none;
            font-family: 'Montserrat', sans-serif;
            font-size: 0.82rem;
            font-weight: 300;
            letter-spacing: 1px;
            transition: border-color 0.4s;
        }

        .input-group input::placeholder {
            color: #4a4a4a;
            font-weight: 300;
            letter-spacing: 1px;
        }

        .input-group input:focus {
            border-bottom-color: #9C0606;
        }

           /* Tombol LOGIN */
        .btn-login {
            background: transparent;
            color: #ccc;
            border: 1px solid #444;
            padding: 13px 55px;
            border-radius: 50px;
            font-family: 'Montserrat', sans-serif;
            font-size: 0.7rem;
            font-weight: 400;
            letter-spacing: 4px;
            text-transform: uppercase;
            cursor: pointer;
            margin-top: 18px;
            transition: all 0.35s ease;
            position: relative;
            overflow: hidden;
        }

        .btn-login::before {
            content: '';
            position: absolute;
            inset: 0;
            background: #9C0606;
            transform: scaleX(0);
            transform-origin: left;
            transition: transform 0.35s ease;
            z-index: -1;
            border-radius: 50px;
        }

        .btn-login:hover {
            color: #fff;
            border-color: #9C0606;
        }

        .btn-login:hover::before {
            transform: scaleX(1);
        }

        /* ── Stickers ── 
             Semua disamakan 160x160px
             opacity 0.82, blend mode normal agar warna asli tetap
             tapi tetap menyatu karena opacity                       */
        .sticker {
            position: absolute;
            width: 160px;
            height: 160px;
            z-index: 5;
            pointer-events: none;
            opacity: 0.82;
        }

        .sticker img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            display: block;
        }

        /* Headset — sedikit di atas garis tengah, tepat di lekukan */
        .stk-headset {
            top: 6%;
            left: calc(55% - 80px); /* tengah tepat di lekukan */
        }

        /* Petir — kanan atas */
        .stk-petir {
            top: 2%;
            right: 3%;
        }

        /* Vinyl — kanan tengah */
        .stk-vinyl {
            top: 40%;
            right: 8%;
        }

        /* Mic — bawah tengah, di lekukan */
        .stk-mic {
            bottom: 12%;
            left: calc(55% - 80px);
        }

        /* Guitar — kanan bawah */
        .stk-guitar {
            bottom: 2%;
            right: 2%;
        }
    </style>
</head>
<body>

    <div class="bg-red"></div>
    <div class="bg-black-wave"></div>

    <div class="login-wrapper">
        <h1>LOGIN</h1>
        <p>Silahkan Masukkan Username dan Password</p>

        <form action="/proses_login" method="post">
            <div class="input-group">
                <input type="email" name="email" placeholder="Email">
            </div>
            <div class="input-group">
                <input type="password" name="password" placeholder="Password">
            </div>
            <button type="submit" class="btn-login">LOGIN</button>
        </form>
    </div>

    <div class="sticker stk-headset">
        <img src="/images/8385ebb78d2a2568afb34eb3baad30f2-removebg-preview.png" alt="">
    </div>
    <div class="sticker stk-petir">
        <img src="/images/b4b0d594317ea464b45b3b16acbd98e6-removebg-preview.png" alt="">
    </div>
    <div class="sticker stk-vinyl">
        <img src="/images/58417fc4dd16022c7dc980bae6e2d57a-removebg-preview.png" alt="">
    </div>
    <div class="sticker stk-mic">
        <img src="/images/03e7896fa819bdd7a81cf7807c20f0b4-removebg-preview.png" alt="">
    </div>
    <div class="sticker stk-guitar">
        <img src="/images/b263e860d3d9d1823094edc2aee95473-removebg-preview.png" alt="">
    </div>

</body>
</html>