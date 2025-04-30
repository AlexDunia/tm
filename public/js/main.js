// Enhanced slideshow functionality
document.addEventListener('DOMContentLoaded', function() {
    // Force all cards to be visible
    document.querySelectorAll('.one_e').forEach(element => {
        element.style.opacity = "1";
        element.style.transform = "translateY(0)";
    });

    let slideIndex = 0;
    const slides = document.querySelectorAll('.newslideshow');

    if (slides.length > 0) {
        // Initialize first slide
        showSlides();

        function showSlides() {
            // Hide all slides
            for (let i = 0; i < slides.length; i++) {
                slides[i].style.opacity = "0";
                slides[i].classList.remove('active');
            }

            // Next slide
            slideIndex++;
            if (slideIndex > slides.length) {
                slideIndex = 1;
            }

            // Show the current slide
            slides[slideIndex - 1].style.opacity = "1";
            slides[slideIndex - 1].classList.add('active');

            // Change slide every 6 seconds
            setTimeout(showSlides, 6000);
        }
    }

    // Header scroll effect
    const header = document.querySelector('.site-header');
    if (header) {
        window.addEventListener('scroll', function() {
            if (window.scrollY > 50) {
                header.classList.add('scrolled');
            } else {
                header.classList.remove('scrolled');
            }
        });
    }

    // Only animate non-card elements
    const animateOnScroll = function() {
        const elements = document.querySelectorAll('.event-category, .event-detail-image, .event-detail-content');

        elements.forEach(element => {
            const elementPosition = element.getBoundingClientRect().top;
            const screenPosition = window.innerHeight / 1.2;

            if (elementPosition < screenPosition) {
                element.style.opacity = "1";
                element.style.transform = "translateY(0)";
            }
        });
    };

    // Set initial state only for non-card elements
    document.querySelectorAll('.event-category, .event-detail-image, .event-detail-content').forEach(element => {
        element.style.opacity = "0";
        element.style.transform = "translateY(20px)";
        element.style.transition = "all 0.8s ease-out";
    });

    // Run animation on load and scroll
    window.addEventListener('load', animateOnScroll);
    window.addEventListener('scroll', animateOnScroll);

    // Force cards to be visible again after everything loads
    window.addEventListener('load', function() {
        setTimeout(function() {
            document.querySelectorAll('.one_e').forEach(element => {
                element.style.opacity = "1";
                element.style.transform = "translateY(0)";
            });
        }, 100);
    });
});
