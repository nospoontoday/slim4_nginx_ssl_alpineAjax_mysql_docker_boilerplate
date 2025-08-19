<?php
/**
 * @file landing.php
 * @purpose Sugar.ie landing page with semantic HTML5 structure
 * @ai-context Main landing page for Sugar.ie
 */

declare(strict_types=1);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'Sugar.ie - Ireland\'s Dedicated Sugar Dating Platform') ?></title>
    
    <!-- Meta tags -->
    <meta name="description" content="<?= htmlspecialchars($description ?? 'Sugar.ie is Ireland\'s dedicated sugar dating platform connecting attractive sugar babies with successful sugar daddies. 100% REAL EXPERIENCE with genuine profiles—no fakes or bots.') ?>">
    <meta name="keywords" content="sugar dating, sugar daddy, sugar baby, dating platform, Ireland dating">
    <meta name="author" content="Sugar.ie">
    
    <!-- CSS -->
    <link rel="stylesheet" href="/assets/css/landing.css">
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- HTMX -->
    <script src="https://unpkg.com/htmx.org@2.0.3/dist/htmx.min.js"></script>
    
    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.14.1/dist/cdn.min.js"></script>
</head>
<body>
    <!-- Header -->
    <header class="header" role="banner">
        <div class="container">
            <nav class="nav" role="navigation" aria-label="Main navigation" x-data="{ mobileMenuOpen: false }">
                <div class="logo-container">
                    <a href="/" class="logo" aria-label="Sugar.ie Home">
                        Sugar.ie
                    </a>
                </div>
                
                <!-- Mobile menu button -->
                <button 
                    class="mobile-menu-toggle" 
                    @click="mobileMenuOpen = !mobileMenuOpen" 
                    :aria-expanded="mobileMenuOpen" 
                    aria-controls="mobile-menu"
                    aria-label="Toggle navigation menu"
                >
                    <span :class="{ 'hidden': mobileMenuOpen }">Menu</span>
                    <span :class="{ 'hidden': !mobileMenuOpen }">Close</span>
                </button>
                
                <ul class="nav-menu" id="mobile-menu" x-show="mobileMenuOpen" @click.outside="mobileMenuOpen = false">
                    <li><a href="#home" aria-current="page">Home</a></li>
                    <li><a href="#sugar-do">What We Do</a></li>
                    <li><a href="#about">About</a></li>
                    <li><a href="#locations">Locations</a></li>
                    <li><a href="#blog">Blog</a></li>
                </ul>
            </nav>
        </div>
    </header>
    
    <!-- Main Content -->
    <main class="main" role="main">
        <!-- Hero Section -->
        <section class="hero" id="home">
            <div class="hero__container">
                <div class="hero__content">
                    <h1 class="hero__title">
                        Ireland's <span class="text-secondary">Dedicated</span>
                        <br>
                        <strong>Sugar Dating Platform</strong>
                    </h1>
                    <p class="hero__subtitle">
                        Sugar.ie is a modern dating platform connecting attractive sugar babies with successful sugar daddies in Ireland.
                        <br><br>
                        We offer a <strong>100% REAL EXPERIENCE</strong> with genuine profiles—no fakes or bots.
                        <br><br>
                        Discover mutually beneficial connections in a fresh, authentic way.
                    </p>
                    <div class="hero__buttons">
                        <a href="/register" class="btn btn--primary btn--lg" aria-label="Get started with Sugar.ie">I'm ready to get started</a>
                        <a href="#sugar-do" class="btn btn--secondary btn--lg" aria-label="Explore Sugar.ie features">Explore</a>
                    </div>
                </div>
                <div class="hero__image">
                    <img src="https://via.placeholder.com/600x400/2969ff/ffffff?text=Sugar.ie+App+Mockup" alt="Sugar.ie mobile app mockup">
                </div>
            </div>
        </section>

        <!-- Form Section -->
        <section class="form-section" x-data="{ name: '', email: '', password: '' }">
            <div class="form-section__container">
                <h2 class="form-section__title">Join Sugar.ie Today</h2>
                <p class="form-section__subtitle">
                    Create your profile and start connecting with like-minded individuals in Ireland's premium sugar dating community.
                </p>
                <form class="form-section__form" action="/register" method="POST" hx-post="/register" hx-swap="outerHTML">
                    <input type="text" name="name" placeholder="Your Name" required aria-label="Your Name" x-model="name">
                    <input type="email" name="email" placeholder="Your Email" required aria-label="Your Email" x-model="email">
                    <input type="password" name="password" placeholder="Password" required aria-label="Password" x-model="password">
                    <button type="submit" class="btn btn--primary btn--lg" :disabled="!name || !email || !password" :class="{ 'opacity-50': !name || !email || !password }" aria-label="Create account">Create Account</button>
                </form>
            </div>
        </section>

        <!-- What Does Section -->
        <section class="what-does" id="sugar-do">
            <div class="what-does__container">
                <h2 class="what-does__title">What Does <strong>Sugar.ie</strong> Do?</h2>
                <div class="what-does__content">
                    <div class="what-does__text">
                        <h3>
                            <strong>Sugar.ie</strong> is the number one premium website for sugar daddies or babies in Ireland
                        </h3>
                        <div class="features">
                            <div class="feature">
                                <div class="icon">
                                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                        <path d="M12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22Z" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M8 12H16" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M12 8V16" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </div>
                                <div class="text">
                                    <h4>Big Community</h4>
                                    <p>
                                        You are in the right place. This is the online home of all people who are into Sugar Dating in Ireland. 
                                        Get comfortable and browse through Sugar.ie to get yourself familiar with all the amazing opportunities 
                                        your new lifestyle can bring you.
                                    </p>
                                </div>
                            </div>
                            <div class="feature">
                                <div class="icon">
                                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                        <path d="M9 12L11 14L15 10M21 12C21 16.9706 16.9706 21 12 21C7.02944 21 3 16.9706 3 12C3 7.02944 7.02944 3 12 3C16.9706 3 21 7.02944 21 12Z" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </div>
                                <div class="text">
                                    <h4>Quality</h4>
                                    <p>
                                        Thanks to verifying our users, we know you will not be dissatisfied with the quality of people you will 
                                        come across on Sugar.ie. Go ahead, sign up to our platform, and hit that search button to see the profiles 
                                        of people available for Sugar Dating near you. You'll thank us later.
                                    </p>
                                </div>
                            </div>
                            <div class="feature">
                                <div class="icon">
                                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                        <path d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </div>
                                <div class="text">
                                    <h4>Treat Yourself</h4>
                                    <p>
                                        You are here because you are curious about Sugar Dating and you have the habit of making the right decisions. 
                                        That's how you have found Sugar.ie. Think about it like this - through all the options you have in your life, 
                                        you were able to get here and find the platform built for people like you. As soon as you indulge in your 
                                        new Sugar Lifestyle, you will know, that this was a great choice.
                                    </p>
                                </div>
                            </div>
                            <div class="feature">
                                <div class="icon">
                                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                        <path d="M12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22Z" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M12 16V12" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M12 8H12.01" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </div>
                                <div class="text">
                                    <h4>Find Real Gentleman</h4>
                                    <p>
                                        No fakes, only real people who are into Sugar Dating - that's the truth. If you struggle to meet quality 
                                        people in your life, this is a solution for you. Multiple search filters allow you to browse through 
                                        hundreds of like-minded people, who cannot wait to meet you.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="what-does__image">
                        <img src="https://via.placeholder.com/600x400/2969ff/ffffff?text=Sugar.ie+Features" alt="Sugar.ie features">
                    </div>
                </div>
            </div>
        </section>

        <!-- About Section -->
        <section class="about" id="about">
            <div class="about__content">
                <div class="about__text">
                    <h2>About <strong>Sugar.ie</strong></h2>
                    <p>
                        Where Sugar Babies enjoy a life of luxury by being pampered with fine dinners, exotic trips and allowances. 
                        In turn, Sugar Daddies or Mommas find beautiful members to accompany them at all times.
                    </p>
                    <p>
                        We want relationships to be balanced. We give our members a place for this to happen.
                    </p>
                    <ul class="about__features">
                        <li>Verified profiles for authenticity</li>
                        <li>Advanced search and matching algorithms</li>
                        <li>Secure messaging system</li>
                        <li>Discreet and confidential service</li>
                        <li>Events and meetups for members</li>
                    </ul>
                </div>
                <div class="about__image">
                    <img src="https://via.placeholder.com/500x400/2969ff/ffffff?text=About+Sugar.ie" alt="About Sugar.ie">
                </div>
            </div>
        </section>

        <!-- Locations Section -->
        <section class="locations" id="locations">
            <div class="locations__container">
                <h2 class="locations__title">Find <strong>Sugar Daddies and Babies</strong> in Ireland</h2>
                <div class="locations__carousel">
                    <!-- This would be populated with location data -->
                    <div class="location-card">
                        <img src="https://via.placeholder.com/300x200/2969ff/ffffff?text=Dublin" alt="Dublin">
                        <h3>Dublin</h3>
                    </div>
                    <div class="location-card">
                        <img src="https://via.placeholder.com/300x200/2969ff/ffffff?text=Cork" alt="Cork">
                        <h3>Cork</h3>
                    </div>
                    <div class="location-card">
                        <img src="https://via.placeholder.com/300x200/2969ff/ffffff?text=Galway" alt="Galway">
                        <h3>Galway</h3>
                    </div>
                    <div class="location-card">
                        <img src="https://via.placeholder.com/300x200/2969ff/ffffff?text=Belfast" alt="Belfast">
                        <h3>Belfast</h3>
                    </div>
                </div>
            </div>
        </section>

        <!-- As Seen On Section -->
        <section class="as-seen-on">
            <div class="as-seen-on__container">
                <h2 class="as-seen-on__title">As <strong>seen on</strong></h2>
                <div class="as-seen-on__logos">
                    <img src="https://via.placeholder.com/150x50/cccccc/ffffff?text=Media+1" alt="Media 1">
                    <img src="https://via.placeholder.com/150x50/cccccc/ffffff?text=Media+2" alt="Media 2">
                    <img src="https://via.placeholder.com/150x50/cccccc/ffffff?text=Media+3" alt="Media 3">
                    <img src="https://via.placeholder.com/150x50/cccccc/ffffff?text=Media+4" alt="Media 4">
                </div>
            </div>
        </section>

        <!-- Blog Section -->
        <section class="blog" id="blog">
            <div class="blog__container">
                <h2 class="blog__title">Our <strong>Blog</strong></h2>
                <div class="blog__posts">
                    <!-- This would be populated with blog post data -->
                    <article class="blog-post">
                        <img src="https://via.placeholder.com/400x250/2969ff/ffffff?text=Blog+Post+1" alt="Blog Post 1">
                        <h3>How to Create the Perfect Sugar Dating Profile</h3>
                        <p>Learn the essential tips for crafting a profile that attracts the right matches on Sugar.ie...</p>
                        <a href="/blog/post-1" class="btn btn--secondary">Read More</a>
                    </article>
                    <article class="blog-post">
                        <img src="https://via.placeholder.com/400x250/2969ff/ffffff?text=Blog+Post+2" alt="Blog Post 2">
                        <h3>Navigating First Meetings Safely</h3>
                        <p>Important safety guidelines for your first sugar dating encounter...</p>
                        <a href="/blog/post-2" class="btn btn--secondary">Read More</a>
                    </article>
                    <article class="blog-post">
                        <img src="https://via.placeholder.com/400x250/2969ff/ffffff?text=Blog+Post+3" alt="Blog Post 3">
                        <h3>Understanding Sugar Dating Dynamics</h3>
                        <p>Exploring the different types of sugar dating relationships and what to expect...</p>
                        <a href="/blog/post-3" class="btn btn--secondary">Read More</a>
                    </article>
                    <article class="blog-post">
                        <img src="https://via.placeholder.com/400x250/2969ff/ffffff?text=Blog+Post+4" alt="Blog Post 4">
                        <h3>Maximizing Your Sugar.ie Experience</h3>
                        <p>Expert advice on how to make the most of your Sugar.ie membership...</p>
                        <a href="/blog/post-4" class="btn btn--secondary">Read More</a>
                    </article>
                </div>
            </div>
        </section>
    </main>
    
    <!-- Footer -->
    <footer class="footer" role="contentinfo">
        <div class="container">
            <p>&copy; <?= date('Y') ?> Sugar.ie. All rights reserved.</p>
        </div>
    </footer>
    
    <!-- Notification Container -->
    <div id="notifications" aria-live="polite"></div>
</body>
</html>