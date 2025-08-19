<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'Sign Up | Sugar Clone') ?></title>
    
    <!-- Meta tags -->
    <meta name="description" content="<?= htmlspecialchars($description ?? 'Join Sugar Clone, the premium sugar dating platform. Create your profile and connect with attractive sugar babies and generous sugar daddies!') ?>">
    <meta name="csrf-token" content="<?= htmlspecialchars($_ENV['CSRF_TOKEN'] ?? '') ?>">
    
    <!-- CSS -->
    <link rel="stylesheet" href="/assets/css/master.css">
    <link rel="stylesheet" href="/assets/css/sugar.css">
    <link rel="stylesheet" href="/assets/css/register.css">
    
    <!-- HTMX -->
    <script src="https://unpkg.com/htmx.org@2.0.3/dist/htmx.min.js"></script>
    
    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.14.1/dist/cdn.min.js"></script>
    
    <!-- Custom JavaScript -->
    <script src="/assets/js/app.js"></script>
</head>
<body>
    <!-- Header -->
    <header class="header" role="banner">
        <div class="container">
            <nav class="nav" role="navigation" aria-label="Main navigation">
                <div class="logo-container">
                    <a href="/" class="logo" aria-label="Sugar Clone Home">
                        <h1>Sugar Clone</h1>
                    </a>
                </div>
                
                <ul class="nav-menu">
                    <li><a href="/">Home</a></li>
                    <li><a href="/#products">Products</a></li>
                    <li><a href="/#about">About</a></li>
                    <li><a href="/#contact">Contact</a></li>
                    <li><a href="/login">Login</a></li>
                </ul>
            </nav>
        </div>
    </header>
    
    <!-- Main Content -->
    <main class="main" role="main">
        <div class="register-container">
            <div class="register-form-wrapper">
                <form 
                    id="register-form" 
                    class="register-form" 
                    action="/api/v1/auth/register" 
                    method="POST"
                    hx-post="/api/v1/auth/register"
                    hx-swap="outerHTML"
                    hx-target="#register-form"
                >
                    <div class="form-header">
                        <h1 class="form-title">
                            Sign up <span class="font-semibold">now</span>
                            <br>and <span class="text-accent">find your sugar</span>
                        </h1>
                    </div>
                    
                    <div class="form-fields">
                        <div class="form-field">
                            <label for="name" class="form-label">Username</label>
                            <input 
                                type="text" 
                                id="name" 
                                name="name" 
                                class="form-input" 
                                placeholder="Your username"
                                required
                                maxlength="20"
                                pattern="[a-zA-Z0-9]+"
                                title="Username must be 20 characters max, letters and numbers only"
                                aria-describedby="name-help"
                            >
                            <div id="name-help" class="form-help">
                                Create a unique username (20 characters max, letters and numbers only)
                            </div>
                        </div>
                        
                        <div class="form-field">
                            <label for="email" class="form-label">Email</label>
                            <input 
                                type="email" 
                                id="email" 
                                name="email" 
                                class="form-input" 
                                placeholder="Your email"
                                required
                                aria-describedby="email-help"
                            >
                            <div id="email-help" class="form-help">
                                Use a real email, you will need to verify your account in the last register step
                            </div>
                        </div>
                        
                        <div class="form-field">
                            <label for="password" class="form-label">Password</label>
                            <input 
                                type="password" 
                                id="password" 
                                name="password" 
                                class="form-input" 
                                placeholder="Your password"
                                required
                                minlength="8"
                                maxlength="16"
                                aria-describedby="password-help"
                            >
                            <div id="password-help" class="form-help">
                                Passwords must include: 8-16 characters, at least one uppercase letter (A-Z), 
                                at least one lowercase letter (a-z), at least one number (0-9), 
                                at least one symbol (e.g., !, @, #, $)
                            </div>
                        </div>
                        
                        <div class="form-field checkbox-field">
                            <label class="checkbox-label">
                                <input 
                                    type="checkbox" 
                                    name="terms" 
                                    required
                                    class="checkbox-input"
                                    aria-describedby="terms-help"
                                >
                                <span class="checkbox-text">
                                    By continuing, you are agreeing to our 
                                    <a href="/terms-and-conditions" class="link-primary">terms and conditions</a> 
                                    and 
                                    <a href="/privacy-policy" class="link-primary">privacy policy</a>.
                                </span>
                            </label>
                        </div>
                        
                        <div class="form-actions">
                            <button type="submit" class="btn btn--primary btn--lg">
                                Next
                            </button>
                        </div>
                        
                        <div class="form-footer">
                            <span class="form-footer-text">Already have an account?</span>
                            <a href="/login" class="link-primary">Sign in now!</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </main>
    
    <!-- Footer -->
    <footer class="footer" role="contentinfo">
        <div class="container">
            <p>&copy; <?= date('Y') ?> Sugar Clone. All rights reserved.</p>
        </div>
    </footer>
    
    <!-- Notification Container -->
    <div id="notifications" aria-live="polite"></div>
</body>
</html>
