<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'Our App') ?></title>
    
    <!-- Meta tags -->
    <meta name="description" content="<?= htmlspecialchars($description ?? 'A modern PHP application') ?>">
    <meta name="csrf-token" content="<?= htmlspecialchars($_ENV['CSRF_TOKEN'] ?? '') ?>">
    
    <!-- CSS -->
    <link rel="stylesheet" href="/assets/css/master.css">
    
    <!-- HTMX -->
    <script src="https://unpkg.com/htmx.org@2.0.3/dist/htmx.min.js"></script>
    
    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.14.1/dist/cdn.min.js"></script>
    
    <!-- Custom JavaScript -->
    <script src="/assets/js/app.js"></script>
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="container">
            <nav class="nav">
                <div class="logo-container">
                    <a href="/" class="logo">
                        <h1>Our App</h1>
                    </a>
                </div>
                
                <ul class="nav-menu">
                    <li><a href="/">Home</a></li>
                    <li><a href="/dashboard">Dashboard</a></li>
                    <li><a href="/login">Login</a></li>
                    <li><a href="/register">Register</a></li>
                </ul>
            </nav>
        </div>
    </header>
    
    <!-- Main Content -->
    <main class="main">
        <div class="container">
            <?= $content ?? '' ?>
        </div>
    </main>
    
    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <p>&copy; <?= date('Y') ?> Our App. All rights reserved.</p>
        </div>
    </footer>
    
    <!-- Notification Container -->
    <div id="notifications"></div>
</body>
</html>
