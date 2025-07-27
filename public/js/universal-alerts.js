/**
 * Universal Alert System - Consistent SweetAlert2 Implementation
 * Menggunakan design yang konsisten dengan navbar/sidebar (#667eea → #764ba2)
 */

// Konfigurasi default untuk semua alert
const defaultConfig = {
    customClass: {
        popup: 'swal2-popup-modern',
        title: 'swal2-title-modern',
        htmlContainer: 'swal2-html-modern',
        actions: 'swal2-actions-modern'
    },
    showClass: {
        popup: 'animate__animated animate__fadeInDown animate__faster'
    },
    hideClass: {
        popup: 'animate__animated animate__fadeOutUp animate__faster'
    },
    allowOutsideClick: false,
    allowEscapeKey: true,
    allowEnterKey: true,
    stopKeydownPropagation: false,
    keydownListenerCapture: false,
    showCloseButton: false,
    showCancelButton: false,
    focusConfirm: true,
    reverseButtons: false,
    focusDeny: false,
    focusCancel: false,
    returnFocus: true,
    scrollbarPadding: true,
    heightAuto: true,
    backdrop: true,
    toast: false,
    position: 'center',
    grow: false,
    width: 500,
    padding: '2em',
    color: '#545454',
    background: '#fff',
    buttonsStyling: false
};

// Alert Success
window.alertSuccess = function(title, text = '', options = {}) {
    return Swal.fire({
        ...defaultConfig,
        icon: 'success',
        title: title,
        text: text,
        confirmButtonText: 'OK',
        customClass: {
            ...defaultConfig.customClass,
            confirmButton: 'swal2-confirm-success'
        },
        iconColor: '#4CAF50',
        ...options
    });
};

// Alert Error
window.alertError = function(title, text = '', options = {}) {
    return Swal.fire({
        ...defaultConfig,
        icon: 'error',
        title: title,
        text: text,
        confirmButtonText: 'OK',
        customClass: {
            ...defaultConfig.customClass,
            confirmButton: 'swal2-confirm-error'
        },
        iconColor: '#f44336',
        ...options
    });
};

// Alert Warning
window.alertWarning = function(title, text = '', options = {}) {
    return Swal.fire({
        ...defaultConfig,
        icon: 'warning',
        title: title,
        text: text,
        confirmButtonText: 'OK',
        customClass: {
            ...defaultConfig.customClass,
            confirmButton: 'swal2-confirm-warning'
        },
        iconColor: '#FF9800',
        ...options
    });
};

// Alert Info
window.alertInfo = function(title, text = '', options = {}) {
    return Swal.fire({
        ...defaultConfig,
        icon: 'info',
        title: title,
        text: text,
        confirmButtonText: 'OK',
        customClass: {
            ...defaultConfig.customClass,
            confirmButton: 'swal2-confirm-info'
        },
        iconColor: '#2196F3',
        ...options
    });
};

// Alert Question
window.alertQuestion = function(title, text = '', options = {}) {
    return Swal.fire({
        ...defaultConfig,
        icon: 'question',
        title: title,
        text: text,
        confirmButtonText: 'Ya',
        showCancelButton: true,
        cancelButtonText: 'Tidak',
        customClass: {
            ...defaultConfig.customClass,
            confirmButton: 'swal2-confirm-primary',
            cancelButton: 'swal2-cancel-modern'
        },
        iconColor: '#667eea',
        ...options
    });
};

// Confirm Delete
window.confirmDelete = function(title = 'Apakah Anda yakin?', text = 'Data yang dihapus tidak dapat dikembalikan!', options = {}) {
    return Swal.fire({
        ...defaultConfig,
        icon: 'warning',
        title: title,
        text: text,
        showCancelButton: true,
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal',
        customClass: {
            ...defaultConfig.customClass,
            confirmButton: 'swal2-confirm-delete',
            cancelButton: 'swal2-cancel-modern'
        },
        iconColor: '#f44336',
        ...options
    });
};

