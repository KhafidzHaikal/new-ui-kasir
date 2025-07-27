/**
 * Modern Alert System - Replace All Browser Default Alerts
 * Enhanced design with reference colors
 */

class ModernAlert {
    constructor() {
        this.alertContainer = null;
        this.init();
        this.overrideBrowserAlerts();
    }
    
    init() {
        // Create alert container if not exists
        if (!document.getElementById('alert-container')) {
            this.alertContainer = document.createElement('div');
            this.alertContainer.id = 'alert-container';
            this.alertContainer.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                z-index: 9999;
                pointer-events: none;
            `;
            document.body.appendChild(this.alertContainer);
        }
    }
    
    /**
     * Override browser default alerts
     */
    overrideBrowserAlerts() {
        // Override window.alert
        window.alert = (message) => {
            return this.info('Pemberitahuan', message);
        };
        
        // Override window.confirm
        window.confirm = (message) => {
            return this.confirm('Konfirmasi', message);
        };
        
        // Override console methods for better UX
        const originalError = console.error;
        console.error = (...args) => {
            originalError.apply(console, args);
            if (args.length > 0 && typeof args[0] === 'string') {
                this.error('Error', args[0]);
            }
        };
    }
    
    /**
     * Show success alert with enhanced design
     */
    success(title, message, options = {}) {
        return Swal.fire({
            icon: 'success',
            title: title,
            html: this.formatMessage(message),
            confirmButtonText: options.confirmButtonText || 'OK',
            customClass: {
                confirmButton: 'swal2-confirm success-confirm',
                popup: 'swal2-success-popup',
                title: 'swal2-title-success'
            },
            showClass: {
                popup: 'animate__animated animate__fadeInDown animate__faster'
            },
            hideClass: {
                popup: 'animate__animated animate__fadeOutUp animate__faster'
            },
            ...options
        });
    }
    
    /**
     * Show warning alert with enhanced design
     */
    warning(title, message, options = {}) {
        return Swal.fire({
            icon: 'warning',
            title: title,
            html: this.formatMessage(message),
            confirmButtonText: options.confirmButtonText || 'Mengerti',
            customClass: {
                confirmButton: 'swal2-confirm warning-confirm',
                popup: 'swal2-warning-popup',
                title: 'swal2-title-warning'
            },
            showClass: {
                popup: 'animate__animated animate__fadeInDown animate__faster'
            },
            hideClass: {
                popup: 'animate__animated animate__fadeOutUp animate__faster'
            },
            ...options
        });
    }
    
    /**
     * Show error alert with enhanced design
     */
    error(title, message, options = {}) {
        return Swal.fire({
            icon: 'error',
            title: title,
            html: this.formatMessage(message),
            confirmButtonText: options.confirmButtonText || 'OK',
            customClass: {
                confirmButton: 'swal2-confirm',
                popup: 'swal2-error-popup',
                title: 'swal2-title-error'
            },
            showClass: {
                popup: 'animate__animated animate__fadeInDown animate__faster'
            },
            hideClass: {
                popup: 'animate__animated animate__fadeOutUp animate__faster'
            },
            ...options
        });
    }
    
    /**
     * Show info alert with enhanced design
     */
    info(title, message, options = {}) {
        return Swal.fire({
            icon: 'info',
            title: title,
            html: this.formatMessage(message),
            confirmButtonText: options.confirmButtonText || 'OK',
            customClass: {
                confirmButton: 'swal2-confirm',
                popup: 'swal2-info-popup',
                title: 'swal2-title-info'
            },
            showClass: {
                popup: 'animate__animated animate__fadeInDown animate__faster'
            },
            hideClass: {
                popup: 'animate__animated animate__fadeOutUp animate__faster'
            },
            ...options
        });
    }
    
    /**
     * Enhanced delete confirmation with modern design
     */
    confirmDelete(title = 'Hapus Data?', message = 'Data yang dihapus tidak dapat dikembalikan!', options = {}) {
        return Swal.fire({
            icon: 'question',
            title: title,
            html: `
                <div style="text-align: center; margin: 20px 0;">
                    <div style="font-size: 60px; margin-bottom: 15px;">🗑️</div>
                    <p style="font-size: 16px; color: #666; margin: 0; line-height: 1.5;">
                        ${message}
                    </p>
                    <div style="background: #fff3cd; border: 1px solid #ffeaa7; border-radius: 8px; padding: 12px; margin-top: 15px;">
                        <small style="color: #856404; font-weight: 500;">
                            ⚠️ Tindakan ini tidak dapat dibatalkan
                        </small>
                    </div>
                </div>
            `,
            showCancelButton: true,
            confirmButtonText: options.confirmButtonText || '🗑️ Ya, Hapus!',
            cancelButtonText: options.cancelButtonText || '❌ Batal',
            reverseButtons: true,
            customClass: {
                confirmButton: 'swal2-confirm delete-confirm',
                cancelButton: 'swal2-cancel',
                popup: 'swal2-delete-popup'
            },
            showClass: {
                popup: 'animate__animated animate__fadeInDown animate__faster'
            },
            hideClass: {
                popup: 'animate__animated animate__fadeOutUp animate__faster'
            },
            buttonsStyling: false,
            ...options
        });
    }
    
    /**
     * Enhanced general confirmation
     */
    confirm(title, message, options = {}) {
        return Swal.fire({
            icon: 'question',
            title: title,
            html: this.formatMessage(message),
            showCancelButton: true,
            confirmButtonText: options.confirmButtonText || '✅ Ya',
            cancelButtonText: options.cancelButtonText || '❌ Tidak',
            reverseButtons: true,
            customClass: {
                confirmButton: 'swal2-confirm',
                cancelButton: 'swal2-cancel',
                popup: 'swal2-question-popup'
            },
            showClass: {
                popup: 'animate__animated animate__fadeInDown animate__faster'
            },
            hideClass: {
                popup: 'animate__animated animate__fadeOutUp animate__faster'
            },
            buttonsStyling: false,
            ...options
        });
    }
    
    /**
     * Enhanced expired products warning
     */
    expiredProductsWarning(count, products = []) {
        let productList = '';
        if (products.length > 0) {
            productList = `
                <div style="margin-top: 20px; text-align: left; background: #fff3cd; border-radius: 10px; padding: 15px;">
                    <h4 style="margin: 0 0 10px 0; color: #856404; font-size: 16px;">
                        📦 Produk yang akan kadaluarsa:
                    </h4>
                    <ul style="margin: 0; padding-left: 20px; color: #856404;">
            `;
            products.slice(0, 5).forEach(product => {
                productList += `
                    <li style="margin: 8px 0; font-size: 14px;">
                        <strong>${product.nama_produk}</strong> 
                        <span style="color: #d63384;">📅 ${product.tanggal_expire}</span>
                    </li>
                `;
            });
            if (products.length > 5) {
                productList += `
                    <li style="margin: 8px 0; font-style: italic; color: #6c757d;">
                        ... dan ${products.length - 5} produk lainnya
                    </li>
                `;
            }
            productList += '</ul></div>';
        }
        
        return this.warning(
            '⚠️ Peringatan Produk Kadaluarsa',
            `
                <div style="text-align: center;">
                    <div style="font-size: 80px; margin-bottom: 15px;">⏰</div>
                    <p style="font-size: 18px; margin-bottom: 10px; color: #333;">
                        Terdapat <strong style="color: #FF9800;">${count} produk</strong> yang akan kadaluarsa dalam 7 hari ke depan.
                    </p>
                    <p style="color: #666; font-size: 14px; margin-bottom: 15px;">
                        Harap segera lakukan pengecekan dan pengelolaan stok produk.
                    </p>
                    ${productList}
                </div>
            `,
            {
                confirmButtonText: '📋 Periksa Produk',
                showCancelButton: true,
                cancelButtonText: '⏰ Nanti Saja',
                width: '600px'
            }
        );
    }
    
    /**
     * Format message with better typography
     */
    formatMessage(message) {
        if (typeof message !== 'string') return message;
        
        // Convert line breaks to HTML
        message = message.replace(/\n/g, '<br>');
        
        // Add emphasis to important words
        message = message.replace(/\*\*(.*?)\*\*/g, '<strong style="color: #667eea;">$1</strong>');
        message = message.replace(/\*(.*?)\*/g, '<em>$1</em>');
        
        return `<div style="line-height: 1.6; color: #666;">${message}</div>`;
    }
    
    /**
     * Show loading alert with modern design
     */
    loading(title = 'Memproses...', message = 'Mohon tunggu sebentar') {
        return Swal.fire({
            title: title,
            html: `
                <div style="text-align: center; padding: 20px;">
                    <div style="font-size: 60px; margin-bottom: 15px;">⏳</div>
                    <p style="color: #666; font-size: 15px; margin: 0;">${message}</p>
                    <div style="margin-top: 20px;">
                        <div style="display: inline-block; width: 40px; height: 40px; border: 4px solid #f3f3f3; border-top: 4px solid #667eea; border-radius: 50%; animation: spin 1s linear infinite;"></div>
                    </div>
                </div>
                <style>
                    @keyframes spin {
                        0% { transform: rotate(0deg); }
                        100% { transform: rotate(360deg); }
                    }
                </style>
            `,
            allowOutsideClick: false,
            allowEscapeKey: false,
            showConfirmButton: false,
            customClass: {
                popup: 'swal2-loading-popup'
            },
            showClass: {
                popup: 'animate__animated animate__fadeIn animate__faster'
            }
        });
    }
    
    /**
     * Close loading alert
     */
    closeLoading() {
        Swal.close();
    }
    
    /**
     * Enhanced toast notification system
     */
    toast(type, title, message, duration = 5000) {
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: duration,
            timerProgressBar: true,
            customClass: {
                popup: `swal2-toast-${type}`,
                title: 'swal2-toast-title',
                htmlContainer: 'swal2-toast-message'
            },
            showClass: {
                popup: 'animate__animated animate__fadeInRight animate__faster'
            },
            hideClass: {
                popup: 'animate__animated animate__fadeOutRight animate__faster'
            },
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer);
                toast.addEventListener('mouseleave', Swal.resumeTimer);
            }
        });
        
        const iconMap = {
            success: '✅',
            warning: '⚠️',
            error: '❌',
            info: 'ℹ️'
        };
        
        return Toast.fire({
            icon: type,
            title: `${iconMap[type]} ${title}`,
            html: message
        });
    }
}
    
    /**
     * Show custom toast notification
     */
    toast(type, title, message, duration = 5000) {
        const alertId = 'alert-' + Date.now();
        const alertElement = document.createElement('div');
        alertElement.id = alertId;
        alertElement.className = `custom-alert alert-${type}`;
        
        const iconMap = {
            success: '✅',
            warning: '⚠️',
            error: '❌',
            info: 'ℹ️'
        };
        
        alertElement.innerHTML = `
            <div class="alert-header">
                <div class="alert-icon">${iconMap[type] || 'ℹ️'}</div>
                <h4 class="alert-title">${title}</h4>
                <button class="alert-close" onclick="modernAlert.closeToast('${alertId}')">&times;</button>
            </div>
            <div class="alert-body">
                <p class="alert-message">${message}</p>
            </div>
            <div class="alert-progress">
                <div class="alert-progress-bar"></div>
            </div>
        `;
        
        this.alertContainer.appendChild(alertElement);
        
        // Show animation
        setTimeout(() => {
            alertElement.classList.add('show');
        }, 100);
        
        // Progress bar animation
        const progressBar = alertElement.querySelector('.alert-progress-bar');
        setTimeout(() => {
            progressBar.style.transitionDuration = `${duration}ms`;
            progressBar.style.transform = 'translateX(0)';
        }, 200);
        
        // Auto close
        setTimeout(() => {
            this.closeToast(alertId);
        }, duration);
        
        return alertId;
    }
    
    /**
     * Close toast notification
     */
    closeToast(alertId) {
        const alertElement = document.getElementById(alertId);
        if (alertElement) {
            alertElement.classList.add('hide');
            setTimeout(() => {
                if (alertElement.parentNode) {
                    alertElement.parentNode.removeChild(alertElement);
                }
            }, 300);
        }
    }
    
    /**
     * Show expired products warning
     */
    expiredProductsWarning(count, products = []) {
        let productList = '';
        if (products.length > 0) {
            productList = '<div style="margin-top: 15px; text-align: left;"><strong>Produk yang akan kadaluarsa:</strong><ul style="margin: 10px 0; padding-left: 20px;">';
            products.slice(0, 5).forEach(product => {
                productList += `<li style="margin: 5px 0;">${product.nama_produk} - ${product.tanggal_expire}</li>`;
            });
            if (products.length > 5) {
                productList += `<li style="margin: 5px 0; font-style: italic;">... dan ${products.length - 5} produk lainnya</li>`;
            }
            productList += '</ul></div>';
        }
        
        return this.warning(
            '⚠️ Peringatan Produk Kadaluarsa',
            `<div style="text-align: center;">
                <p style="margin-bottom: 10px;">Terdapat <strong>${count} produk</strong> yang akan kadaluarsa dalam 7 hari ke depan.</p>
                <p style="color: #666; font-size: 13px;">Harap segera lakukan pengecekan dan pengelolaan stok produk.</p>
                ${productList}
            </div>`,
            {
                confirmButtonText: 'Periksa Produk',
                showCancelButton: true,
                cancelButtonText: 'Nanti Saja'
            }
        );
    }
    
    /**
     * Show loading alert
     */
    loading(title = 'Memproses...', message = 'Mohon tunggu sebentar') {
        return Swal.fire({
            title: title,
            text: message,
            allowOutsideClick: false,
            allowEscapeKey: false,
            showConfirmButton: false,
            didOpen: () => {
                Swal.showLoading();
            },
            customClass: {
                popup: 'swal2-info'
            }
        });
    }
    
    /**
     * Close loading alert
     */
    closeLoading() {
        Swal.close();
    }
}

// Initialize global instance
const modernAlert = new ModernAlert();

// Backward compatibility functions
function showSuccessAlert(title, message, options = {}) {
    return modernAlert.success(title, message, options);
}

function showWarningAlert(title, message, options = {}) {
    return modernAlert.warning(title, message, options);
}

function showErrorAlert(title, message, options = {}) {
    return modernAlert.error(title, message, options);
}

function showInfoAlert(title, message, options = {}) {
    return modernAlert.info(title, message, options);
}

function confirmDelete(title, message, options = {}) {
    return modernAlert.confirmDelete(title, message, options);
}

function showConfirm(title, message, options = {}) {
    return modernAlert.confirm(title, message, options);
}

function showToast(type, title, message, duration = 5000) {
    return modernAlert.toast(type, title, message, duration);
}

function showExpiredWarning(count, products = []) {
    return modernAlert.expiredProductsWarning(count, products);
}

function showLoading(title, message) {
    return modernAlert.loading(title, message);
}

function closeLoading() {
    return modernAlert.closeLoading();
}

// jQuery integration (if jQuery is available)
if (typeof $ !== 'undefined') {
    $.extend({
        alert: {
            success: showSuccessAlert,
            warning: showWarningAlert,
            error: showErrorAlert,
            info: showInfoAlert,
            confirm: showConfirm,
            confirmDelete: confirmDelete,
            toast: showToast,
            expiredWarning: showExpiredWarning,
            loading: showLoading,
            closeLoading: closeLoading
        }
    });
}

// Export for module systems
if (typeof module !== 'undefined' && module.exports) {
    module.exports = ModernAlert;
}

// Auto-initialize on DOM ready
document.addEventListener('DOMContentLoaded', function() {
    // Override default confirm dialogs
    window.confirmDelete = confirmDelete;
    window.showAlert = modernAlert;
    
    // Handle expired products alert from Laravel
    if (typeof expiredProductsData !== 'undefined' && expiredProductsData.length > 0) {
        setTimeout(() => {
            showExpiredWarning(expiredProductsData.length, expiredProductsData);
        }, 1000);
    }
});
