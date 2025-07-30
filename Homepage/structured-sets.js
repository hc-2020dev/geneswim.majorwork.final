// Structured Sets Page Functionality

// Workout set data with detailed information
const workoutSets = {
    sprints: {
        title: "Sprints",
        image: "3844417856.jpeg",
        description: "This high-intensity set targets maximum speed, acceleration, and anaerobic power. Short intervals (25–50m) are performed at race pace or faster, with full or near-full rest to allow for peak effort each repetition.",
        benefits: [
            "Develops explosive speed and power",
            "Improves neuromuscular coordination",
            "Enhances race pace familiarity",
            "Builds anaerobic capacity",
            "Sharpens competitive instincts"
        ],
        instructions: [
            "Warm up thoroughly with easy swimming",
            "Perform 6-10 repetitions of 25-50m at maximum effort",
            "Rest 2-3 minutes between repetitions",
            "Focus on perfect technique at high speed",
            "Cool down with easy swimming"
        ],
        duration: "30-45 minutes",
        intensity: "Maximum (95-100% effort)"
    },
    hypoxic: {
        title: "Hypoxic",
        image: "2988967394.jpeg",
        description: "This breath-control set is designed to improve lung capacity, discipline underwater, and boost efficiency between breaths. Swimmers follow restricted breathing patterns or complete short distances with limited breaths.",
        benefits: [
            "Increases lung capacity and efficiency",
            "Develops mental toughness",
            "Improves underwater discipline",
            "Enhances CO₂ tolerance",
            "Better oxygen utilization"
        ],
        instructions: [
            "Start with bilateral breathing every 3 strokes",
            "Progress to breathing every 5, 7, then 9 strokes",
            "Practice 25m swims with limited breaths",
            "Build up gradually over multiple sessions",
            "Never push beyond your comfort zone"
        ],
        duration: "20-35 minutes",
        intensity: "Moderate (70-80% effort)"
    },
    rehab: {
        title: "Rehabilitation",
        image: "4221476503.jpeg",
        description: "A low-intensity recovery-based set that promotes joint mobility, muscle activation, and stroke mechanics while reducing load on injured areas. Ideal for injury recovery and prevention.",
        benefits: [
            "Promotes healing and recovery",
            "Improves joint mobility",
            "Maintains fitness during injury",
            "Gentle muscle activation",
            "Reduces inflammation"
        ],
        instructions: [
            "Focus on smooth, controlled movements",
            "Emphasize backstroke and easy freestyle",
            "Avoid aggressive kicking or pulling",
            "Use pull buoys to reduce leg strain",
            "Stop immediately if pain occurs"
        ],
        duration: "20-40 minutes",
        intensity: "Low (50-60% effort)"
    },
    aerobics: {
        title: "Aerobics",
        image: "4097219043.jpeg",
        description: "This set emphasizes sustained swimming at moderate pace to build cardiovascular endurance, stroke rhythm, and pacing strategy. Perfect for building aerobic base fitness.",
        benefits: [
            "Builds cardiovascular endurance",
            "Improves stroke efficiency",
            "Develops pacing awareness",
            "Enhances fat burning",
            "Increases aerobic capacity"
        ],
        instructions: [
            "Maintain steady pace throughout",
            "Focus on consistent stroke rate",
            "Keep heart rate in aerobic zone",
            "Practice efficient breathing patterns",
            "Monitor split times for consistency"
        ],
        duration: "45-60 minutes",
        intensity: "Moderate (65-75% effort)"
    },
    "sprint-medley": {
        title: "Sprint (medley)",
        image: "4245787591.jpeg",
        description: "This sprint-focused IM set trains explosive transitions across all four competitive strokes: butterfly, backstroke, breaststroke, and freestyle. Perfect for developing versatility and speed.",
        benefits: [
            "Develops all four competitive strokes",
            "Improves transition skills",
            "Builds stroke-specific power",
            "Enhances coordination under fatigue",
            "Increases versatility"
        ],
        instructions: [
            "Practice smooth transitions between strokes",
            "Maintain high intensity throughout",
            "Focus on stroke-specific technique",
            "Rest adequately between repetitions",
            "Perfect underwater phases"
        ],
        duration: "30-45 minutes",
        intensity: "High (85-95% effort)"
    }
};

document.addEventListener('DOMContentLoaded', function() {
    // Initialize page functionality
    initializeSetModals();
    initializeSetAnimations();
    initializeWorkoutTracking();

    // Set up event listeners
    setupEventListeners();
});

