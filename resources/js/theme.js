// Jalankan sebelum render, biar gak ada flicker
function applyTheme() {
    const saved = localStorage.getItem('theme');
    const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
    const isDark = saved ? saved === 'dark' : prefersDark;

    document.documentElement.classList.toggle('dark', isDark);
    return isDark;
}

function toggleTheme() {
    const isDark = document.documentElement.classList.toggle('dark');
    localStorage.setItem('theme', isDark ? 'dark' : 'light');
    updateToggleIcon(isDark);
}

function updateToggleIcon(isDark) {
    const icon = document.getElementById('theme-icon');
    if (icon) {
        icon.textContent = isDark ? '🌙' : '☀️';
    }
}

document.addEventListener('DOMContentLoaded', () => {
    const isDark = applyTheme();
    updateToggleIcon(isDark);

    const btn = document.getElementById('theme-toggle');
    if (btn) {
        btn.addEventListener('click', toggleTheme);
    }
});

// Export untuk dipanggil inline script juga
window.applyTheme = applyTheme;
window.toggleTheme = toggleTheme;