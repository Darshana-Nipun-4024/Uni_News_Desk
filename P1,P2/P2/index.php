<?php
session_start();
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In | NSBM NewsDesk</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header class="topbar">
        <a class="brand" href="index.php" aria-label="NSBM Green University Town home">
            <span class="brand-mark"><span></span><span></span><span></span></span>
            <span class="brand-name"><b>NSBM</b><small>GREEN<br>UNIVERSITY TOWN</small></span>
        </a>

        <nav class="main-nav">
            <a href="index.php"><span class="nav-icon">⌂</span>Home</a>
            <a href="#"><span class="nav-icon">⌕</span>Explore &amp; Search</a>
            <a href="#"><span class="nav-icon">➤</span>Submit News</a>
        </nav>

        <a class="breaking" href="#"><span>▤</span> BREAKING NEWS</a>
        <a class="login-link" href="index.php">Login / Register</a>
    </header>

    <main class="hero">
        <section class="welcome">
            <h1>Welcome Back</h1>
            <p>Sign in to NSBM NewsDesk</p>
        </section>

        <section class="login-card">
            <a class="brand card-brand" href="index.php" aria-label="NSBM Green University Town">
                <span class="brand-mark"><span></span><span></span><span></span></span>
                <span class="brand-name"><b>NSBM</b><small>GREEN<br>UNIVERSITY TOWN</small></span>
            </a>

            <div class="avatar" aria-hidden="true">
                <div class="avatar-head"></div>
                <div class="avatar-body"></div>
            </div>

            <form id="loginForm" action="login.php" method="POST" novalidate>
                <input type="hidden" name="csrf_token"
                       value="<?php echo htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8'); ?>">

                <label for="email">University Email</label>
                <input type="email" id="email" name="email"
                       placeholder="student@students.nsbm.ac.lk" autocomplete="username" required>

                <label for="password">Password</label>
                <input type="password" id="password" name="password"
                       placeholder="********" autocomplete="current-password" required>

                <div class="form-options">
                    <label class="remember">
                        <input type="checkbox" name="remember" value="1">
                        <span>Remember me</span>
                    </label>
                    <a href="forgot-password.php">Forgot password?</a>
                </div>

                <p class="form-error" id="formError" aria-live="polite"><?php
                    if (!empty($_SESSION['login_error'])) {
                        echo htmlspecialchars($_SESSION['login_error'], ENT_QUOTES, 'UTF-8');
                        unset($_SESSION['login_error']);
                    }
                ?></p>
                <button class="sign-in" type="submit">Sign In</button>
            </form>

            <div class="register-prompt">
                <p>No account?</p>
                <a href="register.php">Register</a>
            </div>
        </section>
    </main>
    <script src="script.js"></script>
</body>
</html>
