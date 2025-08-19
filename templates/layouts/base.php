<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'Sugar Clone') ?></title>
    
    <!-- Meta tags -->
    <meta name="description" content="<?= htmlspecialchars($description ?? 'A modern, responsive landing page built with the latest web technologies') ?>">
    <meta name="csrf-token" content="<?= htmlspecialchars($_ENV['CSRF_TOKEN'] ?? '') ?>">
    
    <!-- CSS -->
    <link rel="stylesheet" href="/assets/css/master.css">
    <link rel="stylesheet" href="/assets/css/sugar.css">
    
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
                    <li><a href="#products">Products</a></li>
                    <li><a href="#about">About</a></li>
                    <li><a href="#contact">Contact</a></li>
                </ul>
            </nav>
        </div>
    </header>
    
    <!-- Main Content -->
    <main class="main" role="main">
        <?= $content ?? '' ?>
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
