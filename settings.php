<?php
require __DIR__ . '/includes/auth.php';

$passwordMessage = '';
$passwordMessageType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $submittedToken = $_POST['csrf_token'] ?? '';

  if (!is_string($submittedToken) || !hash_equals($_SESSION['csrf_token'], $submittedToken)) {
    $passwordMessage = 'Your session expired. Please try again.';
    $passwordMessageType = 'error';
  } else {
    $oldPassword = $_POST['old_password'] ?? '';
    $newPassword = $_POST['new_password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    if (!is_string($oldPassword) || !password_verify($oldPassword, $user['password'])) {
        $passwordMessage = 'The old password is incorrect.';
        $passwordMessageType = 'error';
    } elseif (!is_string($newPassword) || strlen($newPassword) < 6) {
        $passwordMessage = 'The new password must be at least 6 characters.';
        $passwordMessageType = 'error';
    } elseif (!is_string($confirmPassword) || $newPassword !== $confirmPassword) {
        $passwordMessage = 'The new passwords do not match.';
        $passwordMessageType = 'error';
    } else {
        $passwordHash = password_hash($newPassword, PASSWORD_DEFAULT);
        $updateStmt = $pdo->prepare('UPDATE users SET password = ? WHERE id = ?');
        $updateStmt->execute([$passwordHash, $_SESSION['user_id']]);
        $user['password'] = $passwordHash;
        $passwordMessage = 'Your password has been changed.';
        $passwordMessageType = 'success';
        }
    }
}

$pageTitle = 'Settings';
$pageSubtitle = 'Manage your preferences';
$activePage = 'settings';
require __DIR__ . '/includes/header.php';
?>
<section class="page-section">
  <div class="section-heading"><div><h2 class="section-title">Settings</h2><p class="section-description">Manage your dashboard appearance.</p></div></div>
  <section class="content-panel">
    <div class="panel-heading"><div><h2>Appearance</h2><p>Choose how the dashboard looks on this device.</p></div></div>
    <div class="settings-row">
      <div><p class="settings-title">Dark mode</p><p class="settings-description">Your preference is saved in this browser.</p></div>
      <button class="theme-switch" id="themeSwitch" type="button" role="switch" aria-checked="false" aria-label="Dark mode"></button>
    </div>
  </section>
</section>
<section class="page-section">
  <div class="section-heading"><div><h2 class="section-title">Change password</h2><p class="section-description">Verify your current password before choosing a new one.</p></div></div>
  <section class="content-panel" aria-labelledby="changePasswordHeading">
    <div class="panel-heading"><div><h2 id="changePasswordHeading">Password</h2><p>Use at least 6 characters for your new password.</p></div></div>
    <?php if ($passwordMessage !== ''): ?><p class="form-message <?= $passwordMessageType ?>" role="status"><?= htmlspecialchars($passwordMessage) ?></p><?php endif; ?>
    <form class="form-stack" method="post">
      <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8') ?>">
      <div><label class="form-label" for="oldPassword">Old password</label><input class="form-field" id="oldPassword" name="old_password" type="password" autocomplete="current-password" required></div>
      <div><label class="form-label" for="newPassword">New password</label><input class="form-field" id="newPassword" name="new_password" type="password" autocomplete="new-password" minlength="6" required></div>
      <div><label class="form-label" for="confirmPassword">Confirm new password</label><input class="form-field" id="confirmPassword" name="confirm_password" type="password" autocomplete="new-password" minlength="6" required></div>
      <div class="form-actions"><button class="form-submit" type="submit">Update password</button></div>
    </form>
  </section>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>
