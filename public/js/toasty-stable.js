/**
 * Toasty Stable System - Layout-stable toast notifications
 * Menggunakan design yang konsisten dengan theme #667eea → #764ba2
 */

class ToastySystem {
    constructor() {
        this.containers = {};
        this.toasts = new Map();
        this.defaultConfig = {
            duration: 3000,
            position: 'top-right',
            closable: true,
            progress: true,
            pauseOnHover: true,
            animation: true
        };
        this.init();
    }

    init() {
        // Create containers for different positions
        const positions = [
            'top-right', 'top-left', 'top-center',
            'bottom-right', 'bottom-left', 'bottom-center'
        ];

        positions.forEach(position => {
            this.containers[position] = this.createContainer(position);
        });

        // Add global styles if not exists
        this.injectStyles();
    }

    createContainer(position) {
        const container = document.createElement('div');
        container.className = `toasty-container ${position}`;
        container.setAttribute('data-position', position);
        document.body.appendChild(container);
        return container;
    }

    injectStyles() {
        if (document.getElementById('toasty-stable-styles')) return;

        const style = document.createElement('style');
        style.id = 'toasty-stable-styles';
        style.textContent = `
            .toasty-container {
                position: fixed;
                z-index: 9999;
                pointer-events: none;
                display: flex;
                flex-direction: column;
                gap: 12px;
                max-height: 100vh;
                overflow: hidden;
            }
        `;
        document.head.appendChild(style);
    }

    show(type, message, options = {}) {
        const config = { ...this.defaultConfig, ...options };
        const toast = this.createToast(type, message, config);
        const container = this.containers[config.position];

        // Add to container
        if (config.position.includes('bottom')) {
            container.insertBefore(toast, container.firstChild);
        } else {
            container.appendChild(toast);
        }

        // Store toast reference
        const toastId = this.generateId();
        this.toasts.set(toastId, toast);

        // Animate in
        if (config.animation) {
            toast.classList.add('entering');
            setTimeout(() => {
                toast.classList.remove('entering');
            }, 300);
        }

        // Auto remove
        if (config.duration > 0) {
            this.scheduleRemoval(toastId, config.duration, config.pauseOnHover);
        }

        // Add progress bar
        if (config.progress && config.duration > 0) {
            this.addProgressBar(toast, config.duration);
        }

        return {
            id: toastId,
            element: toast,
            remove: () => this.remove(toastId)
        };
    }

    createToast(type, message, config) {
        const toast = document.createElement('div');
        toast.className = `toasty ${type}`;

        // Icon
        const icon = document.createElement('div');
        icon.className = 'toasty-icon';
        icon.innerHTML = this.getIcon(type);

        // Content
        const content = document.createElement('div');
        content.className = 'toasty-content';
        content.textContent = message;

        // Close button
        let closeBtn = null;
        if (config.closable) {
            closeBtn = document.createElement('button');
            closeBtn.className = 'toasty-close';
            closeBtn.innerHTML = '×';
            closeBtn.onclick = () => this.remove(toast);
        }

        // Assemble toast
        toast.appendChild(icon);
        toast.appendChild(content);
        if (closeBtn) toast.appendChild(closeBtn);

        // Add action button if provided
        if (config.action) {
            const actionBtn = document.createElement('button');
            actionBtn.className = 'toasty-action';
            actionBtn.textContent = config.action.text;
            actionBtn.onclick = () => {
                config.action.callback();
                this.remove(toast);
            };
            toast.appendChild(actionBtn);
        }

        return toast;
    }

    getIcon(type) {
        const icons = {
            success: '✓',
            error: '✕',
            warning: '⚠',
            info: 'ℹ',
            primary: '★',
            loading: '<div class="toasty-spinner"></div>'
        };
        return icons[type] || icons.info;
    }

    addProgressBar(toast, duration) {
        const progress = document.createElement('div');
        progress.className = 'toasty-progress';
        progress.style.animationDuration = duration + 'ms';
        toast.appendChild(progress);
    }

    scheduleRemoval(toastId, duration, pauseOnHover) {
        const toast = this.toasts.get(toastId);
        if (!toast) return;

        let timeoutId;
        let startTime = Date.now();
        let remainingTime = duration;

        const scheduleTimeout = () => {
            timeoutId = setTimeout(() => {
                this.remove(toastId);
            }, remainingTime);
        };

        if (pauseOnHover) {
            toast.addEventListener('mouseenter', () => {
                clearTimeout(timeoutId);
                remainingTime -= Date.now() - startTime;
            });

            toast.addEventListener('mouseleave', () => {
                startTime = Date.now();
                scheduleTimeout();
            });
        }

        scheduleTimeout();
    }

