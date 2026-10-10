<?php
require __DIR__ . '/includes/auth.php';

$totalUsers = (int)$pdo->query('SELECT COUNT(*) FROM users')->fetchColumn();
$searchInput = $_GET['search'] ?? '';
$search = is_string($searchInput) ? trim($searchInput) : '';
$pageInput = $_GET['page'] ?? null;
$pageValue = is_scalar($pageInput) ? filter_var($pageInput, FILTER_VALIDATE_INT) : false;
$page = max(1, $pageValue === false ? 1 : (int)$pageValue);
$perPage = 8;
$filterSql = '';
$filterParams = [];

if ($search !== '') {
    $filterSql = ' WHERE fullname LIKE ? OR username LIKE ? OR email LIKE ?';
    $searchTerm = '%' . $search . '%';
    $filterParams = [$searchTerm, $searchTerm, $searchTerm];
}

$countStmt = $pdo->prepare('SELECT COUNT(*) FROM users' . $filterSql);
$countStmt->execute($filterParams);
$matchingUsers = (int)$countStmt->fetchColumn();
$totalPages = max(1, (int)ceil($matchingUsers / $perPage));
$page = min($page, $totalPages);
$offset = ($page - 1) * $perPage;

$membersStmt = $pdo->prepare(
    'SELECT id, fullname, username, email, created_at FROM users' . $filterSql .
    ' ORDER BY created_at DESC, id DESC LIMIT ? OFFSET ?'
);
$parameterIndex = 1;
foreach ($filterParams as $filterParam) {
    $membersStmt->bindValue($parameterIndex, $filterParam, PDO::PARAM_STR);
    $parameterIndex++;
}
$membersStmt->bindValue($parameterIndex, $perPage, PDO::PARAM_INT);
$membersStmt->bindValue($parameterIndex + 1, $offset, PDO::PARAM_INT);
$membersStmt->execute();
$members = $membersStmt->fetchAll();

$firstResult = $matchingUsers > 0 ? $offset + 1 : 0;
$lastResult = min($offset + $perPage, $matchingUsers);
$startPage = max(1, $page - 2);
$endPage = min($totalPages, $page + 2);
$pageTitle = 'Dashboard';
$pageSubtitle = 'Account overview';
$activePage = 'dashboard';
require __DIR__ . '/includes/header.php';
?>
<section class="page-section">
  <div class="welcome-panel">
    <p class="welcome-kicker">YOUR ACCOUNT</p>
    <h1>Welcome, <?= htmlspecialchars($user['fullname']) ?></h1>
    <p>Signed in as <strong>@<?= htmlspecialchars($user['username']) ?></strong></p>
  </div>
  <div class="section-heading"><div><h2 class="section-title">At a glance</h2><p class="section-description">A quick overview of your account and community.</p></div></div>
  <div class="row g-3 mb-4">
    <div class="col-md-4"><article class="stat-card stat-card-blue h-100"><div><p class="stat-label">Total users</p><p class="stat-value"><?= $totalUsers ?></p></div><span class="stat-icon"><i class="bi bi-people" aria-hidden="true"></i></span></article></div>
    <div class="col-md-4"><article class="stat-card stat-card-warm h-100"><div><p class="stat-label">Search matches</p><p class="stat-value"><?= $matchingUsers ?></p></div><span class="stat-icon"><i class="bi bi-search" aria-hidden="true"></i></span></article></div>
    <div class="col-md-4"><article class="stat-card h-100"><div><p class="stat-label">Member since</p><p class="stat-value"><?= date('M d, Y', strtotime($user['created_at'])) ?></p></div><span class="stat-icon"><i class="bi bi-calendar3" aria-hidden="true"></i></span></article></div>
  </div>
  <section class="content-panel" aria-labelledby="usersHeading">
    <div class="panel-heading">
      <div><h2 id="usersHeading">Users</h2><p>Browse and search registered accounts.</p></div>
      <form class="search-form" method="get" action="dashboard.php" role="search">
        <label class="visually-hidden" for="userSearch">Search users</label>
        <input class="search-field" id="userSearch" type="search" name="search" value="<?= htmlspecialchars($search, ENT_QUOTES, 'UTF-8') ?>" placeholder="Search users...">
        <?php if ($search !== ''): ?><a class="clear-search" href="dashboard.php" aria-label="Clear search" title="Clear search"><i class="bi bi-x-lg" aria-hidden="true"></i></a><?php endif; ?>
        <button class="search-button" type="submit" aria-label="Search"><i class="bi bi-search" aria-hidden="true"></i></button>
      </form>
    </div>
    <?php if ($members): ?>
      <div class="table-scroll"><table class="users-table">
        <thead><tr><th scope="col">User</th><th scope="col">Email</th><th scope="col">Username</th><th scope="col">Joined</th></tr></thead>
        <tbody><?php foreach ($members as $member): ?><tr>
          <td><span class="user-cell-name"><?= htmlspecialchars($member['fullname']) ?></span><span class="user-cell-handle">ID #<?= (int)$member['id'] ?></span></td>
          <td><?= htmlspecialchars($member['email']) ?></td><td>@<?= htmlspecialchars($member['username']) ?></td>
          <td><?= date('M d, Y', strtotime($member['created_at'])) ?></td>
        </tr><?php endforeach; ?></tbody>
      </table></div>
      <div class="pagination-row">
        <p class="result-count">Showing <?= $firstResult ?>&ndash;<?= $lastResult ?> of <?= $matchingUsers ?> users</p>
        <?php if ($totalPages > 1): ?><nav aria-label="Users pagination"><ul class="pagination">
          <li><a class="page-link<?= $page <= 1 ? ' disabled' : '' ?>" href="dashboard.php?<?= htmlspecialchars(http_build_query(['search' => $search, 'page' => max(1, $page - 1)]), ENT_QUOTES, 'UTF-8') ?>" aria-label="Previous page"><i class="bi bi-chevron-left" aria-hidden="true"></i></a></li>
          <?php for ($pageNumber = $startPage; $pageNumber <= $endPage; $pageNumber++): ?><li><a class="page-link<?= $pageNumber === $page ? ' current' : '' ?>" href="dashboard.php?<?= htmlspecialchars(http_build_query(['search' => $search, 'page' => $pageNumber]), ENT_QUOTES, 'UTF-8') ?>"<?= $pageNumber === $page ? ' aria-current="page"' : '' ?>><?= $pageNumber ?></a></li><?php endfor; ?>
          <li><a class="page-link<?= $page >= $totalPages ? ' disabled' : '' ?>" href="dashboard.php?<?= htmlspecialchars(http_build_query(['search' => $search, 'page' => min($totalPages, $page + 1)]), ENT_QUOTES, 'UTF-8') ?>" aria-label="Next page"><i class="bi bi-chevron-right" aria-hidden="true"></i></a></li>
        </ul></nav><?php endif; ?>
      </div>
    <?php else: ?><div class="empty-state">No users match your search.</div><?php endif; ?>
  </section>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>