// Loading Alert
window.showLoading = function(title = 'Memproses...', text = 'Mohon tunggu sebentar') {
    return Swal.fire({
        ...defaultConfig,
        title: title,
        text: text,
        allowOutsideClick: false,
        allowEscapeKey: false,
        showConfirmButton: false,
        customClass: {
            popup: 'swal2-popup-loading'
        },
        didOpen: () => {
            Swal.showLoading();
        }
    });
};

// Hide Loading
window.hideLoading = function() {
    Swal.close();
};

// Toast Notifications
window.toastSuccess = function(title, options = {}) {
    return Swal.fire({
        toast: true,
        position: 'top-end',
        icon: 'success',
        title: title,
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        customClass: {
            popup: 'swal2-toast-success',
            title: 'swal2-toast-title'
        },
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer);
            toast.addEventListener('mouseleave', Swal.resumeTimer);
        },
        ...options
    });
};

window.toastError = function(title, options = {}) {
    return Swal.fire({
        toast: true,
        position: 'top-end',
        icon: 'error',
        title: title,
        showConfirmButton: false,
        timer: 4000,
        timerProgressBar: true,
        customClass: {
            popup: 'swal2-toast-error',
            title: 'swal2-toast-title'
        },
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer);
            toast.addEventListener('mouseleave', Swal.resumeTimer);
        },
        ...options
    });
};

window.toastWarning = function(title, options = {}) {
    return Swal.fire({
        toast: true,
        position: 'top-end',
        icon: 'warning',
        title: title,
        showConfirmButton: false,
        timer: 3500,
        timerProgressBar: true,
        customClass: {
            popup: 'swal2-toast-warning',
            title: 'swal2-toast-title'
        },
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer);
            toast.addEventListener('mouseleave', Swal.resumeTimer);
        },
        ...options
    });
};

window.toastInfo = function(title, options = {}) {
    return Swal.fire({
        toast: true,
        position: 'top-end',
        icon: 'info',
        title: title,
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        customClass: {
            popup: 'swal2-toast-info',
            title: 'swal2-toast-title'
        },
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer);
            toast.addEventListener('mouseleave', Swal.resumeTimer);
        },
        ...options
    });
};

// Custom Alert dengan HTML
window.alertCustom = function(config) {
    return Swal.fire({
        ...defaultConfig,
        customClass: {
            ...defaultConfig.customClass,
            confirmButton: 'swal2-confirm-primary',
            cancelButton: 'swal2-cancel-modern'
        },
        ...config
    });
};

// Form Input Alert
window.alertInput = function(title, inputType = 'text', inputPlaceholder = '', options = {}) {
    return Swal.fire({
        ...defaultConfig,
        title: title,
        input: inputType,
        inputPlaceholder: inputPlaceholder,
        showCancelButton: true,
        confirmButtonText: 'OK',
        cancelButtonText: 'Batal',
        customClass: {
            ...defaultConfig.customClass,
            confirmButton: 'swal2-confirm-primary',
            cancelButton: 'swal2-cancel-modern'
        },
        inputValidator: (value) => {
            if (!value) {
                return 'Input tidak boleh kosong!';
            }
        },
        ...options
    });
};

// Multiple Input Alert
window.alertMultipleInput = function(title, inputs, options = {}) {
    const inputsHtml = inputs.map(input => {
        return `
            <div style="margin-bottom: 15px; text-align: left;">
                <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #333;">${input.label}</label>
                <input 
                    type="${input.type || 'text'}" 
                    id="${input.id}" 
                    placeholder="${input.placeholder || ''}" 
                    value="${input.value || ''}"
                    style="width: 100%; padding: 10px; border: 2px solid #e1e5e9; border-radius: 8px; font-family: 'Poppins', sans-serif; font-size: 14px; transition: border-color 0.3s ease;"
                    onfocus="this.style.borderColor='#667eea'"
                    onblur="this.style.borderColor='#e1e5e9'"
                />
            </div>
        `;
    }).join('');

    return Swal.fire({
        ...defaultConfig,
        title: title,
        html: inputsHtml,
        showCancelButton: true,
        confirmButtonText: 'OK',
        cancelButtonText: 'Batal',
        customClass: {
            ...defaultConfig.customClass,
            confirmButton: 'swal2-confirm-primary',
            cancelButton: 'swal2-cancel-modern'
        },
        preConfirm: () => {
            const result = {};
            inputs.forEach(input => {
                const element = document.getElementById(input.id);
                result[input.id] = element.value;
            });
            return result;
        },
        ...options
    });
};

