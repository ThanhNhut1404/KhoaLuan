// Expose toggleMenu immediately so hamburger can call it anytime
window.toggleMenu = function() {
    ['sidebar','menu'].forEach(function(id){
        var el = document.getElementById(id);
        if (el) el.classList.toggle('active');
    });
};

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
        console.log(item.querySelector('span').textContent, '| active link found:', activeLink ? activeLink.textContent.trim() : 'none');
        if (activeLink) item.classList.add('open');
    });
});
