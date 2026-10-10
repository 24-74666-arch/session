<?php
require 'db.php';

// Already logged in? Open the dashboard.
if (isset($_SESSION['user_id'])) {
  header('Location: dashboard.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '' || $password === '') {
        $error = 'Please enter your username and password.';
    } else {
        $stmt = $pdo->prepare('SELECT * FROM users WHERE username = ? OR email = ? LIMIT 1');
        $stmt->execute([$username, $username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            session_regenerate_id(true);
            $_SESSION['user_id']  = $user['id'];
            $_SESSION['fullname'] = $user['fullname'];
            header('Location: dashboard.php');
            exit;
        }
        $error = 'Invalid username or password.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
  <style>
    :root {
      --ink: #17324d;
      --muted: #6e8295;
      --teal: #087f8c;
      --teal-dark: #06616d;
    }
    * { box-sizing: border-box; }
    body { min-height: 100vh; margin: 0; padding: 24px; display: grid; place-items: center; font-family: 'Poppins', sans-serif; color: var(--ink); background: #edf3f2; }
    .login-frame { display: grid; width: min(100%, 1120px); min-height: min(700px, calc(100svh - 48px)); grid-template-columns: 1fr 1fr; overflow: hidden; border: 1px solid rgba(255,255,255,.72); border-radius: 26px; background: #fff; box-shadow: 0 26px 80px rgba(21,55,65,.15); animation: frame-in .7s cubic-bezier(.2,.75,.25,1) both; }
    .welcome-panel { position: relative; display: flex; min-height: 100%; flex-direction: column; justify-content: space-between; overflow: hidden; padding: 42px 46px; background: linear-gradient(145deg, #12394b 0%, #087f8c 58%, #bd8654 135%); color: #fff; }
    .welcome-panel::before, .welcome-panel::after { position: absolute; border: 1px solid rgba(255,255,255,.2); border-radius: 20px; content: ''; pointer-events: none; transform: rotate(-12deg); }
    .welcome-panel::before { top: 19%; right: -17%; width: 82%; height: 48%; background: linear-gradient(135deg, rgba(255,255,255,.08), transparent 70%); }
    .welcome-panel::after { right: 12%; bottom: 13%; width: 54%; height: 27%; border-color: rgba(255,255,255,.13); transform: rotate(9deg); }
    .welcome-top, .welcome-copy, .welcome-foot { position: relative; z-index: 1; }
    .brand-lockup { display: inline-flex; align-items: center; gap: 12px; color: #fff; font-size: 1rem; font-weight: 600; }
    .brand-mark { display: grid; width: 42px; height: 42px; place-items: center; border: 1px solid rgba(255,255,255,.35); border-radius: 14px; background: rgba(255,255,255,.14); font-weight: 700; box-shadow: 0 8px 24px rgba(7,30,39,.15); }
    .welcome-copy { max-width: 420px; margin: auto 0; padding: 44px 0; animation: copy-in .7s .12s ease both; }
    .welcome-kicker { margin-bottom: 15px; color: rgba(255,255,255,.72); font-size: .75rem; font-weight: 500; }
    .welcome-copy h1 { max-width: 390px; margin-bottom: 16px; font-size: 2.8rem; font-weight: 600; line-height: 1.2; letter-spacing: 0; }
    .welcome-copy p { max-width: 370px; margin: 0; color: rgba(255,255,255,.78); font-size: .93rem; line-height: 1.8; }
    .welcome-foot { color: rgba(255,255,255,.62); font-size: .74rem; }
    .form-panel { display: flex; align-items: center; justify-content: center; padding: 56px clamp(32px, 5vw, 72px); }
    .auth-content { width: 100%; max-width: 390px; animation: copy-in .65s .18s ease both; }
    .form-eyebrow { margin-bottom: 10px; color: var(--teal); font-size: .75rem; font-weight: 600; }
    .auth-content h2 { margin-bottom: 8px; color: var(--ink); font-size: 1.75rem; font-weight: 600; letter-spacing: 0; }
    .auth-subtitle { margin-bottom: 28px; color: var(--muted); font-size: .88rem; }
    .form-label { margin-bottom: 8px; color: #35536a; font-size: .82rem; font-weight: 500; }
    .field-wrap { position: relative; }
    .field-icon { position: absolute; top: 50%; left: 15px; z-index: 2; color: #8396a3; font-size: 1rem; pointer-events: none; transform: translateY(-50%); }
    .form-control {
      min-height: 50px;
      border: 1px solid #d9e3e8;
      border-radius: 12px;
      background: rgba(255,255,255,.78);
      color: var(--ink);
      font-size: .92rem;
      transition: border-color .2s ease, box-shadow .2s ease, background .2s ease;
    }
    .field-wrap .form-control { padding-left: 44px; }
    .password-field { padding-right: 52px !important; }
    .form-control:focus { border-color: #41a7a3; background: #fff; box-shadow: 0 0 0 4px rgba(8,127,140,.12); }
    .password-toggle { position: absolute; top: 50%; right: 7px; z-index: 2; display: grid; width: 38px; height: 38px; place-items: center; border: 0; border-radius: 9px; background: transparent; color: #718493; transform: translateY(-50%); }
    .password-toggle:hover, .password-toggle:focus-visible { background: #edf5f4; color: var(--teal-dark); }
    .btn-auth {
      position: relative;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 10px;
      min-height: 50px;
      border: 0;
      border-radius: 12px;
      background: linear-gradient(110deg, var(--teal), #159b91);
      color: #fff;
      font-weight: 600;
      box-shadow: 0 9px 20px rgba(8,127,140,.2);
      transition: transform .2s ease, box-shadow .2s ease, filter .2s ease;
    }
    .btn-auth:hover, .btn-auth:focus-visible { transform: translateY(-2px); background: linear-gradient(110deg, var(--teal-dark), #108b82); color: #fff; box-shadow: 0 13px 24px rgba(8,127,140,.27); }
    .btn-auth:disabled { cursor: wait; opacity: .86; transform: none; }
    .btn-spinner { display: none; width: 17px; height: 17px; border: 2px solid rgba(255,255,255,.45); border-top-color: #fff; border-radius: 50%; animation: spin .7s linear infinite; }
    .btn-auth.is-loading .btn-spinner { display: inline-block; }
    .auth-footer { color: var(--muted); font-size: .88rem; }
    .auth-footer a { color: var(--teal-dark); font-weight: 600; text-decoration: none; }
    .auth-footer a:hover { text-decoration: underline; }
    .alert { border: 0; border-radius: 12px; font-size: .86rem; }
    @keyframes frame-in { from { opacity: 0; transform: translateY(16px) scale(.99); } to { opacity: 1; transform: translateY(0) scale(1); } }
    @keyframes copy-in { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
    @keyframes spin { to { transform: rotate(360deg); } }
    @media (max-width: 760px) {
      body { padding: 16px; }
      .login-frame { min-height: 0; grid-template-columns: 1fr; }
      .welcome-panel { min-height: 245px; padding: 25px 28px; }
      .welcome-copy { margin: 0; padding: 28px 0 14px; }
      .welcome-copy h1 { max-width: 500px; font-size: 1.8rem; }
      .welcome-copy p, .welcome-foot { display: none; }
      .form-panel { padding: 36px 28px 40px; }
    }
    @media (max-width: 420px) {
      body { padding: 10px; }
      .login-frame { border-radius: 20px; }
      .welcome-panel { min-height: 210px; padding: 21px 22px; }
      .welcome-copy { padding-top: 22px; }
      .welcome-copy h1 { margin-bottom: 0; font-size: 1.55rem; }
      .form-panel { padding: 30px 22px 32px; }
      .auth-content h2 { font-size: 1.55rem; }
    }
    @media (prefers-reduced-motion: reduce) {
      *, *::before, *::after { scroll-behavior: auto !important; animation-duration: .01ms !important; transition-duration: .01ms !important; }
    }
  </style>
</head>
<body>
  <main class="login-frame">
    <section class="welcome-panel" aria-label="Welcome">
      <div class="welcome-top">
        <div class="brand-lockup"><span class="brand-mark" aria-hidden="true">M</span><span>My App</span></div>
      </div>
      <div class="welcome-copy">
        <p class="welcome-kicker">A BETTER WAY TO STAY CONNECTED</p>
        <h1>Good to have you back.</h1>
        <p>Sign in to pick up right where you left off and keep everything in one place.</p>
      </div>
      <div class="welcome-foot">A simpler space for your everyday work.</div>
    </section>

    <section class="form-panel" aria-labelledby="loginHeading">
      <div class="auth-content">
        <p class="form-eyebrow">WELCOME BACK</p>
        <h2 id="loginHeading">Sign in</h2>
        <p class="auth-subtitle">Enter your account details to continue.</p>

        <?php if ($error): ?>
          <div class="alert alert-danger py-2" role="alert"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <?php if (isset($_GET['registered'])): ?>
          <div class="alert alert-success py-2" role="status">Account created! You can log in now.</div>
        <?php endif; ?>

        <form id="loginForm" method="post" novalidate>
          <div class="mb-3">
            <label class="form-label" for="username">Username or email</label>
            <div class="field-wrap">
              <i class="field-icon bi bi-person" aria-hidden="true"></i>
              <input id="username" type="text" name="username" class="form-control" autocomplete="username" required
                     value="<?= htmlspecialchars($_POST['username'] ?? '') ?>">
            </div>
          </div>
          <div class="mb-4">
            <label class="form-label" for="password">Password</label>
            <div class="field-wrap">
              <i class="field-icon bi bi-lock" aria-hidden="true"></i>
              <input id="password" type="password" name="password" class="form-control password-field" autocomplete="current-password" required>
              <button class="password-toggle" id="passwordToggle" type="button" aria-label="Show password" title="Show password">
                <i class="bi bi-eye" aria-hidden="true"></i>
              </button>
            </div>
          </div>
          <button class="btn btn-auth w-100" id="loginButton" type="submit">
            <span class="btn-spinner" aria-hidden="true"></span>
            <span class="button-label">Log in</span>
          </button>
        </form>

        <p class="auth-footer text-center mt-4 mb-0">No account? <a href="register.php">Register</a></p>
      </div>
    </section>
  </main>
  <script>
    const passwordField = document.getElementById('password');
    const passwordToggle = document.getElementById('passwordToggle');
    const loginForm = document.getElementById('loginForm');
    const loginButton = document.getElementById('loginButton');
    const buttonLabel = loginButton.querySelector('.button-label');

    passwordToggle.addEventListener('click', () => {
      const showPassword = passwordField.type === 'password';
      passwordField.type = showPassword ? 'text' : 'password';
      passwordToggle.setAttribute('aria-label', showPassword ? 'Hide password' : 'Show password');
      passwordToggle.title = showPassword ? 'Hide password' : 'Show password';
      passwordToggle.innerHTML = `<i class="bi ${showPassword ? 'bi-eye-slash' : 'bi-eye'}" aria-hidden="true"></i>`;
      passwordField.focus({ preventScroll: true });
    });

    loginForm.addEventListener('submit', () => {
      loginButton.disabled = true;
      loginButton.classList.add('is-loading');
      loginButton.setAttribute('aria-busy', 'true');
      buttonLabel.textContent = 'Signing in...';
    });
  </script>
</body>
</html>
