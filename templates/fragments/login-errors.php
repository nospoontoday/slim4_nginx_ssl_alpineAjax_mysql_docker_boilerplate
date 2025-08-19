<?php
/**
 * @file login-errors.php
 * @purpose Error fragment for login form HTMX responses
 * @ai-context HTMX fragment for displaying validation errors
 */

if (!empty($errors)): ?>
<div class="alert alert-error login-errors" role="alert">
    <?php if (isset($errors['general'])): ?>
        <div class="error-message">
            <svg class="error-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="10"></circle>
                <line x1="15" y1="9" x2="9" y2="15"></line>
                <line x1="9" y1="9" x2="15" y2="15"></line>
            </svg>
            <span><?= htmlspecialchars($errors['general'][0]) ?></span>
        </div>
    <?php endif; ?>
    
    <?php if (isset($errors['email'])): ?>
        <div class="error-message">
            <svg class="error-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="10"></circle>
                <line x1="15" y1="9" x2="9" y2="15"></line>
                <line x1="9" y1="9" x2="15" y2="15"></line>
            </svg>
            <span><?= htmlspecialchars($errors['email'][0]) ?></span>
        </div>
    <?php endif; ?>
    
    <?php if (isset($errors['password'])): ?>
        <div class="error-message">
            <svg class="error-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="10"></circle>
                <line x1="15" y1="9" x2="9" y2="15"></line>
                <line x1="9" y1="9" x2="15" y2="15"></line>
            </svg>
            <span><?= htmlspecialchars($errors['password'][0]) ?></span>
        </div>
    <?php endif; ?>
</div>
<?php endif; ?>