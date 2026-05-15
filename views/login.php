<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login &mdash; MediShop</title>
<link rel="stylesheet" href="public/assets/style.css">
</head>
<body class="auth-body">

<div class="auth-shell">
    <div class="auth-side">
        <div class="logo-big">&#128138;</div>
        <h1>MediShop</h1>
        <p>Your trusted online medicine shop. Sign in to browse, order, and manage medicines.</p>
        <ul class="feature-list">
            <li>&#10003; Admin manages medicines and orders</li>
            <li>&#10003; Customers browse and purchase</li>
            <li>&#10003; Instant AJAX search</li>
            <li>&#10003; Secure session login</li>
        </ul>
    </div>

    <div class="auth-form-wrap">
        <div class="auth-card">
            <h2>Welcome Back</h2>
            <p class="muted">Sign in to continue</p>

            <?php if (!empty($error)): ?>
                <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <form method="POST" action="index.php?page=login" class="form" id="login-form" novalidate>

                <div class="field">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email"
                           value="<?= htmlspecialchars($prefill) ?>"
                           placeholder="Enter your email" required autofocus>
                    <span class="js-error" id="err-email"></span>
                </div>

                <div class="field">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password"
                           placeholder="Enter your password" required>
                    <span class="js-error" id="err-password"></span>
                </div>
<!--
                <label class="checkbox">
                    <input type="checkbox" name="remember_me" <?= !empty($prefill) ? 'checked' : '' ?>>
                    <span>Remember me</span>
                </label>
-->
                <button type="submit" class="btn btn-primary btn-block">Sign In</button>
            </form>

            <p class="auth-foot">Don't have an account?
                <a href="index.php?page=register">Create one</a>
            </p>
        </div>
    </div>
</div>

<script src="public/js/ajax.js"></script>
</body>
</html>