function initializeSetModals() {
    const modal = document.getElementById('setModal');
    const readMoreButtons = document.querySelectorAll('.btn-read-more');
    const closeButton = document.querySelector('.modal-close');
    const startWorkoutButton = document.querySelector('.btn-start-workout');

    // Open modal when Read More is clicked
    readMoreButtons.forEach(button => {
        button.addEventListener('click', function() {
            const setType = this.getAttribute('data-target');
            openSetModal(setType);
        });
    });

    // Close modal functionality
    closeButton.addEventListener('click', closeModal);

    // Close modal when clicking outside
    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            closeModal();
        }
    });

    // Close modal with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && modal.classList.contains('show')) {
            closeModal();
        }
    });

    // Start workout functionality
    startWorkoutButton.addEventListener('click', function() {
        const currentSet = startWorkoutButton.getAttribute('data-set');
        startWorkout(currentSet);
    });
}

function openSetModal(setType) {
    const modal = document.getElementById('setModal');
    const setData = workoutSets[setType];

    if (!setData) return;

    // Populate modal content
    document.getElementById('modalTitle').textContent = setData.title;

    // Modal image
    const modalImage = document.getElementById('modalImage');
    modalImage.innerHTML = `<img src="${setData.image}" alt="${setData.title} Training" loading="lazy">`;

    // Modal description
    document.getElementById('modalDescription').innerHTML = `
        <p>${setData.description}</p>
        <div class="workout-meta">
            <div class="workout-duration">
                <strong>Duration:</strong> ${setData.duration}
            </div>
            <div class="workout-intensity">
                <strong>Intensity:</strong> ${setData.intensity}
            </div>
        </div>
    `;

    // Modal benefits
    const benefitsList = setData.benefits.map(benefit => `<li>${benefit}</li>`).join('');
    document.getElementById('modalBenefits').innerHTML = `
        <h4>Benefits</h4>
        <ul>${benefitsList}</ul>
    `;

    // Modal instructions
    const instructionsList = setData.instructions.map(instruction => `<li>${instruction}</li>`).join('');
    document.getElementById('modalInstructions').innerHTML = `
        <h4>Instructions</h4>
        <ul>${instructionsList}</ul>
    `;

    // Set current workout for start button
    document.querySelector('.btn-start-workout').setAttribute('data-set', setType);

    // Show modal
    modal.classList.add('show');
    modal.style.display = 'flex';

    // Focus management for accessibility
    setTimeout(() => {
        document.querySelector('.modal-close').focus();
    }, 300);

    // Prevent body scroll
    document.body.style.overflow = 'hidden';

    // Example: inside your function that opens the modal
    if (setType === 'sprints') {
        document.querySelector('.btn-start-workout').onclick = function() {
            window.location.href = 'sprint_set.php';
        };
    } else {
        document.querySelector('.btn-start-workout').onclick = null; // or set to another page or do nothing
    }
}

function closeModal() {
    const modal = document.getElementById('setModal');
    modal.classList.remove('show');

    setTimeout(() => {
        modal.style.display = 'none';
        document.body.style.overflow = '';
    }, 300);
}

function initializeSetAnimations() {
    // Intersection Observer for scroll animations
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.animationPlayState = 'running';
            }
        });
    }, observerOptions);

    // Observe all set items
    const setItems = document.querySelectorAll('.set-item');
    setItems.forEach(item => {
        observer.observe(item);
    });
}

function initializeWorkoutTracking() {
    // Load user preferences from localStorage
    const preferences = getWorkoutPreferences();

    // Apply any saved preferences
    if (preferences.favoriteSet) {
        highlightFavoriteSet(preferences.favoriteSet);
    }
}

function setupEventListeners() {
    // Track user interactions for analytics
    const setItems = document.querySelectorAll('.set-item');

    setItems.forEach(item => {
        item.addEventListener('mouseenter', function() {
            const setType = this.getAttribute('data-set');
            trackInteraction('set_hover', setType);
        });

        item.addEventListener('click', function(e) {
            if (!e.target.classList.contains('btn-read-more')) {
                const setType = this.getAttribute('data-set');
                trackInteraction('set_click', setType);
            }
        });
    });

    // Newsletter form in footer
    const newsletterForm = document.querySelector('.newsletter-form');
    if (newsletterForm) {
        newsletterForm.addEventListener('submit', function(e) {
            e.preventDefault();
            handleNewsletterSignup(this);
        });
    }
}

function startWorkout(setType) {
    const setData = workoutSets[setType];

    // Close modal
    closeModal();

    // Show workout confirmation
    showWorkoutConfirmation(setData);

    // Track workout start
    trackInteraction('workout_start', setType);

    // Save to workout history
    saveWorkoutToHistory(setType);

    // In a real app, this would navigate to the workout interface
    setTimeout(() => {
        showNotification(`Starting ${setData.title} workout! Duration: ${setData.duration}`);
    }, 500);
}

