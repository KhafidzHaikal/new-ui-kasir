/**
 * Toastify Enhanced - Modern Toast System
 * Terintegrasi dengan theme #667eea → #764ba2
 */

// Default configuration untuk semua toast
const defaultToastConfig = {
    duration: 3000,
    gravity: "top",
    position: "right",
    stopOnFocus: true,
    close: true,
    style: {
        background: "linear-gradient(135deg, #667eea 0%, #764ba2 100%)",
    },
    onClick: function(){} // Callback after click
};

// Enhanced Toast Success
window.toastSuccess = function(message, options = {}) {
    const config = {
        ...defaultToastConfig,
        text: message,
        duration: options.duration || 3000,
        className: "toast-success",
        style: {
            background: "linear-gradient(135deg, #4CAF50 0%, #45a049 100%)",
        },
        ...options
    };
    
    // Add content wrapper for icon spacing
    const toastElement = Toastify(config);
    
    // Show toast
    toastElement.showToast();
    
    // Add progress bar if enabled
    if (options.progress !== false) {
        addProgressBar(toastElement, config.duration);
    }
    
    return toastElement;
};

// Enhanced Toast Error
window.toastError = function(message, options = {}) {
    const config = {
        ...defaultToastConfig,
        text: message,
        duration: options.duration || 4000,
        className: "toast-error",
        style: {
            background: "linear-gradient(135deg, #f44336 0%, #d32f2f 100%)",
        },
        ...options
    };
    
    const toastElement = Toastify(config);
    toastElement.showToast();
    
    if (options.progress !== false) {
        addProgressBar(toastElement, config.duration);
    }
    
    return toastElement;
};

// Enhanced Toast Warning
window.toastWarning = function(message, options = {}) {
    const config = {
        ...defaultToastConfig,
        text: message,
        duration: options.duration || 3500,
        className: "toast-warning",
        style: {
            background: "linear-gradient(135deg, #FF9800 0%, #F57C00 100%)",
        },
        ...options
    };
    
    const toastElement = Toastify(config);
    toastElement.showToast();
    
    if (options.progress !== false) {
        addProgressBar(toastElement, config.duration);
    }
    
    return toastElement;
};

// Enhanced Toast Info
window.toastInfo = function(message, options = {}) {
    const config = {
        ...defaultToastConfig,
        text: message,
        duration: options.duration || 3000,
        className: "toast-info",
        style: {
            background: "linear-gradient(135deg, #2196F3 0%, #1976D2 100%)",
        },
        ...options
    };
    
    const toastElement = Toastify(config);
    toastElement.showToast();
    
    if (options.progress !== false) {
        addProgressBar(toastElement, config.duration);
    }
    
    return toastElement;
};

// Enhanced Toast Primary (Theme Color)
window.toastPrimary = function(message, options = {}) {
    const config = {
        ...defaultToastConfig,
        text: message,
        duration: options.duration || 3000,
        className: "toast-primary",
        style: {
            background: "linear-gradient(135deg, #667eea 0%, #764ba2 100%)",
        },
        ...options
    };
    
    const toastElement = Toastify(config);
    toastElement.showToast();
    
    if (options.progress !== false) {
        addProgressBar(toastElement, config.duration);
    }
    
    return toastElement;
};

// Loading Toast (Persistent until manually closed)
window.toastLoading = function(message, options = {}) {
    const config = {
        ...defaultToastConfig,
        text: message,
        duration: -1, // Persistent
        className: "toast-loading",
        close: options.close !== false,
        style: {
            background: "linear-gradient(135deg, #667eea 0%, #764ba2 100%)",
        },
        ...options
    };
    
    const toastElement = Toastify(config);
    toastElement.showToast();
    
    return toastElement;
};

// Function untuk menambahkan progress bar
function addProgressBar(toastElement, duration) {
    if (duration <= 0) return;
    
    // Wait for toast to be rendered
    setTimeout(() => {
        const toastDiv = toastElement.toastElement;
        if (toastDiv) {
            const progressBar = document.createElement('div');
            progressBar.className = 'toast-progress';
            progressBar.style.animationDuration = duration + 'ms';
            toastDiv.appendChild(progressBar);
        }
    }, 50);
}

