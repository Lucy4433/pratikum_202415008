<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login - Phone Store</title>

    <style>
        body {
            background: #F5F7FF;
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .login-box {
            background: white;
            padding: 30px;
            width: 350px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            text-align: center;
        }

        .login-box img {
            width: 80px;
            margin-bottom: 10px;
        }

        .login-box input {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 6px;
        }

        .btn-login {
            background: #5C6BC0;
            color: white;
            padding: 10px;
            width: 100%;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 16px;
        }

        .btn-login:hover {
            background: #4554A8;
        }
    </style>
</head>

<body>

    <div class="login-box">
        <img src="<?= base_url('assets/images/Logo_kecil.png') ?>" alt="Logo">

        <h3 style="margin-bottom: 20px;">Phone Store Login</h3>

        <?php if(session()->getFlashdata('error')): ?>
            <p style="color:red;"><?= session()->getFlashdata('error') ?></p>
        <?php endif; ?>

        <form action="<?= base_url('login/proses') ?>" method="post">
            <?= csrf_field() ?>

            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>

            <button type="submit" class="btn-login">Login</button>
        </form>

        <p style="margin-top: 10px; font-size: 12px;">
            Hubungi admin jika Anda tidak memiliki akun.
        </p>
    </div>

</body>
</html>
