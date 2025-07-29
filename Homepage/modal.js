// Auth Modal JavaScript
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('authModal');
    const closeBtn = document.querySelector('.auth-close');
    const switchFormLinks = document.querySelectorAll('.switch-form');
    
    // Close modal when clicking the X button
    if (closeBtn) {
        closeBtn.addEventListener('click', closeAuthModal);
    }
    
    // Close modal when clicking outside the modal content
    if (modal) {
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                closeAuthModal();
            }
        });
    }
    
    // Handle form switching
    switchFormLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const targetForm = this.getAttribute('data-form');
            switchAuthForm(targetForm);
        });
    });
    
    // Close modal with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && modal.style.display === 'block') {
            closeAuthModal();
        }
    });
    
    // Add form validation and enhancement
    enhanceModalForms();
});

// Global function to open auth modal
function openAuthModal(formType = 'login') {
    const modal = document.getElementById('authModal');
    if (modal) {
        modal.style.display = 'block';
        document.body.style.overflow = 'hidden'; // Prevent background scrolling
        switchAuthForm(formType);
        
        // Focus on first input
        setTimeout(() => {
            const firstInput = modal.querySelector('.auth-form.active input');
            if (firstInput) {
                firstInput.focus();
            }
        }, 300);
    }
}

// Close the auth modal
function closeAuthModal() {
    const modal = document.getElementById('authModal');
    if (modal) {
        modal.style.display = 'none';
        document.body.style.overflow = ''; // Restore scrolling
        
        // Clear any error/success messages
        const messages = modal.querySelectorAll('.error-message, .success-message');
        messages.forEach(msg => msg.remove());
        
        // Reset forms
        const forms = modal.querySelectorAll('form');
        forms.forEach(form => form.reset());
        
        // Remove validation classes
        const inputs = modal.querySelectorAll('input');
        inputs.forEach(input => {
            input.classList.remove('valid', 'invalid');
        });
    }
}

// Switch between login, register, and logout forms
function switchAuthForm(formType) {
    const forms = document.querySelectorAll('.auth-form');
    const targetForm = document.getElementById(formType + 'Form');
    
    // Hide all forms
    forms.forEach(form => {
        form.classList.remove('active');
    });
    
    // Show target form
    if (targetForm) {
        targetForm.classList.add('active');
        
        // Clear any existing messages
        const messages = targetForm.querySelectorAll('.error-message, .success-message');
        messages.forEach(msg => msg.remove());
        
        // Reset form
        const form = targetForm.querySelector('form');
        if (form) {
            form.reset();
        }
        
        // Focus on first input
        setTimeout(() => {
            const firstInput = targetForm.querySelector('input');
            if (firstInput) {
                firstInput.focus();
            }
        }, 100);
    }
}

// Enhance modal forms with validation and effects
function enhanceModalForms() {
    const forms = document.querySelectorAll('.auth-form form');
    
    forms.forEach(form => {
        const inputs = form.querySelectorAll('input');
        const submitBtn = form.querySelector('.submit-btn');
        
        // Add input validation
        inputs.forEach(input => {
            input.addEventListener('input', function() {
                validateModalField(this);
            });
            
            input.addEventListener('focus', function() {
                this.parentElement.classList.add('focused');
            });
            
            input.addEventListener('blur', function() {
                if (!this.value) {
                    this.parentElement.classList.remove('focused');
                }
            });
        });
        
        // Form submission handling
        if (submitBtn) {
            form.addEventListener('submit', function(e) {
                if (!validateModalForm(this)) {
                    e.preventDefault();
                    shakeModalForm(this);
                } else {
                    // Show loading state
                    const originalText = submitBtn.textContent;
                    submitBtn.innerHTML = '<span class="loading-spinner"></span> Processing...';
                    submitBtn.disabled = true;
                    
                    // Re-enable after a delay (in case of errors)
                    setTimeout(() => {
                        submitBtn.textContent = originalText;
                        submitBtn.disabled = false;
                    }, 3000);
                }
            });
        }
    });
    
    // Add password strength indicator for register form
    const registerPassword = document.querySelector('#register-password');
    if (registerPassword) {
        addModalPasswordStrength(registerPassword);
    }
}

// Validate individual field in modal
function validateModalField(field) {
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
        const password = document.querySelector('#register-password').value;
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

// Validate entire modal form
function validateModalForm(form) {
    const inputs = form.querySelectorAll('input[required]');
    let isValid = true;
    
    inputs.forEach(input => {
        if (!validateModalField(input)) {
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

// Add password strength indicator to modal
function addModalPasswordStrength(passwordInput) {
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

// Calculate password strength
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

// Update strength indicator
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

// Shake animation for invalid modal form
function shakeModalForm(form) {
    form.style.animation = 'shake 0.5s ease-in-out';
    setTimeout(() => {
        form.style.animation = '';
    }, 500);
}

// Auto-close modal after successful login/logout
function handleAuthSuccess() {
    setTimeout(() => {
        closeAuthModal();
        // Refresh page to update navbar
        window.location.reload();
    }, 1500);
} 