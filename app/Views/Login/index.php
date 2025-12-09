<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login - Phone Store</title>

    <!-- ICON FONT AWESOME untuk ikon mata -->
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

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
            box-sizing: border-box;
        }

        /* ====== WRAPPER KHUSUS PASSWORD (BIAR IKON DI DALAM KOTAK) ====== */
        .password-wrapper {
            position: relative;
            margin-bottom: 15px;
        }

        .password-wrapper input {
            width: 100%;
            padding: 10px 40px 10px 10px; /* ruang kanan untuk ikon */
            margin-bottom: 0;              /* supaya tidak dobel jarak */
            border: 1px solid #ccc;
            border-radius: 6px;
            box-sizing: border-box;
        }

        .password-toggle {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            padding: 0;
        }

        .password-toggle i {
            font-size: 16px;
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

            <!-- USERNAME -->
            <input type="text" name="username" placeholder="Username" required>

            <!-- PASSWORD + IKON MATA -->
            <div class="password-wrapper">
                <input type="password"
                       name="password"
                       id="password"
                       placeholder="Password"
                       required>

                <button type="button"
                        class="password-toggle"
                        onclick="togglePassword('password', this)">
                    <i class="fa fa-eye"></i>
                </button>
            </div>

            <button type="submit" class="btn-login">Login</button>
        </form>

        <p style="margin-top: 10px; font-size: 12px;">
            Hubungi admin jika Anda tidak memiliki akun.
        </p>
    </div>

    <script>
    function togglePassword(id, btn) {
        const input = document.getElementById(id);
        const icon  = btn.querySelector('i');

        if (!input) return;

        if (input.type === "password") {
            input.type = "text";
            icon.classList.remove("fa-eye");
            icon.classList.add("fa-eye-slash");
        } else {
            input.type = "password";
            icon.classList.remove("fa-eye-slash");
            icon.classList.add("fa-eye");
        }
    }
    </script>

</body>
</html>
