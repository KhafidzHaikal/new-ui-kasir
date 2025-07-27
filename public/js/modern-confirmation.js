/**
 * Modern Confirmation System with Toastify
 * 
 * Enhanced confirmation dialogs and toast notifications
 * for better user experience
 */

class ModernConfirmation {
    constructor() {
        this.toastifyLoaded = typeof Toastify !== 'undefined';
        this.init();
    }

    init() {
        // Initialize Toastify if not loaded
        if (!this.toastifyLoaded) {
            this.loadToastify();
        }
        
        console.log('🎨 Modern Confirmation System initialized');
    }

    loadToastify() {
        // Load Toastify CSS
        const toastifyCSS = document.createElement('link');
        toastifyCSS.rel = 'stylesheet';
        toastifyCSS.href = 'https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css';
        document.head.appendChild(toastifyCSS);

        // Load Toastify JS
        const toastifyJS = document.createElement('script');
        toastifyJS.src = 'https://cdn.jsdelivr.net/npm/toastify-js';
        toastifyJS.onload = () => {
            this.toastifyLoaded = true;
            console.log('✅ Toastify loaded successfully');
        };
        document.head.appendChild(toastifyJS);
    }

    /**
     * Show confirmation dialog
     */
    confirm(options = {}) {
        const defaults = {
            title: 'Konfirmasi',
            message: 'Apakah Anda yakin?',
            confirmText: 'Ya, Lanjutkan',
            cancelText: 'Batal',
            confirmClass: 'btn-danger',
            icon: 'warning',
            onConfirm: () => {},
            onCancel: () => {}
        };

        const config = { ...defaults, ...options };
        
        return new Promise((resolve, reject) => {
            // Create modal HTML
            const modalId = 'modernConfirmModal_' + Date.now();
            const modalHTML = this.createModalHTML(modalId, config);
            
            // Add to DOM
            document.body.insertAdjacentHTML('beforeend', modalHTML);
            
            // Show modal
            const modal = $(`#${modalId}`);
            modal.modal('show');
            
            // Handle confirm button
            modal.find(`#${modalId}Confirm`).on('click', () => {
                modal.modal('hide');
                config.onConfirm();
                resolve(true);
            });
            
            // Handle cancel/close
            modal.on('hidden.bs.modal', () => {
                modal.remove();
                config.onCancel();
                resolve(false);
            });
        });
    }

    /**
     * Delete confirmation with enhanced styling
     */
    confirmDelete(options = {}) {
        const deleteOptions = {
            title: 'Konfirmasi Hapus',
            message: 'Apakah Anda yakin ingin menghapus data ini? Data yang sudah dihapus tidak dapat dikembalikan.',
            confirmText: 'Ya, Hapus!',
            cancelText: 'Batal',
            confirmClass: 'btn-danger',
            icon: 'danger',
            ...options
        };

        return this.confirm(deleteOptions);
    }

    /**
     * Create modal HTML
     */
    createModalHTML(modalId, config) {
        const iconHTML = this.getIconHTML(config.icon);
        
        return `
        <div class="modal fade" id="${modalId}" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content modern-confirmation-modal">
                    <div class="modal-header border-0 pb-0">
                        <button type="button" class="close modern-close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    
                    <div class="modal-body text-center py-4">
                        <div class="confirmation-icon mb-3">
                            ${iconHTML}
                        </div>
                        
                        <h4 class="confirmation-title mb-3">${config.title}</h4>
                        <p class="confirmation-message text-muted mb-4">${config.message}</p>
                    </div>
                    
                    <div class="modal-footer border-0 justify-content-center pb-4">
                        <button type="button" class="btn btn-secondary btn-modern mr-2" data-dismiss="modal">
                            <i class="fas fa-times mr-1"></i> ${config.cancelText}
                        </button>
                        <button type="button" class="btn ${config.confirmClass} btn-modern" id="${modalId}Confirm">
                            <i class="fas fa-check mr-1"></i> ${config.confirmText}
                        </button>
                    </div>
                </div>
            </div>
        </div>`;
    }

    /**
     * Get icon HTML based on type
     */
    getIconHTML(iconType) {
        const icons = {
            warning: '<div class="icon-circle warning"><i class="fas fa-exclamation-triangle"></i></div>',
            danger: '<div class="icon-circle danger"><i class="fas fa-trash-alt"></i></div>',
            info: '<div class="icon-circle info"><i class="fas fa-info-circle"></i></div>',
            question: '<div class="icon-circle question"><i class="fas fa-question-circle"></i></div>'
        };
        
        return icons[iconType] || icons.question;
    }

    /**
     * Toast notifications using Toastify
     */
    toast(message, type = 'info', options = {}) {
        if (!this.toastifyLoaded) {
            console.warn('Toastify not loaded, falling back to alert');
            alert(message);
            return;
        }

        const defaults = {
            text: message,
            duration: 3000,
            close: true,
            gravity: "top",
            position: "right",
            stopOnFocus: true,
            style: this.getToastStyle(type),
            onClick: function() {}
        };

        const config = { ...defaults, ...options };
        
        Toastify(config).showToast();
    }

