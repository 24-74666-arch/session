<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= htmlspecialchars($pageTitle ?? 'Workspace') ?> | My App</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
  <script>
    try {
      document.documentElement.dataset.theme = localStorage.getItem('dashboard-theme') || 'light';
    } catch (error) {
      document.documentElement.dataset.theme = 'light';
    }
  </script>
  <style>
    :root { color-scheme: light; --page:#f2f6f5; --surface:#fff; --surface-soft:#f7faf9; --ink:#19364a; --muted:#718493; --teal:#087f8c; --teal-soft:#e3f3f0; --line:#e2eae9; --shadow:0 10px 28px rgba(26,65,76,.06); }
    :root[data-theme="dark"] { color-scheme:dark; --page:#111d22; --surface:#1b2a30; --surface-soft:#22343b; --ink:#e5eff0; --muted:#a4b6bc; --teal:#5bc3b6; --teal-soft:#203f42; --line:#34484e; --shadow:0 12px 32px rgba(0,0,0,.18); }
    * { box-sizing:border-box; }
    html { scroll-behavior:smooth; }
    body { min-height:100vh; margin:0; background:var(--page); color:var(--ink); font-family:'Poppins',sans-serif; transition:background .2s ease,color .2s ease; }
    button,input { font:inherit; }
    .app-layout { min-height:100vh; }
    .sidebar { position:fixed; inset:0 auto 0 0; z-index:10; display:flex; width:248px; flex-direction:column; padding:25px 16px 18px; border-right:1px solid var(--line); background:var(--surface); }
    .brand-lockup { display:flex; align-items:center; gap:11px; padding:0 10px; color:var(--ink); font-size:1.05rem; font-weight:600; text-decoration:none; }
    .brand-symbol { display:grid; width:40px; height:40px; place-items:center; border-radius:13px; background:linear-gradient(145deg,#087f8c,#159b91); color:#fff; font-weight:700; box-shadow:0 7px 16px rgba(8,127,140,.18); }
    .sidebar-label { margin:38px 10px 12px; color:var(--muted); font-size:.7rem; font-weight:600; text-transform:uppercase; }
    .sidebar-nav { display:grid; gap:6px; }
    .sidebar-link { display:flex; align-items:center; gap:13px; min-height:46px; padding:0 12px; border-radius:11px; color:var(--muted); font-size:.86rem; font-weight:500; text-decoration:none; transition:color .18s ease,background .18s ease,transform .18s ease; }
    .sidebar-link i { width:19px; font-size:1.05rem; text-align:center; }
    .sidebar-link:hover { transform:translateX(2px); background:var(--surface-soft); color:var(--ink); }
    .sidebar-link.active { background:var(--teal-soft); color:var(--teal); font-weight:600; }
    .sidebar-link.logout-link { margin-top:8px; color:#bb5b50; }
    .sidebar-link.logout-link:hover { background:rgba(196,82,68,.1); color:#a94137; }
    .sidebar-account { display:flex; align-items:center; gap:11px; margin-top:auto; padding:15px 9px 2px; border-top:1px solid var(--line); }
    .sidebar-account-name { min-width:0; color:var(--ink); font-size:.8rem; font-weight:600; overflow-wrap:anywhere; }
    .sidebar-account-handle { color:var(--muted); font-size:.72rem; }
    .main-area { min-width:0; margin-left:248px; }
    .topbar { position:sticky; top:0; z-index:5; display:flex; min-height:76px; align-items:center; justify-content:space-between; gap:16px; padding:12px clamp(20px,4vw,48px); border-bottom:1px solid var(--line); background:var(--surface); }
    .topbar-title { margin:0; color:var(--ink); font-size:.95rem; font-weight:600; }
    .topbar-subtitle { margin:3px 0 0; color:var(--muted); font-size:.75rem; }
    .topbar-actions { display:flex; align-items:center; gap:14px; }
    .theme-toggle { display:grid; width:40px; height:40px; place-items:center; border:1px solid var(--line); border-radius:12px; background:var(--surface); color:var(--ink); font-size:1.05rem; transition:background .2s ease,transform .2s ease; }
    .theme-toggle:hover { transform:translateY(-1px); background:var(--surface-soft); }
    .topbar-user { display:flex; align-items:center; gap:10px; }
    .topbar-user-name { color:var(--ink); font-size:.81rem; font-weight:500; }
    .avatar { display:grid; width:40px; height:40px; flex:0 0 40px; place-items:center; border:1px solid rgba(255,255,255,.55); border-radius:50%; background:linear-gradient(145deg,#087f8c,#d19a64); color:#fff; font-size:.78rem; font-weight:600; box-shadow:0 5px 14px rgba(8,127,140,.17); }
    .content-wrap { width:min(100%,1440px); margin:0 auto; padding:32px clamp(20px,4vw,48px) 54px; }
    .page-section { margin-bottom:34px; scroll-margin-top:100px; }
    .welcome-panel { position:relative; overflow:hidden; margin-bottom:24px; padding:34px 38px; border:1px solid rgba(255,255,255,.24); border-radius:19px; background:linear-gradient(112deg,#104b5b 0%,#087f8c 68%,#ba8154 125%); color:#fff; box-shadow:0 16px 38px rgba(19,73,83,.16); animation:arrive .5s ease both; }
    .welcome-panel::after { position:absolute; inset:0 0 0 auto; width:35%; background:linear-gradient(135deg,transparent,rgba(255,255,255,.08)); content:''; pointer-events:none; }
    .welcome-kicker { margin-bottom:8px; color:rgba(255,255,255,.72); font-size:.75rem; font-weight:500; }
    .welcome-panel h1 { position:relative; z-index:1; margin-bottom:8px; font-size:1.75rem; font-weight:600; overflow-wrap:anywhere; }
    .welcome-panel p { position:relative; z-index:1; margin:0; color:rgba(255,255,255,.78); font-size:.9rem; }
    .welcome-panel strong { color:#fff; font-weight:600; }
    .section-heading { display:flex; align-items:end; justify-content:space-between; gap:16px; margin-bottom:16px; }
    .section-title { margin:0; color:var(--ink); font-size:1rem; font-weight:600; }
    .section-description { margin:4px 0 0; color:var(--muted); font-size:.78rem; }
    .stat-card,.content-panel { border:1px solid var(--line); border-radius:15px; background:var(--surface); box-shadow:var(--shadow); }
    .stat-card { position:relative; display:flex; min-height:132px; align-items:center; justify-content:space-between; gap:15px; overflow:hidden; padding:22px; transition:transform .2s ease,box-shadow .2s ease; }
    .stat-card::before { position:absolute; inset:0 auto 0 0; width:3px; background:var(--stat-accent,#087f8c); content:''; }
    .stat-card:hover { transform:translateY(-3px); box-shadow:0 14px 32px rgba(26,65,76,.11); }
    .stat-card-warm { --stat-accent:#d19a64; } .stat-card-blue { --stat-accent:#6c8fbd; }
    .stat-label { margin-bottom:9px; color:var(--muted); font-size:.78rem; font-weight:500; }
    .stat-value { margin:0; color:var(--ink); font-size:1.35rem; font-weight:600; overflow-wrap:anywhere; }
    .stat-icon { display:grid; width:46px; height:46px; flex:0 0 46px; place-items:center; border-radius:14px; background:var(--teal-soft); color:var(--teal); font-size:1.2rem; }
    .stat-card-warm .stat-icon { background:rgba(209,154,100,.16); color:#ad713c; } .stat-card-blue .stat-icon { background:rgba(108,143,189,.16); color:#597cac; }
    [data-theme="dark"] .stat-card-warm .stat-icon { color:#e3ad76; } [data-theme="dark"] .stat-card-blue .stat-icon { color:#9bb9e1; }
    .content-panel { overflow:hidden; }
    .panel-heading { display:flex; align-items:center; justify-content:space-between; gap:16px; padding:20px 22px; border-bottom:1px solid var(--line); }
    .panel-heading h2 { margin:0; color:var(--ink); font-size:.95rem; font-weight:600; }
    .panel-heading p { margin:4px 0 0; color:var(--muted); font-size:.75rem; }
    .search-form { display:flex; width:min(100%,340px); gap:8px; }
    .search-field,.form-field { min-width:0; min-height:40px; flex:1; padding:8px 12px; border:1px solid var(--line); border-radius:10px; outline:none; background:var(--surface-soft); color:var(--ink); font-size:.8rem; }
    .search-field:focus,.form-field:focus { border-color:var(--teal); box-shadow:0 0 0 3px color-mix(in srgb,var(--teal) 15%,transparent); }
    .form-field { width:100%; min-height:46px; font-size:.84rem; }
    .form-label { display:block; margin:0 0 7px; color:var(--muted); font-size:.76rem; font-weight:500; }
    .form-stack { display:grid; gap:16px; padding:22px; }
    .form-actions { display:flex; align-items:center; gap:12px; }
    .form-submit { min-height:42px; padding:0 17px; border:0; border-radius:10px; background:var(--teal); color:#fff; font-size:.82rem; font-weight:600; }
    .form-submit:hover { filter:brightness(.94); }
    .form-message { margin:18px 22px 0; padding:11px 14px; border-radius:10px; font-size:.8rem; }
    .form-message.success { background:var(--teal-soft); color:var(--teal); }
    .form-message.error { background:rgba(196,82,68,.12); color:#bb5b50; }
    .search-button { display:grid; width:42px; flex:0 0 42px; place-items:center; border:0; border-radius:10px; background:var(--teal); color:#fff; transition:filter .2s ease,transform .2s ease; }
    .search-button:hover { transform:translateY(-1px); filter:brightness(.94); }
    .clear-search { display:grid; width:40px; flex:0 0 40px; place-items:center; border:1px solid var(--line); border-radius:10px; color:var(--muted); text-decoration:none; }
    .clear-search:hover { background:var(--surface-soft); color:var(--ink); }
    .table-scroll { overflow-x:auto; }
    .users-table { width:100%; min-width:720px; border-collapse:collapse; }
    .users-table th { padding:13px 22px; background:var(--surface-soft); color:var(--muted); font-size:.7rem; font-weight:600; text-align:left; white-space:nowrap; }
    .users-table td { padding:15px 22px; border-top:1px solid var(--line); color:var(--ink); font-size:.8rem; vertical-align:middle; }
    .users-table tbody tr { transition:background .15s ease; } .users-table tbody tr:hover { background:var(--surface-soft); }
    .user-cell-name { font-weight:500; } .user-cell-handle { display:block; margin-top:3px; color:var(--muted); font-size:.72rem; }
    .pagination-row { display:flex; align-items:center; justify-content:space-between; gap:16px; padding:15px 22px; border-top:1px solid var(--line); }
    .result-count { margin:0; color:var(--muted); font-size:.74rem; }
    .pagination { display:flex; flex-wrap:wrap; gap:5px; margin:0; padding:0; list-style:none; }
    .page-link { display:grid; min-width:34px; height:34px; place-items:center; padding:0 9px; border:1px solid var(--line); border-radius:9px; background:var(--surface); color:var(--muted); font-size:.76rem; text-decoration:none; }
    .page-link:hover { background:var(--surface-soft); color:var(--ink); } .page-link.current { border-color:var(--teal); background:var(--teal); color:#fff; }
    .page-link.disabled { opacity:.45; pointer-events:none; } .empty-state { padding:42px 20px; color:var(--muted); font-size:.84rem; text-align:center; }
    .profile-grid { display:grid; grid-template-columns:repeat(2,minmax(0,1fr)); gap:14px; padding:22px; }
    .profile-field { min-width:0; padding:16px; border:1px solid var(--line); border-radius:12px; background:var(--surface-soft); }
    .profile-field-label { margin-bottom:7px; color:var(--muted); font-size:.72rem; }
    .profile-field-value { margin:0; color:var(--ink); font-size:.88rem; font-weight:500; overflow-wrap:anywhere; }
    .settings-row { display:flex; align-items:center; justify-content:space-between; gap:20px; padding:21px 22px; }
    .settings-title { margin:0 0 4px; color:var(--ink); font-size:.86rem; font-weight:500; }
    .settings-description { margin:0; color:var(--muted); font-size:.75rem; }
    .theme-switch { position:relative; display:inline-flex; width:48px; height:27px; flex:0 0 48px; align-items:center; padding:3px; border:0; border-radius:20px; background:#bdc9cc; cursor:pointer; transition:background .2s ease; }
    .theme-switch::after { width:21px; height:21px; border-radius:50%; background:#fff; box-shadow:0 1px 4px rgba(0,0,0,.2); content:''; transition:transform .2s ease; }
    :root[data-theme="dark"] .theme-switch { background:var(--teal); } :root[data-theme="dark"] .theme-switch::after { transform:translateX(21px); }
    @keyframes arrive { from { opacity:0; transform:translateY(12px); } to { opacity:1; transform:translateY(0); } }
    @media (max-width:900px) { .sidebar { position:sticky; inset:auto; width:100%; padding:12px 18px; border-right:0; border-bottom:1px solid var(--line); } .sidebar .brand-lockup { padding:0 2px 12px; } .sidebar-label,.sidebar-account { display:none; } .sidebar-nav { display:flex; gap:6px; overflow-x:auto; } .sidebar-link { min-height:40px; flex:0 0 auto; padding:0 11px; font-size:.78rem; } .sidebar-link.logout-link { margin-top:0; } .main-area { margin-left:0; } .topbar { min-height:68px; } }
    @media (max-width:575px) { .topbar { padding-right:17px; padding-left:17px; } .topbar-user-name { display:none; } .content-wrap { padding:22px 16px 38px; } .welcome-panel { padding:27px 22px; border-radius:16px; } .welcome-panel h1 { font-size:1.42rem; } .section-heading,.panel-heading { align-items:flex-start; flex-direction:column; } .search-form { width:100%; } .pagination-row { align-items:flex-start; flex-direction:column; padding:14px 16px; } .profile-grid { grid-template-columns:1fr; padding:16px; } .settings-row { align-items:flex-start; padding:18px 16px; } }
    @media (prefers-reduced-motion:reduce) { *,*::before,*::after { scroll-behavior:auto!important; animation-duration:.01ms!important; transition-duration:.01ms!important; } }
  </style>
</head>
<body>
<div class="app-layout">
  <?php require __DIR__ . '/sidebar.php'; ?>
  <div class="main-area">
    <header class="topbar">
      <div><h1 class="topbar-title"><?= htmlspecialchars($pageTitle ?? 'Workspace') ?></h1><p class="topbar-subtitle"><?= htmlspecialchars($pageSubtitle ?? 'Account overview') ?></p></div>
      <div class="topbar-actions">
        <button class="theme-toggle" id="themeToggle" type="button" aria-label="Switch to dark mode" title="Switch to dark mode"><i class="bi bi-moon-stars" aria-hidden="true"></i></button>
        <div class="topbar-user"><span class="topbar-user-name"><?= htmlspecialchars($user['fullname']) ?></span><span class="avatar" aria-hidden="true"><?= htmlspecialchars($initials) ?></span></div>
      </div>
    </header>
    <main class="content-wrap">
