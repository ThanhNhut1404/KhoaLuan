document.addEventListener('DOMContentLoaded', function() {
    window.toggleMenu = function() {
        ['sidebar','menu'].forEach(function(id){
            var el = document.getElementById(id);
            if (el) el.classList.toggle('active');
        });
    };
});