// Progress Alert
window.alertProgress = function(title, text = '') {
    let timerInterval;
    return Swal.fire({
        ...defaultConfig,
        title: title,
        html: `
            ${text}<br/>
            <div style="margin-top: 20px;">
                <div style="background: #f0f0f0; border-radius: 10px; height: 20px; overflow: hidden;">
                    <div id="progress-bar" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); height: 100%; width: 0%; transition: width 0.3s ease; border-radius: 10px;"></div>
                </div>
                <div id="progress-text" style="margin-top: 10px; font-size: 14px; color: #666;">0%</div>
            </div>
        `,
        allowOutsideClick: false,
        allowEscapeKey: false,
        showConfirmButton: false,
        didOpen: () => {
            const progressBar = document.getElementById('progress-bar');
            const progressText = document.getElementById('progress-text');
            let progress = 0;
            
            timerInterval = setInterval(() => {
                progress += Math.random() * 10;
                if (progress > 100) progress = 100;
                
                progressBar.style.width = progress + '%';
                progressText.textContent = Math.round(progress) + '%';
                
                if (progress >= 100) {
                    clearInterval(timerInterval);
                }
            }, 200);
        },
        willClose: () => {
            clearInterval(timerInterval);
        }
    });
};

// Auto-close Alert
window.alertAutoClose = function(title, text, timer = 3000, icon = 'success') {
    return Swal.fire({
        ...defaultConfig,
        icon: icon,
        title: title,
        text: text,
        timer: timer,
        timerProgressBar: true,
        showConfirmButton: false,
        customClass: {
            ...defaultConfig.customClass
        },
        didOpen: () => {
            const popup = Swal.getPopup();
            popup.addEventListener('mouseenter', Swal.stopTimer);
            popup.addEventListener('mouseleave', Swal.resumeTimer);
        }
    });
};

// Utility Functions
window.AlertUtils = {
    // Replace all standard alerts
    replaceStandardAlerts: function() {
        // Override window.alert
        window.alert = function(message) {
            alertInfo('Informasi', message);
        };
        
        // Override window.confirm
        window.confirm = function(message) {
            return alertQuestion('Konfirmasi', message).then(result => result.isConfirmed);
        };
    },
    
    // Show network error
    showNetworkError: function() {
        alertError(
            'Koneksi Bermasalah',
            'Tidak dapat terhubung ke server. Periksa koneksi internet Anda.'
        );
    },
    
    // Show validation errors
    showValidationErrors: function(errors) {
        const errorList = Array.isArray(errors) ? errors : Object.values(errors).flat();
        const errorHtml = errorList.map(error => `• ${error}`).join('<br>');
        
        alertError('Validasi Gagal', '', {
            html: errorHtml
        });
    },
    
    // Show success with redirect
    successWithRedirect: function(title, text, url, delay = 2000) {
        alertSuccess(title, text).then(() => {
            setTimeout(() => {
                window.location.href = url;
            }, delay);
        });
    },
    
    // Show loading with promise
    loadingWithPromise: function(promise, loadingTitle = 'Memproses...') {
        showLoading(loadingTitle);
        
        return promise
            .then(result => {
                hideLoading();
                return result;
            })
            .catch(error => {
                hideLoading();
                throw error;
            });
    }
};

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    // Optional: Replace standard alerts automatically
    // AlertUtils.replaceStandardAlerts();
    
    console.log('Universal Alert System initialized');
});

// Export for module systems
if (typeof module !== 'undefined' && module.exports) {
    module.exports = {
        alertSuccess,
        alertError,
        alertWarning,
        alertInfo,
        alertQuestion,
        confirmDelete,
        showLoading,
        hideLoading,
        toastSuccess,
        toastError,
        toastWarning,
        toastInfo,
        alertCustom,
        alertInput,
        alertMultipleInput,
        alertProgress,
        alertAutoClose,
        AlertUtils
    };
}
