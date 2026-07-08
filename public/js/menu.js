// Expose a fallback only when the layout has not already registered toggleMenu.
if (typeof window.toggleMenu !== 'function') {
    window.toggleMenu = function() {
        var sidebar = document.getElementById('sidebar');
        if (!sidebar) return;

        if (typeof window.setSidebarState === 'function') {
            window.setSidebarState(!sidebar.classList.contains('active'));
            return;
        }

        sidebar.classList.toggle('active');
    };
}

document.addEventListener('DOMContentLoaded', function() {
    // Accordion behavior for sidebar: only one submenu open at a time
    var menu = document.querySelector('.sidebar .sidebar-menu');
    if (!menu) return;

    var anchors = menu.querySelectorAll('li.has-submenu > a');
    anchors.forEach(function(anchor){
        // remove inline onclick to avoid conflicts
        if (anchor.hasAttribute('onclick')) anchor.removeAttribute('onclick');

        anchor.addEventListener('click', function(e){
            e.preventDefault();
            var li = anchor.parentElement;
            var wasOpen = li.classList.contains('open');

            // close others
            menu.querySelectorAll('li.has-submenu.open').forEach(function(item){
                if (item !== li) item.classList.remove('open');
            });

            // toggle this one
            li.classList.toggle('open', !wasOpen);
        });
    });

// Open parent items that contain an active link
    menu.querySelectorAll('li.has-submenu').forEach(function(item){
        var activeLink = item.querySelector('a.active');
        if (activeLink) item.classList.add('open');
    });
});
