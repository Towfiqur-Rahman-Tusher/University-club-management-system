        // Add any custom JavaScript here
        document.querySelectorAll('.nav-link').forEach(link => {
            link.addEventListener('click', function() {
                // Close mobile menu when clicking a link
                const navbarCollapse = document.querySelector('.navbar-collapse');
                if (navbarCollapse.classList.contains('show')) {
                    new bootstrap.Collapse(navbarCollapse).toggle();
                }
            });
        });