function showWorkoutConfirmation(setData) {
    const confirmation = document.createElement('div');
    confirmation.className = 'workout-confirmation';
    confirmation.innerHTML = `
        <div class="confirmation-content">
            <div class="confirmation-icon">🏊‍♂️</div>
            <h3>Workout Started!</h3>
            <p>Starting your ${setData.title} training session</p>
            <div class="confirmation-details">
                <div>Duration: ${setData.duration}</div>
                <div>Intensity: ${setData.intensity}</div>
            </div>
        </div>
    `;

    // Style the confirmation
    confirmation.style.cssText = `
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background: white;
        padding: 2rem;
        border-radius: 12px;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
        z-index: 3000;
        text-align: center;
        max-width: 300px;
        animation: scaleIn 0.3s ease;
    `;

    document.body.appendChild(confirmation);

    // Remove after 3 seconds
    setTimeout(() => {
        confirmation.style.animation = 'scaleOut 0.3s ease forwards';
        setTimeout(() => {
            if (confirmation.parentNode) {
                confirmation.remove();
            }
        }, 300);
    }, 3000);
}

function handleNewsletterSignup(form) {
    const email = form.querySelector('input[name="email"]').value;
    const checkbox = form.querySelector('input[name="subscribe"]').checked;

    if (!checkbox) {
        showNotification('Please check the subscription checkbox to continue.', 'error');
        return;
    }

    // Submit via AJAX
    const formData = new FormData(form);
    formData.append('ajax', 'true');

    fetch('subscribe.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('Successfully subscribed to newsletter!', 'success');
            form.reset();
        } else {
            showNotification(data.message || 'Subscription failed. Please try again.', 'error');
        }
    })
    .catch(error => {
        showNotification('Network error. Please try again.', 'error');
    });
}

function trackInteraction(action, setType) {
    // Analytics tracking (would integrate with Google Analytics, etc.)
    if (typeof gtag === 'function') {
        gtag('event', action, {
            'event_category': 'workout_sets',
            'event_label': setType
        });
    }

    // Save to localStorage for demo purposes
    const interactions = JSON.parse(localStorage.getItem('geneswim_interactions') || '[]');
    interactions.push({
        action,
        setType,
        timestamp: new Date().toISOString()
    });

    // Keep only last 100 interactions
    if (interactions.length > 100) {
        interactions.splice(0, interactions.length - 100);
    }

    localStorage.setItem('geneswim_interactions', JSON.stringify(interactions));
}

function getWorkoutPreferences() {
    return JSON.parse(localStorage.getItem('geneswim_preferences') || '{}');
}

function saveWorkoutToHistory(setType) {
    const history = JSON.parse(localStorage.getItem('geneswim_workout_history') || '[]');
    history.unshift({
        setType,
        date: new Date().toISOString(),
        completed: false
    });

    // Keep only last 50 workouts
    if (history.length > 50) {
        history.splice(50);
    }

    localStorage.setItem('geneswim_workout_history', JSON.stringify(history));
}

function highlightFavoriteSet(setType) {
    const setItem = document.querySelector(`[data-set="${setType}"]`);
    if (setItem) {
        setItem.classList.add('favorite-set');
    }
}

function showNotification(message, type = 'info') {
    // Remove existing notifications
    const existing = document.querySelector('.notification');
    if (existing) {
        existing.remove();
    }

    const notification = document.createElement('div');
    notification.className = 'notification';
    notification.textContent = message;

    const colors = {
        info: '#216bb6',
        success: '#28a745',
        error: '#dc3545'
    };

    notification.style.cssText = `
        position: fixed;
        top: 100px;
        right: 20px;
        background: ${colors[type] || colors.info};
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

    // Auto remove
    setTimeout(() => {
        notification.style.transform = 'translateX(100%)';
        setTimeout(() => {
            if (notification.parentNode) {
                notification.remove();
            }
        }, 300);
    }, 5000);
}

// Add CSS for additional elements
const additionalStyles = document.createElement('style');
additionalStyles.textContent = `
    .workout-meta {
        background: #f8f9fa;
        padding: 1rem;
        border-radius: 8px;
        margin-top: 1rem;
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
    }

    .workout-duration,
    .workout-intensity {
        font-size: 0.9rem;
    }

    .confirmation-content .confirmation-icon {
        font-size: 3rem;
        margin-bottom: 1rem;
    }

    .confirmation-content h3 {
        color: #040d1c;
        margin-bottom: 0.5rem;
    }

    .confirmation-details {
        margin-top: 1rem;
        padding-top: 1rem;
        border-top: 1px solid #dcebf3;
        font-size: 0.9rem;
        color: #676d72;
    }

    .favorite-set {
        border: 2px solid #216bb6;
    }

    .favorite-set::before {
        content: "⭐";
        position: absolute;
        top: 1rem;
        right: 1rem;
        font-size: 1.5rem;
        z-index: 10;
    }

    @keyframes scaleOut {
        from {
            opacity: 1;
            transform: translate(-50%, -50%) scale(1);
        }
        to {
            opacity: 0;
            transform: translate(-50%, -50%) scale(0.9);
        }
    }
`;
document.head.appendChild(additionalStyles);