    remove(toastId) {
        const toast = typeof toastId === 'string' ? this.toasts.get(toastId) : toastId;
        if (!toast || !toast.parentNode) return;

        // Animate out
        toast.classList.add('exiting');
        
        setTimeout(() => {
            if (toast.parentNode) {
                toast.parentNode.removeChild(toast);
            }
            if (typeof toastId === 'string') {
                this.toasts.delete(toastId);
            }
        }, 200);
    }

    removeAll() {
        this.toasts.forEach((toast, id) => {
            this.remove(id);
        });
    }

    generateId() {
        return 'toast_' + Date.now() + '_' + Math.random().toString(36).substr(2, 9);
    }

    // Utility methods
    sequence(toasts, delay = 500) {
        toasts.forEach((toast, index) => {
            setTimeout(() => {
                this.show(toast.type, toast.message, toast.options);
            }, index * delay);
        });
    }

    showAt(type, message, position, options = {}) {
        return this.show(type, message, { ...options, position });
    }

    progress(message, progressValue = 0, options = {}) {
        const toast = this.show('primary', '', {
            ...options,
            duration: -1,
            progress: false
        });

        // Add custom progress
        const progressContainer = document.createElement('div');
        progressContainer.className = 'toasty-progress-container';
        
        const progressBar = document.createElement('div');
        progressBar.className = 'toasty-progress-bar';
        progressBar.style.width = progressValue + '%';
        
        progressContainer.appendChild(progressBar);
        
        // Update content
        const content = toast.element.querySelector('.toasty-content');
        content.innerHTML = `
            <div>${message}</div>
            <div class="toasty-progress-container">
                <div class="toasty-progress-bar" style="width: ${progressValue}%"></div>
            </div>
        `;

        return {
            ...toast,
            updateProgress: (value) => {
                const bar = toast.element.querySelector('.toasty-progress-bar');
                if (bar) bar.style.width = value + '%';
            }
        };
    }
}

// Initialize global Toasty system
const toasty = new ToastySystem();

// Global functions for easy access
window.toastSuccess = function(message, options = {}) {
    return toasty.show('success', message, options);
};

window.toastError = function(message, options = {}) {
    return toasty.show('error', message, { duration: 4000, ...options });
};

window.toastWarning = function(message, options = {}) {
    return toasty.show('warning', message, { duration: 3500, ...options });
};

window.toastInfo = function(message, options = {}) {
    return toasty.show('info', message, options);
};

window.toastPrimary = function(message, options = {}) {
    return toasty.show('primary', message, options);
};

window.toastLoading = function(message, options = {}) {
    return toasty.show('loading', message, { 
        duration: -1, 
        closable: options.closable !== false,
        ...options 
    });
};

// Utility object
window.ToastyUtils = {
    clearAll: () => toasty.removeAll(),
    
    sequence: (toasts, delay = 500) => toasty.sequence(toasts, delay),
    
    showAt: (type, message, position, options = {}) => 
        toasty.showAt(type, message, position, options),
    
    withAction: (type, message, actionText, actionCallback, options = {}) => 
        toasty.show(type, message, {
            ...options,
            action: {
                text: actionText,
                callback: actionCallback
            }
        }),
    
    progress: (message, progressValue = 0, options = {}) => 
        toasty.progress(message, progressValue, options)
};

// Integration dengan sistem delete yang sudah ada
window.showModernSuccess = function(message, options = {}) {
    return toastSuccess(message, options);
};

window.showModernError = function(title, message, options = {}) {
    return toastError(message || title, options);
};

window.showModernWarning = function(message, options = {}) {
    return toastWarning(message, options);
};

window.showModernInfo = function(message, options = {}) {
    return toastInfo(message, options);
};

// Backward compatibility dengan SweetAlert2 toast
if (typeof Swal !== 'undefined') {
    const originalSwalFire = Swal.fire;
    Swal.fire = function(options) {
        if (options && options.toast) {
            const message = options.title || options.text || '';
            const icon = options.icon;
            const duration = options.timer || 3000;
            
            switch(icon) {
                case 'success':
                    return toastSuccess(message, { duration });
                case 'error':
                    return toastError(message, { duration: duration || 4000 });
                case 'warning':
                    return toastWarning(message, { duration: duration || 3500 });
                case 'info':
                    return toastInfo(message, { duration });
                default:
                    return toastPrimary(message, { duration });
            }
        }
        
        return originalSwalFire.apply(this, arguments);
    };
}

// Auto-initialize when DOM is ready
// document.addEventListener('DOMContentLoaded', function() {
//     console.log('✅ Toasty Stable System initialized');
//     console.log('✅ Layout-stable toast notifications ready');
    
//     // Show initialization toast
//     setTimeout(() => {
//         toastInfo('Toasty Stable System Ready!', {
//             duration: 2000
//         });
//     }, 1000);
// });

// Export untuk module systems
if (typeof module !== 'undefined' && module.exports) {
    module.exports = {
        toastSuccess,
        toastError,
        toastWarning,
        toastInfo,
        toastPrimary,
        toastLoading,
        ToastyUtils
    };
}
