<?php
/**
 * Home Page Template
 * 
 * @ai-purpose Welcome page with HTMX examples
 * @ai-pattern Uses base layout and demonstrates HTMX functionality
 */
?>

<div class="hero">
    <h1><?= htmlspecialchars($title) ?></h1>
    <p class="hero-description"><?= htmlspecialchars($description) ?></p>
    
    <div class="hero-actions">
        <a href="/register" class="btn btn-primary">Get Started</a>
        <a href="/login" class="btn btn-secondary">Sign In</a>
    </div>
</div>

<div class="features">
    <h2>Features</h2>
    
    <div class="feature-grid">
        <div class="feature-card">
            <h3>Modern PHP 8.3</h3>
            <p>Built with the latest PHP features including strict types, JIT compilation, and modern syntax.</p>
        </div>
        
        <div class="feature-card">
            <h3>HTMX Integration</h3>
            <p>Dynamic content loading without writing JavaScript. Modern web development simplified.</p>
        </div>
        
        <div class="feature-card">
            <h3>Alpine.js</h3>
            <p>Lightweight reactive framework for interactive components. Only 15KB gzipped.</p>
        </div>
        
        <div class="feature-card">
            <h3>Component Library</h3>
            <p>Reusable UI components built with our design system. Consistent and maintainable.</p>
        </div>
    </div>
</div>

<!-- HTMX Example: Live Search -->
<div class="htmx-demo">
    <h2>HTMX Demo: Live Search</h2>
    
    <div class="search-container">
        <input type="search"
               name="q"
               placeholder="Search users..."
               hx-get="/htmx/search-results"
               hx-trigger="input changed delay:500ms"
               hx-target="#search-results"
               hx-indicator="#search-spinner"
               class="form-input">
        
        <div id="search-spinner" class="htmx-indicator">
            <span class="spinner"></span> Searching...
        </div>
    </div>
    
    <div id="search-results">
        <p class="text-muted">Start typing to search users...</p>
    </div>
</div>

<!-- Alpine.js Example: Counter -->
<div class="alpine-demo">
    <h2>Alpine.js Demo: Interactive Counter</h2>
    
    <div x-data="{ count: 0 }" class="counter-container">
        <p>Count: <span x-text="count"></span></p>
        
        <div class="counter-actions">
            <button @click="count--" class="btn btn-secondary">-</button>
            <button @click="count++" class="btn btn-primary">+</button>
            <button @click="count = 0" class="btn btn-secondary">Reset</button>
        </div>
    </div>
</div>

<!-- HTMX Example: Dynamic Content Loading -->
<div class="htmx-demo">
    <h2>HTMX Demo: Dynamic Content</h2>
    
    <div class="content-actions">
        <button hx-get="/htmx/users-table"
                hx-target="#dynamic-content"
                hx-indicator="#content-spinner"
                class="btn btn-primary">
            Load Users Table
        </button>
        
        <button hx-get="/htmx/posts-list"
                hx-target="#dynamic-content"
                hx-indicator="#content-spinner"
                class="btn btn-secondary">
            Load Posts List
        </button>
    </div>
    
    <div id="content-spinner" class="htmx-indicator">
        <span class="spinner"></span> Loading content...
    </div>
    
    <div id="dynamic-content">
        <p class="text-muted">Click a button above to load dynamic content...</p>
    </div>
</div>

<style>
.hero {
    text-align: center;
    padding: var(--spacing-8) 0;
    background: linear-gradient(135deg, var(--brand-primary), var(--brand-secondary));
    color: var(--color-white);
    border-radius: var(--border-radius-lg);
    margin-bottom: var(--spacing-8);
}

.hero h1 {
    font-size: var(--font-size-5xl);
    margin-bottom: var(--spacing-4);
}

.hero-description {
    font-size: var(--font-size-xl);
    margin-bottom: var(--spacing-6);
    opacity: 0.9;
}

.hero-actions {
    display: flex;
    gap: var(--spacing-4);
    justify-content: center;
}

.features {
    margin-bottom: var(--spacing-8);
}

.feature-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: var(--spacing-6);
    margin-top: var(--spacing-6);
}

.feature-card {
    background: var(--color-white);
    padding: var(--spacing-6);
    border-radius: var(--border-radius-lg);
    box-shadow: var(--shadow);
    border: 1px solid var(--color-gray-200);
}

.feature-card h3 {
    color: var(--brand-primary);
    margin-bottom: var(--spacing-3);
}

.htmx-demo, .alpine-demo {
    margin-bottom: var(--spacing-8);
    padding: var(--spacing-6);
    background: var(--color-gray-50);
    border-radius: var(--border-radius-lg);
    border: 1px solid var(--color-gray-200);
}

.search-container {
    margin-bottom: var(--spacing-4);
}

.counter-container {
    text-align: center;
}

.counter-actions {
    display: flex;
    gap: var(--spacing-3);
    justify-content: center;
    margin-top: var(--spacing-4);
}

.content-actions {
    display: flex;
    gap: var(--spacing-3);
    margin-bottom: var(--spacing-4);
}

#dynamic-content {
    min-height: 100px;
    padding: var(--spacing-4);
    background: var(--color-white);
    border-radius: var(--border-radius);
    border: 1px solid var(--color-gray-200);
}
</style>
