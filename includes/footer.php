  </main>
  </div>
</div>
<script>
  const themeToggle = document.getElementById('themeToggle');
  const themeSwitch = document.getElementById('themeSwitch');
  const root = document.documentElement;

  function setTheme(theme) {
    const isDark = theme === 'dark';
    root.dataset.theme = isDark ? 'dark' : 'light';
    themeToggle.innerHTML = `<i class="bi ${isDark ? 'bi-sun' : 'bi-moon-stars'}" aria-hidden="true"></i>`;
    themeToggle.setAttribute('aria-label', `Switch to ${isDark ? 'light' : 'dark'} mode`);
    themeToggle.title = `Switch to ${isDark ? 'light' : 'dark'} mode`;
    if (themeSwitch) themeSwitch.setAttribute('aria-checked', String(isDark));
    try {
      localStorage.setItem('dashboard-theme', root.dataset.theme);
    } catch (error) {
    }
  }

  function toggleTheme() {
    setTheme(root.dataset.theme === 'dark' ? 'light' : 'dark');
  }

  themeToggle.addEventListener('click', toggleTheme);
  if (themeSwitch) themeSwitch.addEventListener('click', toggleTheme);
  setTheme(root.dataset.theme || 'light');
</script>
</body>
</html>