    /**
     * Success toast
     */
    toastSuccess(message, options = {}) {
        this.toast(message, 'success', options);
    }

    /**
     * Error toast
     */
    toastError(message, options = {}) {
        this.toast(message, 'error', options);
    }

    /**
     * Warning toast
     */
    toastWarning(message, options = {}) {
        this.toast(message, 'warning', options);
    }

    /**
     * Info toast
     */
    toastInfo(message, options = {}) {
        this.toast(message, 'info', options);
    }

    /**
     * Get toast styling based on type
     */
    getToastStyle(type) {
        const styles = {
            success: {
                background: "linear-gradient(135deg, #27ae60, #2ecc71)",
                borderRadius: "10px",
                color: "#fff",
                fontWeight: "500"
            },
            error: {
                background: "linear-gradient(135deg, #e74c3c, #c0392b)",
                borderRadius: "10px",
                color: "#fff",
                fontWeight: "500"
            },
            warning: {
                background: "linear-gradient(135deg, #f39c12, #e67e22)",
                borderRadius: "10px",
                color: "#fff",
                fontWeight: "500"
            },
            info: {
                background: "linear-gradient(135deg, #3498db, #2980b9)",
                borderRadius: "10px",
                color: "#fff",
                fontWeight: "500"
            }
        };

        return styles[type] || styles.info;
    }

    /**
     * Loading toast
     */
    toastLoading(message = 'Memproses...', options = {}) {
        const loadingOptions = {
            text: `<i class="fas fa-spinner fa-spin mr-2"></i> ${message}`,
            duration: -1, // Don't auto-hide
            close: false,
            gravity: "top",
            position: "center",
            escapeMarkup: false,
            style: {
                background: "linear-gradient(135deg, #34495e, #2c3e50)",
                borderRadius: "10px",
                color: "#fff",
                fontWeight: "500"
            },
            ...options
        };

        return Toastify(loadingOptions).showToast();
    }

    /**
     * Hide all toasts
     */
    hideAllToasts() {
        // Remove all toastify elements
        document.querySelectorAll('.toastify').forEach(toast => {
            toast.remove();
        });
    }
}

// Initialize global instance
window.ModernConfirm = new ModernConfirmation();

// Add CSS for modal styling
const modalCSS = `
<style>
.modern-confirmation-modal {
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
    border: none;
}

.modern-confirmation-modal .modal-header {
    padding: 15px 20px 0;
}

.modern-close {
    font-size: 24px;
    font-weight: 300;
    color: #999;
    opacity: 0.7;
    transition: all 0.3s ease;
}

.modern-close:hover {
    opacity: 1;
    color: #666;
}

.confirmation-icon {
    display: flex;
    justify-content: center;
    align-items: center;
}

.icon-circle {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 32px;
    color: white;
    animation: pulse 2s infinite;
}

.icon-circle.warning {
    background: linear-gradient(135deg, #f39c12, #e67e22);
}

.icon-circle.danger {
    background: linear-gradient(135deg, #e74c3c, #c0392b);
}

.icon-circle.info {
    background: linear-gradient(135deg, #3498db, #2980b9);
}

.icon-circle.question {
    background: linear-gradient(135deg, #9b59b6, #8e44ad);
}

@keyframes pulse {
    0% {
        transform: scale(1);
        box-shadow: 0 0 0 0 rgba(52, 152, 219, 0.4);
    }
    70% {
        transform: scale(1.05);
        box-shadow: 0 0 0 10px rgba(52, 152, 219, 0);
    }
    100% {
        transform: scale(1);
        box-shadow: 0 0 0 0 rgba(52, 152, 219, 0);
    }
}

.confirmation-title {
    font-weight: 600;
    color: #2c3e50;
    font-size: 1.4rem;
}

.confirmation-message {
    font-size: 1rem;
    line-height: 1.5;
}

.btn-modern {
    border-radius: 25px;
    padding: 10px 25px;
    font-weight: 500;
    transition: all 0.3s ease;
    border: none;
    min-width: 120px;
}

.btn-modern:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
}

.btn-danger.btn-modern {
    background: linear-gradient(135deg, #e74c3c, #c0392b);
}

.btn-danger.btn-modern:hover {
    background: linear-gradient(135deg, #c0392b, #a93226);
}

.btn-secondary.btn-modern {
    background: linear-gradient(135deg, #95a5a6, #7f8c8d);
}

.btn-secondary.btn-modern:hover {
    background: linear-gradient(135deg, #7f8c8d, #6c7b7d);
}
</style>
`;

// Add CSS to head
document.head.insertAdjacentHTML('beforeend', modalCSS);

// Export for module usage
if (typeof module !== 'undefined' && module.exports) {
    module.exports = ModernConfirmation;
}