// Utility functions
window.ToastUtils = {
    // Clear all toasts
    clearAll: function() {
        const toasts = document.querySelectorAll('.toastify');
        toasts.forEach(toast => {
            toast.remove();
        });
    },
    
    // Show multiple toasts in sequence
    sequence: function(toasts, delay = 500) {
        toasts.forEach((toast, index) => {
            setTimeout(() => {
                const { type, message, options } = toast;
                switch(type) {
                    case 'success':
                        toastSuccess(message, options);
                        break;
                    case 'error':
                        toastError(message, options);
                        break;
                    case 'warning':
                        toastWarning(message, options);
                        break;
                    case 'info':
                        toastInfo(message, options);
                        break;
                    case 'primary':
                        toastPrimary(message, options);
                        break;
                }
            }, index * delay);
        });
    },
    
    // Show toast with custom position
    showAt: function(type, message, position = 'top-right', options = {}) {
        const [gravity, pos] = position.split('-');
        const config = {
            gravity: gravity,
            position: pos,
            ...options
        };
        
        switch(type) {
            case 'success':
                return toastSuccess(message, config);
            case 'error':
                return toastError(message, config);
            case 'warning':
                return toastWarning(message, config);
            case 'info':
                return toastInfo(message, config);
            case 'primary':
                return toastPrimary(message, config);
        }
    },
    
    // Show toast with action button
    withAction: function(type, message, actionText, actionCallback, options = {}) {
        const actionHtml = `
            <div class="toast-content">${message}</div>
            <button class="toast-action" onclick="(${actionCallback.toString()})()">${actionText}</button>
        `;
        
        const config = {
            text: actionHtml,
            escapeMarkup: false,
            ...options
        };
        
        switch(type) {
            case 'success':
                return toastSuccess('', config);
            case 'error':
                return toastError('', config);
            case 'warning':
                return toastWarning('', config);
            case 'info':
                return toastInfo('', config);
            case 'primary':
                return toastPrimary('', config);
        }
    },
    
    // Show progress toast
    progress: function(message, progress = 0, options = {}) {
        const progressHtml = `
            <div class="toast-content">${message}</div>
            <div class="toast-progress-container">
                <div class="toast-progress-bar" style="width: ${progress}%"></div>
            </div>
        `;
        
        const config = {
            text: progressHtml,
            escapeMarkup: false,
            duration: -1,
            className: "toast-primary",
            ...options
        };
        
        return Toastify(config).showToast();
    }
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
    // Override SweetAlert2 toast dengan Toastify
    const originalSwalFire = Swal.fire;
    Swal.fire = function(options) {
        if (options && options.toast) {
            // Convert SweetAlert2 toast to Toastify
            const message = options.title || options.text || '';
            const icon = options.icon;
            
            switch(icon) {
                case 'success':
                    return toastSuccess(message, {
                        duration: options.timer || 3000
                    });
                case 'error':
                    return toastError(message, {
                        duration: options.timer || 4000
                    });
                case 'warning':
                    return toastWarning(message, {
                        duration: options.timer || 3500
                    });
                case 'info':
                    return toastInfo(message, {
                        duration: options.timer || 3000
                    });
                default:
                    return toastPrimary(message, {
                        duration: options.timer || 3000
                    });
            }
        }
        
        // For non-toast alerts, use original SweetAlert2
        return originalSwalFire.apply(this, arguments);
    };
}

// Auto-initialize when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    // Add custom CSS for action buttons
    const style = document.createElement('style');
    style.textContent = `
        .toast-action {
            background: rgba(255, 255, 255, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: white;
            padding: 4px 12px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            margin-left: 10px;
            transition: all 0.2s ease;
        }
        
        .toast-action:hover {
            background: rgba(255, 255, 255, 0.3);
            border-color: rgba(255, 255, 255, 0.5);
        }
        
        .toast-progress-container {
            margin-top: 8px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 10px;
            height: 6px;
            overflow: hidden;
        }
        
        .toast-progress-bar {
            height: 100%;
            background: white;
            border-radius: 10px;
            transition: width 0.3s ease;
        }
    `;
    document.head.appendChild(style);
    
    console.log('✅ Toastify Enhanced System initialized');
    console.log('✅ All toast functions now use Toastify with modern design');
    
    // Show initialization toast
    setTimeout(() => {
        toastInfo('Toastify Enhanced System Ready!', {
            duration: 2000
        });
    }, 1000);
});

// Export untuk module systems
if (typeof module !== 'undefined' && module.exports) {
    module.exports = {
        toastSuccess,
        toastError,
        toastWarning,
        toastInfo,
        toastPrimary,
        toastLoading,
        ToastUtils
    };
}
