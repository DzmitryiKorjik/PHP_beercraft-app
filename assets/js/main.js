document.addEventListener('DOMContentLoaded', function() {
    const dropdowns = document.querySelectorAll('.dropdown');

    dropdowns.forEach(dropdown => {
        const toggle = dropdown.querySelector('.dropdown-toggle');
        const menu = dropdown.querySelector('.dropdown-menu');

        // Toggle menu on click
        toggle.addEventListener('click', (e) => {
            e.preventDefault();
            menu.classList.toggle('show');
        });

        // Close menu when clicking outside
        document.addEventListener('click', (e) => {
            if (!dropdown.contains(e.target)) {
                menu.classList.remove('show');
            }
        });
    });
});
