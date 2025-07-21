// Smooth scrolling for navigation links
document.addEventListener('DOMContentLoaded', function() {
    // Smooth scrolling for anchor links
    const navLinks = document.querySelectorAll('a[href^="#"]');

    navLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();

            const targetId = this.getAttribute('href');
            const targetElement = document.querySelector(targetId);

            if (targetElement) {
                const headerHeight = document.querySelector('.header').offsetHeight;
                const targetPosition = targetElement.offsetTop - headerHeight;

                window.scrollTo({
                    top: targetPosition,
                    behavior: 'smooth'
                });
            }
        });
    });

    // Header scroll effect
    const header = document.querySelector('.header');
    let lastScrollTop = 0;

    window.addEventListener('scroll', function() {
        const scrollTop = window.pageYOffset || document.documentElement.scrollTop;

        if (scrollTop > 100) {
            header.style.background = 'rgba(255, 255, 255, 0.98)';
            header.style.boxShadow = '0 2px 20px rgba(0, 0, 0, 0.1)';
        } else {
            header.style.background = 'rgba(255, 255, 255, 0.95)';
            header.style.boxShadow = 'none';
        }

        lastScrollTop = scrollTop;
    });

    // Button animations and interactions
    const buttons = document.querySelectorAll('button, .btn-start, .btn-get-started');

    buttons.forEach(button => {
        button.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-2px)';
        });

        button.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });

        button.addEventListener('mousedown', function() {
            this.style.transform = 'translateY(0)';
        });

        button.addEventListener('mouseup', function() {
            this.style.transform = 'translateY(-2px)';
        });
    });

    // Start Now and Get Started button functionality
    const startButtons = document.querySelectorAll('.btn-start, .btn-get-started');

    startButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Redirect to circuit generator (would be a real page in production)
            showNotification('Welcome to GeneSwim! Circuit Generator coming soon...');
        });
    });

    // Newsletter form handling
    const newsletterForm = document.querySelector('.newsletter-form');

    if (newsletterForm) {
        newsletterForm.addEventListener('submit', function(e) {
            e.preventDefault();

            const email = this.querySelector('input[name="email"]').value;
            const checkbox = document.querySelector('input[name="subscribe"]').checked;

            if (email && checkbox) {
                // In a real application, this would submit to the PHP backend
                submitNewsletter(email);
            } else if (!checkbox) {
                showNotification('Please check the subscription checkbox to continue.');
            }
        });
    }

    // Parallax effect for hero sections
    window.addEventListener('scroll', function() {
        const scrolled = window.pageYOffset;
        const parallaxElements = document.querySelectorAll('.hero-bg-img');

        parallaxElements.forEach(element => {
            const speed = 0.2;
            const yPos = -(scrolled * speed);
            element.style.transform = `translateY(${yPos}px)`;
        });
    });

    // Intersection Observer for animations
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate-in');
            }
        });
    }, observerOptions);

    // Observe feature items for animation
    const featureItems = document.querySelectorAll('.feature-item');
    featureItems.forEach(item => {
        observer.observe(item);
    });

    // Mobile menu toggle (if needed for smaller screens)
    const navToggle = document.createElement('button');
    navToggle.classList.add('nav-toggle');
    navToggle.innerHTML = '☰';
    navToggle.style.display = 'none';

    const navContainer = document.querySelector('.nav-container');
    navContainer.appendChild(navToggle);

    navToggle.addEventListener('click', function() {
        const navLinks = document.querySelector('.nav-links');
        navLinks.classList.toggle('mobile-open');
    });

    // Responsive navigation
    function checkMobile() {
        if (window.innerWidth <= 768) {
            navToggle.style.display = 'block';
        } else {
            navToggle.style.display = 'none';
            document.querySelector('.nav-links').classList.remove('mobile-open');
        }
    }

    window.addEventListener('resize', checkMobile);
    checkMobile();
});

// Function to simulate newsletter submission
function submitNewsletter(email) {
    // Show loading state
    const submitButton = document.querySelector('.btn-newsletter');
    const originalText = submitButton.textContent;
    submitButton.textContent = 'Joining...';
    submitButton.disabled = true;

    // Simulate API call
    setTimeout(() => {
        submitButton.textContent = 'Joined!';
        submitButton.style.background = '#28a745';

        showNotification(`Thank you! We've added ${email} to our newsletter.`);

        // Reset form
        document.querySelector('.newsletter-form').reset();

        // Reset button after 3 seconds
        setTimeout(() => {
            submitButton.textContent = originalText;
            submitButton.disabled = false;
            submitButton.style.background = '';
        }, 3000);
    }, 1500);
}

// Notification system
function showNotification(message) {
    // Remove existing notifications
    const existingNotification = document.querySelector('.notification');
    if (existingNotification) {
        existingNotification.remove();
    }

    // Create notification element
    const notification = document.createElement('div');
    notification.className = 'notification';
    notification.textContent = message;

    // Style the notification
    notification.style.cssText = `
        position: fixed;
        top: 100px;
        right: 20px;
        background: #216bb6;
        color: white;
        padding: 1rem 1.5rem;
        border-radius: 8px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
        z-index: 1001;
        transform: translateX(100%);
        transition: transform 0.3s ease;
        max-width: 300px;
        font-weight: 500;
    `;

    document.body.appendChild(notification);

    // Animate in
    setTimeout(() => {
        notification.style.transform = 'translateX(0)';
    }, 100);

    // Auto remove after 5 seconds
    setTimeout(() => {
        notification.style.transform = 'translateX(100%)';
        setTimeout(() => {
            if (notification.parentNode) {
                notification.remove();
            }
        }, 300);
    }, 5000);
}

// Add CSS animations
const style = document.createElement('style');
style.textContent = `
    .feature-item {
        opacity: 0;
        transform: translateY(30px);
        transition: all 0.6s ease;
    }

    .feature-item.animate-in {
        opacity: 1;
        transform: translateY(0);
    }

    .nav-toggle {
        background: none;
        border: none;
        font-size: 1.5rem;
        cursor: pointer;
        color: #2d3e4d;
    }

    @media (max-width: 768px) {
        .nav-links {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(10px);
            flex-direction: column;
            padding: 1rem 2rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            transform: translateY(-100%);
            opacity: 0;
            pointer-events: none;
            transition: all 0.3s ease;
        }

        .nav-links.mobile-open {
            transform: translateY(0);
            opacity: 1;
            pointer-events: auto;
        }
    }
`;
document.head.appendChild(style);