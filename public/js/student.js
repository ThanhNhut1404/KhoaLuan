document.addEventListener('DOMContentLoaded', function() {
    const allowedThemes = ['blue', 'red', 'green', 'purple', 'cyan', 'orange'];
    const themeButtons = Array.from(document.querySelectorAll('.student-theme-dot[data-theme-value]'));
    let activeTheme = document.body.dataset.theme || 'blue';

    function setActiveTheme(theme) {
        if (!allowedThemes.includes(theme)) {
            theme = 'blue';
        }

        document.body.dataset.theme = theme;
        activeTheme = theme;

        themeButtons.forEach(function(button) {
            const isActive = button.dataset.themeValue === theme;
            button.classList.toggle('is-active', isActive);
            button.setAttribute('aria-pressed', isActive ? 'true' : 'false');
        });
    }

    function showThemeToast(message, type) {
        const oldToast = document.querySelector('.student-theme-toast');
        if (oldToast) {
            oldToast.remove();
        }

        const toast = document.createElement('div');
        toast.className = 'student-theme-toast ' + (type === 'error' ? 'error' : 'success');
        toast.textContent = message;
        document.body.appendChild(toast);

        window.setTimeout(function() {
            toast.classList.add('is-hiding');
            window.setTimeout(function() {
                toast.remove();
            }, 220);
        }, 1600);
    }

    themeButtons.forEach(function(button) {
        button.addEventListener('click', function() {
            const nextTheme = button.dataset.themeValue || 'blue';
            if (!allowedThemes.includes(nextTheme) || nextTheme === activeTheme) {
                return;
            }

            const previousTheme = activeTheme;
            setActiveTheme(nextTheme);

            fetch('/KhoaLuan/public/student.php?action=update_theme', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                credentials: 'same-origin',
                body: JSON.stringify({ theme_color: nextTheme })
            })
                .then(function(response) {
                    return response.json().then(function(data) {
                        if (!response.ok || !data.success) {
                            throw new Error(data.message || 'Không thể lưu màu giao diện.');
                        }
                        return data;
                    });
                })
                .then(function(data) {
                    setActiveTheme(data.theme_color || nextTheme);
                    showThemeToast(data.message || 'Đã đổi màu giao diện.', 'success');
                })
                .catch(function(error) {
                    setActiveTheme(previousTheme);
                    showThemeToast(error.message || 'Không thể lưu màu giao diện.', 'error');
                });
        });
    });

    setActiveTheme(activeTheme);
});
