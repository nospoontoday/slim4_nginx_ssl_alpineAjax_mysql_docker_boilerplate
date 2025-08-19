<?php
/**
 * @file login.php
 * @purpose Premium login page template
 * @ai-context Login page with modern design and HTMX interactions
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In - Sugar</title>
    <meta name="description" content="Sign in to your Sugar account">
    
    <!-- Preload critical fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- CSS -->
    <link rel="stylesheet" href="/assets/css/master.css">
    
    <!-- HTMX -->
    <script src="https://unpkg.com/htmx.org@2.0.3" defer></script>
    
    <!-- Alpine.js -->
    <script src="https://unpkg.com/alpinejs@3.14.1/dist/cdn.min.js" defer></script>
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="/assets/images/favicon.ico">
</head>
<body class="login-page">
    <div class="login-container">
        <!-- Background Elements -->
        <div class="login-background">
            <div class="bg-gradient"></div>
            <div class="bg-pattern"></div>
        </div>
        
        <!-- Main Login Card -->
        <div class="login-card">
            <!-- Logo Section -->
            <div class="login-header">
                <div class="logo-container">
                    <svg class="logo" viewBox="0 0 120 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <!-- Sugar Logo - Premium design -->
                        <path d="M8 32C8 28.6863 10.6863 26 14 26H18C21.3137 26 24 28.6863 24 32V32C24 35.3137 21.3137 38 18 38H14C10.6863 38 8 35.3137 8 32V32Z" fill="var(--brand-primary)"/>
                        <path d="M28 20C28 16.6863 30.6863 14 34 14H38C41.3137 14 44 16.6863 44 20V20C44 23.3137 41.3137 26 38 26H34C30.6863 26 28 23.3137 28 20V20Z" fill="var(--brand-secondary)"/>
                        <path d="M48 8C48 4.68629 50.6863 2 54 2H58C61.3137 2 64 4.68629 64 8V8C64 11.3137 61.3137 14 58 14H54C50.6863 14 48 11.3137 48 8V8Z" fill="var(--brand-primary)"/>
                        <text x="72" y="24" font-family="var(--font-primary)" font-size="18" font-weight="700" fill="var(--color-gray-900)">Sugar</text>
                    </svg>
                </div>
                <h1 class="login-title">Welcome back</h1>
                <p class="login-subtitle">Sign in to your account to continue</p>
            </div>
            
            <!-- Error Messages -->
            <div id="login-errors" class="login-errors">
                <!-- Test button to verify HTMX functionality -->
                <button type="button" 
                        class="btn btn-secondary btn-sm" 
                        hx-get="/api/v1/auth/test-errors"
                        hx-target="#login-errors"
                        hx-swap="innerHTML"
                        style="margin-bottom: 1rem;">
                    Test Error Display
                </button>
                
                <!-- Simple test to verify HTMX is working -->
                <div id="htmx-test" style="margin-bottom: 1rem;">
                    <button type="button" 
                            class="btn btn-primary btn-sm" 
                            hx-get="/api/v1/auth/test-errors"
                            hx-target="#htmx-test"
                            hx-swap="innerHTML">
                        Test HTMX
                    </button>
                </div>
            </div>
            
            <!-- Login Form -->
            <form 
                class="login-form"
                method="POST"
                action="/api/v1/auth/login"
                hx-post="/api/v1/auth/login"
                hx-target="#login-errors"
                hx-swap="innerHTML"
                hx-trigger="submit"
                @submit="handleSubmit"
                novalidate
            >
                <!-- Email Field -->
                <div class="form-group">
                    <label for="email" class="form-label">
                        <span>Email address</span>
                        <span class="required">*</span>
                    </label>
                    <div class="input-wrapper">
                        <input
                            type="email"
                            id="email"
                            name="email"
                            class="form-input"
                            placeholder="Enter your email"
                            required
                            autocomplete="email"
                        >
                        <div class="input-icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                                <polyline points="22,6 12,13 2,6"></polyline>
                            </svg>
                        </div>
                    </div>
                    <div class="field-error" id="email-error" style="display: none;"></div>
                </div>
                
                <!-- Password Field -->
                <div class="form-group">
                    <label for="password" class="form-label">
                        <span>Password</span>
                        <span class="required">*</span>
                    </label>
                    <div class="input-wrapper">
                        <input
                            type="password"
                            id="password"
                            name="password"
                            class="form-input"
                            placeholder="Enter your password"
                            required
                            autocomplete="current-password"
                        >
                        <button
                            type="button"
                            class="password-toggle"
                            id="password-toggle"
                            aria-label="Show password"
                        >
                            <svg id="show-password-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                <circle cx="12" cy="12" r="3"></circle>
                            </svg>
                            <svg id="hide-password-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display: none;">
                                <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path>
                                <line x1="1" y1="1" x2="23" y2="23"></line>
                            </svg>
                        </button>
                    </div>
                    <div class="field-error" id="password-error" style="display: none;"></div>
                </div>
                
                <!-- Remember Me & Forgot Password -->
                <div class="form-options">
                    <label class="checkbox-wrapper">
                        <input
                            type="checkbox"
                            name="remember"
                            id="remember"
                        >
                        <span class="checkbox-custom"></span>
                        <span class="checkbox-label">Remember me</span>
                    </label>
                    
                    <a href="/forgot-password" class="forgot-link">
                        Forgot password?
                    </a>
                </div>
                
                <!-- Submit Button -->
                <button 
                    type="submit" 
                    class="btn btn-primary btn-full"
                    id="login-submit-btn"
                >
                    <span id="btn-text-default">Sign In</span>
                    <span id="btn-text-loading" class="btn-loading" style="display: none;">
                        <svg id="login-spinner" class="spinner" width="20" height="20" viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-dasharray="32" stroke-dashoffset="32">
                                <animate attributeName="stroke-dasharray" dur="2s" values="0 32;16 16;0 32;0 32" repeatCount="indefinite"/>
                                <animate attributeName="stroke-dashoffset" dur="2s" values="0;-16;-32;-32" repeatCount="indefinite"/>
                            </circle>
                        </svg>
                        Signing in...
                    </span>
                </button>
            </form>
            
            <!-- Social Login Options -->
            <div class="social-login">
                <div class="divider">
                    <span>Or continue with</span>
                </div>
                
                <div class="social-buttons">
                    <button class="btn btn-social" data-provider="google">
                        <svg width="20" height="20" viewBox="0 0 24 24">
                            <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                            <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                            <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                            <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                        </svg>
                        Google
                    </button>
                    
                    <button class="btn btn-social" data-provider="microsoft">
                        <svg width="20" height="20" viewBox="0 0 24 24">
                            <path fill="#F25022" d="M1 1h10v10H1z"/>
                            <path fill="#00A4EF" d="M13 1h10v10H13z"/>
                            <path fill="#7FBA00" d="M1 13h10v10H1z"/>
                            <path fill="#FFB900" d="M13 13h10v10H13z"/>
                        </svg>
                        Microsoft
                    </button>
                </div>
            </div>
            
            <!-- Sign Up Link -->
            <div class="signup-link">
                <p>Don't have an account? <a href="/register">Sign up</a></p>
            </div>
        </div>
        
        <!-- Footer -->
        <div class="login-footer">
            <p>&copy; 2024 Sugar. All rights reserved.</p>
            <div class="footer-links">
                <a href="/privacy">Privacy Policy</a>
                <a href="/terms">Terms of Service</a>
                <a href="/support">Support</a>
            </div>
        </div>
    </div>

    <script>
        // Client-side validation functions
        function validateEmail(email) {
            if (!email) {
                return 'Email is required';
            } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
                return 'Please enter a valid email address';
            } else if (email.length > 255) {
                return 'Email address is too long';
            }
            return null;
        }
        
        function validatePassword(password) {
            if (!password) {
                return 'Password is required';
            } else if (password.length < 6) {
                return 'Password must be at least 6 characters';
            } else if (password.length > 255) {
                return 'Password is too long';
            }
            return null;
        }
        
        function showFieldError(fieldId, message) {
            const field = document.getElementById(fieldId);
            const errorDiv = document.getElementById(fieldId + '-error');
            
            if (field && errorDiv) {
                field.classList.add('error');
                errorDiv.textContent = message;
                errorDiv.style.display = 'block';
            }
        }
        
        function hideFieldError(fieldId) {
            const field = document.getElementById(fieldId);
            const errorDiv = document.getElementById(fieldId + '-error');
            
            if (field && errorDiv) {
                field.classList.remove('error');
                errorDiv.style.display = 'none';
            }
        }
        
        function clearAllErrors() {
            hideFieldError('email');
            hideFieldError('password');
            
            // Clear server-side errors
            const errorContainer = document.getElementById('login-errors');
            if (errorContainer) {
                errorContainer.innerHTML = '';
            }
        }
        
        // Password toggle functionality
        function initPasswordToggle() {
            const passwordField = document.getElementById('password');
            const toggleButton = document.getElementById('password-toggle');
            const showIcon = document.getElementById('show-password-icon');
            const hideIcon = document.getElementById('hide-password-icon');
            
            if (passwordField && toggleButton && showIcon && hideIcon) {
                toggleButton.addEventListener('click', function() {
                    const isPassword = passwordField.type === 'password';
                    
                    passwordField.type = isPassword ? 'text' : 'password';
                    showIcon.style.display = isPassword ? 'none' : 'block';
                    hideIcon.style.display = isPassword ? 'block' : 'none';
                    toggleButton.setAttribute('aria-label', isPassword ? 'Hide password' : 'Show password');
                });
            }
        }
        
        // Form validation on blur
        function initFieldValidation() {
            const emailField = document.getElementById('email');
            const passwordField = document.getElementById('password');
            
            if (emailField) {
                emailField.addEventListener('blur', function() {
                    const error = validateEmail(this.value);
                    if (error) {
                        showFieldError('email', error);
                    } else {
                        hideFieldError('email');
                    }
                });
                
                emailField.addEventListener('input', function() {
                    // Clear error on input if field was previously invalid
                    if (this.classList.contains('error')) {
                        const error = validateEmail(this.value);
                        if (!error) {
                            hideFieldError('email');
                        }
                    }
                });
            }
            
            if (passwordField) {
                passwordField.addEventListener('blur', function() {
                    const error = validatePassword(this.value);
                    if (error) {
                        showFieldError('password', error);
                    } else {
                        hideFieldError('password');
                    }
                });
                
                passwordField.addEventListener('input', function() {
                    // Clear error on input if field was previously invalid
                    if (this.classList.contains('error')) {
                        const error = validatePassword(this.value);
                        if (!error) {
                            hideFieldError('password');
                        }
                    }
                });
            }
        }
        
        // Form submission handler
        function handleFormSubmit(event) {
            console.log('🔍 DEBUG: Form submission started');
            
            // Clear previous errors
            clearAllErrors();
            
            // Get form values
            const emailField = document.getElementById('email');
            const passwordField = document.getElementById('password');
            
            let hasErrors = false;
            
            // Validate email
            if (emailField) {
                const emailError = validateEmail(emailField.value);
                if (emailError) {
                    showFieldError('email', emailError);
                    hasErrors = true;
                }
            }
            
            // Validate password
            if (passwordField) {
                const passwordError = validatePassword(passwordField.value);
                if (passwordError) {
                    showFieldError('password', passwordError);
                    hasErrors = true;
                }
            }
            
            // If there are client-side errors, prevent submission
            if (hasErrors) {
                event.preventDefault();
                console.log('🔍 DEBUG: Form submission prevented due to validation errors');
                return false;
            }
            
            console.log('🔍 DEBUG: Form validation passed, proceeding with HTMX submission');
            return true;
        }
        
        // Initialize everything when DOM is loaded
        document.addEventListener('DOMContentLoaded', function() {
            console.log('🔍 DEBUG: Initializing form validation and password toggle');
            
            initPasswordToggle();
            initFieldValidation();
            
            // Add form submit handler
            const form = document.querySelector('.login-form');
            if (form) {
                form.addEventListener('submit', handleFormSubmit);
            }
        });
        
        // Legacy Alpine.js function for compatibility (empty)
        function loginForm() {
            return {};
        }
        
        // Button state management function
        function setButtonLoadingState(isLoading) {
            const defaultText = document.getElementById('btn-text-default');
            const loadingText = document.getElementById('btn-text-loading');
            const submitBtn = document.getElementById('login-submit-btn');
            
            console.log('🔍 DEBUG: setButtonLoadingState called with:', isLoading);
            console.log('🔍 DEBUG: Elements found - default:', !!defaultText, 'loading:', !!loadingText, 'button:', !!submitBtn);
            
            if (defaultText && loadingText && submitBtn) {
                if (isLoading) {
                    defaultText.style.display = 'none';
                    loadingText.style.display = 'inline-flex';
                    submitBtn.disabled = true;
                    submitBtn.classList.add('loading');
                } else {
                    defaultText.style.display = 'inline';
                    loadingText.style.display = 'none';
                    submitBtn.disabled = false;
                    submitBtn.classList.remove('loading');
                }
                console.log('🔍 DEBUG: Button state updated - default display:', defaultText.style.display, 'loading display:', loadingText.style.display);
            }
        }
        
        // Handle HTMX events
        document.addEventListener('htmx:beforeRequest', function(event) {
            console.log('HTMX request starting:', event.detail);
            console.log('Target:', event.detail.target);
            console.log('Request method:', event.detail.xhr.method);
            console.log('Form data:', event.detail.parameters);
            
            // Show loading state when form is submitted
            const form = document.querySelector('.login-form');
            if (form && event.target === form) {
                console.log('🔍 DEBUG: Setting button to loading state');
                setButtonLoadingState(true);
            }
        });
        
        document.addEventListener('htmx:afterRequest', function(event) {
            console.log('HTMX request completed:', event.detail);
            console.log('Response status:', event.detail.xhr.status);
            console.log('Response headers:', event.detail.xhr.getAllResponseHeaders());
            console.log('Response body:', event.detail.xhr.responseText);
            console.log('Target element:', event.detail.target);
            
            const form = document.querySelector('.login-form');
            if (form && event.target === form) {
                console.log('🔍 DEBUG: Resetting button to default state');
                // Reset loading state
                setButtonLoadingState(false);
                
                // Handle successful login redirect
                if (event.detail.xhr.status === 200) {
                    // Check if there's a redirect header
                    const redirectHeader = event.detail.xhr.getResponseHeader('HX-Redirect');
                    if (redirectHeader) {
                        window.location.href = redirectHeader;
                    }
                }
            }
        });
        
        // Handle HTMX errors
        document.addEventListener('htmx:responseError', function(event) {
            console.log('HTMX response error:', event.detail);
            const form = document.querySelector('.login-form');
            if (form && event.target === form) {
                console.log('🔍 DEBUG: Resetting button after error');
                // Reset loading state
                setButtonLoadingState(false);
            }
        });
        
        // Handle HTMX swap
        document.addEventListener('htmx:beforeSwap', function(event) {
            console.log('HTMX before swap:', event.detail);
            console.log('Swap target:', event.detail.target);
            console.log('Swap content:', event.detail.content);
        });
        
        document.addEventListener('htmx:afterSwap', function(event) {
            console.log('HTMX after swap:', event.detail);
            console.log('Swap target:', event.detail.target);
        });
        
        // Handle form submission
        document.addEventListener('submit', function(event) {
            console.log('Form submission event:', event);
            console.log('Form target:', event.target);
            console.log('Form action:', event.target.action);
            console.log('Form method:', event.target.method);
        });
    </script>
</body>
</html>