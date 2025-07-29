// Authentication Pages JavaScript
document.addEventListener('DOMContentLoaded', function() {
    
    // Add floating particles to background
    addFloatingParticles();
    
    // Form validation and enhancement
    enhanceForms();
    
    // Password strength indicator for register page
    if (document.querySelector('input[name="password"]')) {
        addPasswordStrengthIndicator();
    }
    
    // Smooth animations
    addSmoothAnimations();
});

function addFloatingParticles() {
    const container = document.querySelector('.login-container, .register-container');
    if (!container) return;
    
    const particlesContainer = document.createElement('div');
    particlesContainer.className = 'particles';
    
    // Create 3 floating particles
    for (let i = 0; i < 3; i++) {
        const particle = document.createElement('div');
        particle.className = 'particle';
        particlesContainer.appendChild(particle);
    }
    
    container.appendChild(particlesContainer);
}

function enhanceForms() {
    const forms = document.querySelectorAll('form');
    
    forms.forEach(form => {
        const inputs = form.querySelectorAll('input');
        const submitBtn = form.querySelector('button[type="submit"]');
        
        // Add focus effects
        inputs.forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.classList.add('focused');
            });
            
            input.addEventListener('blur', function() {
                if (!this.value) {
                    this.parentElement.classList.remove('focused');
                }
            });
            
            // Add input validation feedback
            input.addEventListener('input', function() {
                validateField(this);
            });
        });
        
        // Form submission enhancement
        if (submitBtn) {
            form.addEventListener('submit', function(e) {
                if (!validateForm(this)) {
                    e.preventDefault();
                    shakeForm(this);
                } else {
                    // Show loading state
                    submitBtn.innerHTML = '<span class="loading-spinner"></span> Logging in...';
                    submitBtn.disabled = true;
                }
            });
        }
    });
}

function validateField(field) {
    const value = field.value.trim();
    const fieldType = field.type;
    const fieldName = field.name;
    
    // Remove existing validation classes
    field.classList.remove('valid', 'invalid');
    
    // Validate based on field type
    let isValid = true;
    
    if (fieldType === 'email') {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        isValid = emailRegex.test(value);
    } else if (fieldName === 'password') {
        isValid = value.length >= 6;
    } else if (fieldName === 'confirm_password') {
        const password = document.querySelector('input[name="password"]').value;
        isValid = value === password && value.length > 0;
    } else {
        isValid = value.length > 0;
    }
    
    // Add validation class
    if (isValid && value.length > 0) {
        field.classList.add('valid');
    } else if (value.length > 0) {
        field.classList.add('invalid');
    }
    
    return isValid;
}

function validateForm(form) {
    const inputs = form.querySelectorAll('input[required]');
    let isValid = true;
    
    inputs.forEach(input => {
        if (!validateField(input)) {
            isValid = false;
        }
    });
    
    // Check password confirmation
    const password = form.querySelector('input[name="password"]');
    const confirmPassword = form.querySelector('input[name="confirm_password"]');
    
    if (password && confirmPassword) {
        if (password.value !== confirmPassword.value) {
            isValid = false;
            confirmPassword.classList.add('invalid');
        }
    }
    
    return isValid;
}

function addPasswordStrengthIndicator() {
    const passwordInput = document.querySelector('input[name="password"]');
    const strengthIndicator = document.createElement('div');
    strengthIndicator.className = 'password-strength';
    strengthIndicator.innerHTML = `
        <div class="strength-bar">
            <div class="strength-fill"></div>
        </div>
        <span class="strength-text">Password strength</span>
    `;
    
    passwordInput.parentElement.appendChild(strengthIndicator);
    
    passwordInput.addEventListener('input', function() {
        const strength = calculatePasswordStrength(this.value);
        updateStrengthIndicator(strengthIndicator, strength);
    });
}

function calculatePasswordStrength(password) {
    let score = 0;
    
    if (password.length >= 8) score += 1;
    if (/[a-z]/.test(password)) score += 1;
    if (/[A-Z]/.test(password)) score += 1;
    if (/[0-9]/.test(password)) score += 1;
    if (/[^A-Za-z0-9]/.test(password)) score += 1;
    
    if (score <= 1) return 'weak';
    if (score <= 3) return 'medium';
    return 'strong';
}

function updateStrengthIndicator(indicator, strength) {
    const fill = indicator.querySelector('.strength-fill');
    const text = indicator.querySelector('.strength-text');
    
    // Remove existing classes
    fill.className = 'strength-fill';
    
    // Update based on strength
    switch(strength) {
        case 'weak':
            fill.style.width = '33%';
            fill.style.backgroundColor = '#ff6b6b';
            text.textContent = 'Weak password';
            break;
        case 'medium':
            fill.style.width = '66%';
            fill.style.backgroundColor = '#ffd93d';
            text.textContent = 'Medium password';
            break;
        case 'strong':
            fill.style.width = '100%';
            fill.style.backgroundColor = '#51cf66';
            text.textContent = 'Strong password';
            break;
    }
}

function addSmoothAnimations() {
    // Add entrance animations to form elements
    const formElements = document.querySelectorAll('form > *');
    
    formElements.forEach((element, index) => {
        element.style.opacity = '0';
        element.style.transform = 'translateY(20px)';
        
        setTimeout(() => {
            element.style.transition = 'all 0.6s ease';
            element.style.opacity = '1';
            element.style.transform = 'translateY(0)';
        }, index * 100);
    });
}

function shakeForm(form) {
    form.style.animation = 'shake 0.5s ease-in-out';
    setTimeout(() => {
        form.style.animation = '';
    }, 500);
}

// Add CSS for new elements
const style = document.createElement('style');
style.textContent = `
    .password-strength {
        margin-top: 0.5rem;
    }
    
    .strength-bar {
        width: 100%;
        height: 4px;
        background: #e1e5e9;
        border-radius: 2px;
        overflow: hidden;
        margin-bottom: 0.5rem;
    }
    
    .strength-fill {
        height: 100%;
        width: 0;
        transition: all 0.3s ease;
        border-radius: 2px;
    }
    
    .strength-text {
        font-size: 0.8rem;
        color: #666;
    }
    
    .loading-spinner {
        display: inline-block;
        width: 16px;
        height: 16px;
        border: 2px solid rgba(255,255,255,0.3);
        border-radius: 50%;
        border-top-color: #fff;
        animation: spin 1s ease-in-out infinite;
        margin-right: 8px;
    }
    
    @keyframes spin {
        to { transform: rotate(360deg); }
    }
    
    input.valid {
        border-color: #51cf66;
        box-shadow: 0 0 0 3px rgba(81, 207, 102, 0.1);
    }
    
    input.invalid {
        border-color: #ff6b6b;
        box-shadow: 0 0 0 3px rgba(255, 107, 107, 0.1);
    }
    
    .focused label {
        color: #667eea;
    }
`;
document.head.appendChild(style); 