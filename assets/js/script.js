document.addEventListener("DOMContentLoaded", function () {
    // Burger menu functionality
    const burgerMenu = document.querySelector('.burger-menu');
    const mainNav = document.querySelector('.main-nav');
    const header = document.querySelector('.header');

    function toggleMenu() {
        mainNav.classList.toggle('active');
        burgerMenu.classList.toggle('active');
        header.classList.toggle('nav-open');
    }

    // Click events
    if (burgerMenu && mainNav) {
        // Mouse click
        burgerMenu.addEventListener('click', function(e) {
            e.stopPropagation();
            toggleMenu();
        });

        // Touch events
        burgerMenu.addEventListener('touchstart', function(e) {
            e.preventDefault();
            e.stopPropagation();
            toggleMenu();
        }, { passive: false });

        // Close menu when clicking outside
        document.addEventListener('click', function(e) {
            if (mainNav.classList.contains('active') && !mainNav.contains(e.target)) {
                toggleMenu();
            }
        });

        // Close menu when touching outside
        document.addEventListener('touchstart', function(e) {
            if (mainNav.classList.contains('active') && !mainNav.contains(e.target) && !burgerMenu.contains(e.target)) {
                toggleMenu();
            }
        }, { passive: true });
    }

    // Confirmation for delete action
    const deleteLinks = document.querySelectorAll('a[href*="action=deleteBeer"]');
    deleteLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            if (!confirm('Êtes-vous sûr de vouloir supprimer cette bière ?')) {
                e.preventDefault();
            }
        });
    });
});



