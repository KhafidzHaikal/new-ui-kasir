<!-- Modern Confirmation System Component -->
<!-- Include this component in layouts that need confirmation dialogs -->

<!-- Toastify CSS -->
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">

<!-- Toastify JS -->
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>

<!-- Modern Confirmation System -->
<script src="{{ asset('js/modern-confirmation.js') }}"></script>

<!-- Global Confirmation Functions -->
<script>
// Global helper functions for easy access
window.confirmDelete = function(url, options = {}) {
    const defaultOptions = {
        title: 'Konfirmasi Hapus',
        message: 'Apakah Anda yakin ingin menghapus data ini? Data yang sudah dihapus tidak dapat dikembalikan.',
        confirmText: 'Ya, Hapus!',
        cancelText: 'Batal',
        onConfirm: () => {
            // Default delete action
            const loadingToast = ModernConfirm.toastLoading('Menghapus data...');
            
            $.post(url, {
                '_token': $('[name=csrf-token]').attr('content'),
                '_method': 'delete'
            })
            .done((response) => {
                ModernConfirm.hideAllToasts();
                ModernConfirm.toastSuccess('Data berhasil dihapus!');
                
                // Reload table if exists
                if (typeof table !== 'undefined' && table.ajax) {
                    table.ajax.reload();
                } else {
                    location.reload();
                }
            })
            .fail((errors) => {
                ModernConfirm.hideAllToasts();
                let errorMessage = 'Tidak dapat menghapus data';
                
                if (errors.responseJSON && errors.responseJSON.message) {
                    errorMessage = errors.responseJSON.message;
                } else if (errors.status === 404) {
                    errorMessage = 'Data tidak ditemukan';
                } else if (errors.status === 403) {
                    errorMessage = 'Tidak memiliki akses untuk menghapus data';
                } else if (errors.status === 500) {
                    errorMessage = 'Terjadi kesalahan server';
                }

                ModernConfirm.toastError(errorMessage);
            });
        },
        ...options
    };
    
    return ModernConfirm.confirmDelete(defaultOptions);
};

window.confirmAction = function(options = {}) {
    return ModernConfirm.confirm(options);
};

window.showToast = {
    success: (message, options = {}) => ModernConfirm.toastSuccess(message, options),
    error: (message, options = {}) => ModernConfirm.toastError(message, options),
    warning: (message, options = {}) => ModernConfirm.toastWarning(message, options),
    info: (message, options = {}) => ModernConfirm.toastInfo(message, options),
    loading: (message, options = {}) => ModernConfirm.toastLoading(message, options)
};

// Backward compatibility functions
window.alertSuccess = function(title, message) {
    ModernConfirm.toastSuccess(message || title);
};

window.alertError = function(title, message) {
    ModernConfirm.toastError(message || title);
};

window.alertWarning = function(title, message) {
    ModernConfirm.toastWarning(message || title);
};

window.alertInfo = function(title, message) {
    ModernConfirm.toastInfo(message || title);
};

console.log('🎨 Global Confirmation System loaded');
</script>

<style>
/* Additional styling for better integration */
.toastify {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    font-size: 14px;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
    border-radius: 10px;
    padding: 12px 16px;
}

.toastify .toast-close {
    opacity: 0.7;
    transition: opacity 0.3s ease;
}

.toastify .toast-close:hover {
    opacity: 1;
}

/* Custom animations for toasts */
@keyframes slideInRight {
    from {
        transform: translateX(100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

@keyframes slideOutRight {
    from {
        transform: translateX(0);
        opacity: 1;
    }
    to {
        transform: translateX(100%);
        opacity: 0;
    }
}

.toastify {
    animation: slideInRight 0.3s ease-out;
}

.toastify.toastify-right {
    right: 15px;
}

.toastify.toastify-top {
    top: 15px;
}

/* Modern confirmation modal enhancements */
.modal-backdrop.show {
    opacity: 0.6;
}

.modern-confirmation-modal {
    animation: modalFadeIn 0.3s ease-out;
}

@keyframes modalFadeIn {
    from {
        opacity: 0;
        transform: scale(0.9) translateY(-50px);
    }
    to {
        opacity: 1;
        transform: scale(1) translateY(0);
    }
}
</style>
