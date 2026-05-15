<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Register &mdash; MediShop</title>
<link rel="stylesheet" href="public/assets/style.css">
</head>
<body class="auth-body">

<div class="auth-shell">
    <div class="auth-side">
        <div class="logo-big">&#128138;</div>
        <h1>Join MediShop</h1>
        <p>Create your account to browse and order medicines from the comfort of your home.</p>
        <ul class="feature-list">
            <li>&#10003; Browse medicines by category</li>
            <li>&#10003; Search with instant AJAX filters</li>
            <li>&#10003; Track your orders easily</li>
            <li>&#10003; Secure and fast checkout</li>
        </ul>
    </div>

    <div class="auth-form-wrap">
        <div class="auth-card">
            <h2>Create Account</h2>
            <p class="muted">Fill in your details below</p>

            <?php if (!empty($error)): ?>
                <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            <?php if (!empty($success)): ?>
                <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
            <?php endif; ?>

            <form method="POST" action="index.php?page=register" class="form" id="register-form" novalidate>

                <div class="field">
                    <label for="name">Full Name</label>
                    <input type="text" id="name" name="name"
                           value="<?= htmlspecialchars($old_name) ?>"
                           placeholder="e.g. Rahim Uddin" required>
                    <span class="js-error" id="err-name"></span>
                </div>

                <div class="field">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email"
                           value="<?= htmlspecialchars($old_email) ?>"
                           placeholder="e.g. rahim@email.com" required>
                    <span class="js-error" id="err-email"></span>
                </div>

                <div class="field">
                    <label for="phone">Phone Number</label>
                    <input type="text" id="phone" name="phone"
                           value="<?= htmlspecialchars($old_phone) ?>"
                           placeholder="e.g. 01712345678" required>
                    <span class="js-error" id="err-phone"></span>
                </div>

                <div class="field">
                    <label for="address">Address</label>
                    <textarea id="address" name="address"
                              placeholder="Your delivery address" rows="2" required><?= htmlspecialchars($old_address) ?></textarea>
                    <span class="js-error" id="err-address"></span>
                </div>

                <div class="field">
                    <label for="role">Register As</label>
                    <select id="role" name="role">
                        <option value="customer" <?= $old_role === 'customer' ? 'selected' : '' ?>>Customer</option>
                        <option value="vendor"    <?= $old_role === 'vendor'    ? 'selected' : '' ?>>Vendor</option>
                    </select>
                </div>

                <div class="field-row">
                    <div class="field">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password"
                               placeholder="Min 8 characters" required>
                        <span class="js-error" id="err-password"></span>
                    </div>
                    <div class="field">
                        <label for="confirm_password">Confirm</label>
                        <input type="password" id="confirm_password" name="confirm_password"
                               placeholder="Repeat password" required>
                        <span class="js-error" id="err-confirm"></span>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary btn-block">Create Account</button>
            </form>

            <p class="auth-foot">Already have an account?
                <a href="index.php?page=login">Sign in</a>
            </p>
        </div>
    </div>
</div>

<script src="public/js/ajax.js"></script>
</body>
</html>