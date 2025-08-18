/**
 * Main Application JavaScript
 * 
 * @ai-purpose Initialize Alpine.js and configure HTMX
 * @ai-dependencies Alpine.js 3.14.1, HTMX 2.0.3
 */

// Wait for DOM to be ready
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Alpine.js
    if (typeof Alpine !== 'undefined') {
        Alpine.start();
    }
    
    // HTMX configuration
    if (typeof htmx !== 'undefined') {
        // Configure HTMX defaults
        htmx.config.globalViewTransitions = true;
        htmx.config.includeIndicatorStyles = false;
        
        // Add request headers for API calls
        htmx.on('htmx:configRequest', function(evt) {
            // Add CSRF token if available
            const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            if (token) {
                evt.detail.headers['X-CSRF-TOKEN'] = token;
            }
            
            // Add authentication header if available
            const authToken = localStorage.getItem('auth_token');
            if (authToken) {
                evt.detail.headers['Authorization'] = `Bearer ${authToken}`;
            }
        });
        
        // Handle authentication errors
        htmx.on('htmx:responseError', function(evt) {
            if (evt.detail.xhr.status === 401) {
                // Redirect to login on authentication failure
                window.location.href = '/login';
            }
        });
        
        // Show loading indicators
        htmx.on('htmx:beforeRequest', function(evt) {
            const target = evt.detail.target;
            if (target) {
                target.classList.add('htmx-request');
            }
        });
        
        htmx.on('htmx:afterRequest', function(evt) {
            const target = evt.detail.target;
            if (target) {
                target.classList.remove('htmx-request');
            }
        });
    }
    
    // Initialize any custom components
    initializeComponents();
});

/**
 * Initialize custom components
 */
function initializeComponents() {
    // Initialize tooltips
    initializeTooltips();
    
    // Initialize modals
    initializeModals();
    
    // Initialize notifications
    initializeNotifications();
}

/**
 * Initialize tooltip functionality
 */
function initializeTooltips() {
    const tooltipElements = document.querySelectorAll('[data-tooltip]');
    
    tooltipElements.forEach(element => {
        element.addEventListener('mouseenter', function() {
            const tooltip = document.createElement('div');
            tooltip.className = 'tooltip';
            tooltip.textContent = this.getAttribute('data-tooltip');
            document.body.appendChild(tooltip);
            
            const rect = this.getBoundingClientRect();
            tooltip.style.position = 'absolute';
            tooltip.style.top = rect.top - tooltip.offsetHeight - 5 + 'px';
            tooltip.style.left = rect.left + (rect.width / 2) - (tooltip.offsetWidth / 2) + 'px';
            
            this._tooltip = tooltip;
        });
        
        element.addEventListener('mouseleave', function() {
            if (this._tooltip) {
                this._tooltip.remove();
                this._tooltip = null;
            }
        });
    });
}

/**
 * Initialize modal functionality
 */
function initializeModals() {
    const modalTriggers = document.querySelectorAll('[data-modal]');
    
    modalTriggers.forEach(trigger => {
        trigger.addEventListener('click', function(e) {
            e.preventDefault();
            const modalId = this.getAttribute('data-modal');
            const modal = document.getElementById(modalId);
            
            if (modal) {
                modal.classList.add('modal-open');
                document.body.classList.add('modal-open');
            }
        });
    });
    
    // Close modal on backdrop click
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('modal-backdrop')) {
            closeModal(e.target.closest('.modal'));
        }
    });
    
    // Close modal on escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const openModal = document.querySelector('.modal.modal-open');
            if (openModal) {
                closeModal(openModal);
            }
        }
    });
}

/**
 * Close modal
 */
function closeModal(modal) {
    if (modal) {
        modal.classList.remove('modal-open');
        document.body.classList.remove('modal-open');
    }
}

/**
 * Initialize notification system
 */
function initializeNotifications() {
    // Global notification function
    window.showNotification = function(message, type = 'info', duration = 5000) {
        const notification = document.createElement('div');
        notification.className = `notification notification-${type}`;
        notification.innerHTML = `
            <div class="notification-content">
                <span class="notification-message">${message}</span>
                <button class="notification-close" onclick="this.parentElement.parentElement.remove()">&times;</button>
            </div>
        `;
        
        document.body.appendChild(notification);
        
        // Auto-remove after duration
        setTimeout(() => {
            if (notification.parentElement) {
                notification.remove();
            }
        }, duration);
    };
}
