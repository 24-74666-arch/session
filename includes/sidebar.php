<aside class="sidebar">
  <a class="brand-lockup" href="dashboard.php">
    <span class="brand-symbol" aria-hidden="true">M</span><span>My App</span>
  </a>
  <p class="sidebar-label">Workspace</p>
  <nav class="sidebar-nav" aria-label="Main navigation">
    <a class="sidebar-link<?= ($activePage ?? '') === 'dashboard' ? ' active' : '' ?>" href="dashboard.php"><i class="bi bi-grid-1x2-fill" aria-hidden="true"></i><span>Dashboard</span></a>
    <a class="sidebar-link<?= ($activePage ?? '') === 'profile' ? ' active' : '' ?>" href="profile.php"><i class="bi bi-person" aria-hidden="true"></i><span>Profile</span></a>
    <a class="sidebar-link<?= ($activePage ?? '') === 'settings' ? ' active' : '' ?>" href="settings.php"><i class="bi bi-sliders" aria-hidden="true"></i><span>Settings</span></a>
    <a class="sidebar-link logout-link" href="logout.php"><i class="bi bi-box-arrow-right" aria-hidden="true"></i><span>Logout</span></a>
  </nav>
  <a class="sidebar-account text-decoration-none" href="profile.php">
    <span class="avatar" aria-hidden="true"><?= htmlspecialchars($initials) ?></span>
    <span><span class="sidebar-account-name d-block"><?= htmlspecialchars($user['fullname']) ?></span><span class="sidebar-account-handle d-block">@<?= htmlspecialchars($user['username']) ?></span></span>
  </a>
</aside>
