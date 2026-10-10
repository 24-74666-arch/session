<?php
require __DIR__ . '/includes/auth.php';

$profileMessage = '';
$profileMessageType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $submittedToken = $_POST['csrf_token'] ?? '';

  if (!is_string($submittedToken) || !hash_equals($_SESSION['csrf_token'], $submittedToken)) {
    $profileMessage = 'Your session expired. Please try again.';
    $profileMessageType = 'error';
  } else {
    $fullname = trim($_POST['fullname'] ?? '');
    $email = trim($_POST['email'] ?? '');

    if ($fullname === '' || $email === '') {
        $profileMessage = 'Full name and email are required.';
        $profileMessageType = 'error';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $profileMessage = 'Please enter a valid email address.';
        $profileMessageType = 'error';
    } else {
        $duplicateStmt = $pdo->prepare('SELECT id FROM users WHERE email = ? AND id <> ?');
        $duplicateStmt->execute([$email, $_SESSION['user_id']]);
        if ($duplicateStmt->fetch()) {
            $profileMessage = 'That email address is already in use.';
            $profileMessageType = 'error';
        } else {
            $updateStmt = $pdo->prepare('UPDATE users SET fullname = ?, email = ? WHERE id = ?');
            $updateStmt->execute([$fullname, $email, $_SESSION['user_id']]);
            $user['fullname'] = $fullname;
            $user['email'] = $email;
            $_SESSION['fullname'] = $fullname;
            $profileMessage = 'Your profile has been updated.';
            $profileMessageType = 'success';
        }
          }
    }
}

$pageTitle = 'Profile';
$pageSubtitle = 'Your account details';
$activePage = 'profile';
require __DIR__ . '/includes/header.php';
?>
<section class="page-section">
  <div class="section-heading"><div><h2 class="section-title">Profile</h2><p class="section-description">Your account details.</p></div></div>
  <section class="content-panel">
    <div class="panel-heading"><div><h2>Personal information</h2><p>Details associated with your account.</p></div></div>
    <div class="profile-grid">
      <div class="profile-field"><p class="profile-field-label">Full name</p><p class="profile-field-value"><?= htmlspecialchars($user['fullname']) ?></p></div>
      <div class="profile-field"><p class="profile-field-label">Username</p><p class="profile-field-value">@<?= htmlspecialchars($user['username']) ?></p></div>
      <div class="profile-field"><p class="profile-field-label">Email address</p><p class="profile-field-value"><?= htmlspecialchars($user['email']) ?></p></div>
      <div class="profile-field"><p class="profile-field-label">Member since</p><p class="profile-field-value"><?= date('M d, Y', strtotime($user['created_at'])) ?></p></div>
    </div>
  </section>
</section>
<section class="page-section">
  <div class="section-heading"><div><h2 class="section-title">Edit profile</h2><p class="section-description">Update your name and email address.</p></div></div>
  <section class="content-panel" aria-labelledby="editProfileHeading">
    <div class="panel-heading"><div><h2 id="editProfileHeading">Account details</h2><p>Your username cannot be changed here.</p></div></div>
    <?php if ($profileMessage !== ''): ?><p class="form-message <?= $profileMessageType ?>" role="status"><?= htmlspecialchars($profileMessage) ?></p><?php endif; ?>
    <form class="form-stack" method="post">
      <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8') ?>">
      <div><label class="form-label" for="fullname">Full name</label><input class="form-field" id="fullname" name="fullname" type="text" maxlength="100" required value="<?= htmlspecialchars($user['fullname'], ENT_QUOTES, 'UTF-8') ?>"></div>
      <div><label class="form-label" for="email">Email</label><input class="form-field" id="email" name="email" type="email" maxlength="100" required value="<?= htmlspecialchars($user['email'], ENT_QUOTES, 'UTF-8') ?>"></div>
      <div class="form-actions"><button class="form-submit" type="submit">Save changes</button></div>
    </form>
  </section>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>
