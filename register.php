<?php
require 'db.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullname = trim($_POST['fullname'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm  = $_POST['confirm'] ?? '';

    if ($fullname === '' || $username === '' || $email === '' || $password === '') {
        $errors[] = 'All fields are required.';
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Please enter a valid email address.';
    }
    if (strlen($password) < 6) {
        $errors[] = 'Password must be at least 6 characters.';
    }
    if ($password !== $confirm) {
        $errors[] = 'Passwords do not match.';
    }

    if (!$errors) {
        $stmt = $pdo->prepare('SELECT id FROM users WHERE username = ? OR email = ?');
        $stmt->execute([$username, $email]);
        if ($stmt->fetch()) {
            $errors[] = 'Username or email is already taken.';
        }
    }

    if (!$errors) {
        $stmt = $pdo->prepare('INSERT INTO users (fullname, username, email, password) VALUES (?, ?, ?, ?)');
        $stmt->execute([$fullname, $username, $email, password_hash($password, PASSWORD_DEFAULT)]);
        header('Location: index.php?registered=1');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Register</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
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
    body {
      min-height: 100vh;
      padding: 24px;
      font-family: 'Poppins', sans-serif;
      color: var(--ink);
      background: linear-gradient(125deg, #123548 0%, #176b70 48%, #d19a64 100%);
      background-attachment: fixed;
    }
    .auth-shell { width: 100%; max-width: 500px; margin: auto; animation: arrive .65s ease both; }
    .brand-mark {
      display: grid;
      width: 48px;
      height: 48px;
      margin: 0 auto 20px;
      place-items: center;
      border: 1px solid rgba(255,255,255,.52);
      border-radius: 16px;
      background: rgba(255,255,255,.19);
      color: #fff;
      font-size: 1.2rem;
      font-weight: 700;
      box-shadow: 0 10px 30px rgba(9,35,47,.18);
    }
    .auth-card {
      border: 1px solid rgba(255,255,255,.58);
      border-radius: 24px;
      background: rgba(255,255,255,.88);
      box-shadow: 0 24px 70px rgba(8,35,45,.24);
      backdrop-filter: blur(18px);
      -webkit-backdrop-filter: blur(18px);
    }
    .auth-card h1 { color: var(--ink); font-size: 1.65rem; font-weight: 600; letter-spacing: 0; }
    .auth-subtitle { color: var(--muted); font-size: .92rem; }
    .form-label { margin-bottom: 8px; color: #35536a; font-size: .84rem; font-weight: 500; }
    .form-control {
      min-height: 48px;
      border: 1px solid #d9e3e8;
      border-radius: 12px;
      background: rgba(255,255,255,.78);
      color: var(--ink);
      font-size: .92rem;
      transition: border-color .2s ease, box-shadow .2s ease, background .2s ease;
    }
    .form-control:focus { border-color: #41a7a3; background: #fff; box-shadow: 0 0 0 4px rgba(8,127,140,.12); }
    .btn-auth {
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
    .auth-footer { color: var(--muted); font-size: .88rem; }
    .auth-footer a { color: var(--teal-dark); font-weight: 600; text-decoration: none; }
    .auth-footer a:hover { text-decoration: underline; }
    .alert { border: 0; border-radius: 12px; font-size: .86rem; }
    @keyframes arrive { from { opacity: 0; transform: translateY(14px); } to { opacity: 1; transform: translateY(0); } }
    @media (max-width: 480px) {
      body { padding: 18px; }
      .auth-card { padding: 1.5rem !important; border-radius: 20px; }
    }
    @media (prefers-reduced-motion: reduce) {
      *, *::before, *::after { scroll-behavior: auto !important; animation-duration: .01ms !important; transition-duration: .01ms !important; }
    }
  </style>
</head>
<body class="d-flex align-items-center justify-content-center py-4">
  <main class="auth-shell">
    <div class="brand-mark" aria-hidden="true">M</div>
    <section class="auth-card p-4 p-sm-5">
    <h1 class="text-center mb-2">Create account</h1>
    <p class="auth-subtitle text-center mb-4">It only takes a minute</p>

    <?php foreach ($errors as $e): ?>
      <div class="alert alert-danger py-2"><?= htmlspecialchars($e) ?></div>
    <?php endforeach; ?>

    <form method="post" novalidate>
      <div class="mb-3">
        <label class="form-label">Full name</label>
        <input type="text" name="fullname" class="form-control" required value="<?= htmlspecialchars($_POST['fullname'] ?? '') ?>">
      </div>
      <div class="mb-3">
        <label class="form-label">Username</label>
        <input type="text" name="username" class="form-control" required value="<?= htmlspecialchars($_POST['username'] ?? '') ?>">
      </div>
      <div class="mb-3">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
      </div>
      <div class="mb-3">
        <label class="form-label">Password</label>
        <input type="password" name="password" class="form-control" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Confirm password</label>
        <input type="password" name="confirm" class="form-control" required>
      </div>
      <button class="btn btn-auth w-100">Register</button>
    </form>

    <p class="auth-footer text-center mt-4 mb-0">Already have an account? <a href="index.php">Log in</a></p>
    </section>
  </main>
</body>
</html>
