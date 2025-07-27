/**
 * Enhanced Confirm Delete System
 * Memastikan design modern dengan gradient #667eea → #764ba2
 */

// Enhanced confirmDelete function dengan styling yang dipaksa
window.confirmDelete = function(title = 'Apakah Anda yakin?', text = 'Data yang dihapus tidak dapat dikembalikan!', options = {}) {
    return Swal.fire({
        title: title,
        text: text,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal',
        reverseButtons: false,
        allowOutsideClick: false,
        allowEscapeKey: true,
        allowEnterKey: true,
        focusConfirm: false,
        focusCancel: true,
        buttonsStyling: false,
        customClass: {
            popup: 'swal2-popup-modern-delete',
            title: 'swal2-title-modern',
            htmlContainer: 'swal2-html-modern',
            confirmButton: 'swal2-confirm swal2-styled',
            cancelButton: 'swal2-cancel swal2-styled',
            actions: 'swal2-actions-modern'
        },
        showClass: {
            popup: 'swal2-show',
            backdrop: 'swal2-backdrop-show'
        },
        hideClass: {
            popup: 'swal2-hide',
            backdrop: 'swal2-backdrop-hide'
        },
        backdrop: true,
        heightAuto: true,
        width: 450,
        padding: '2em',
        background: '#fff',
        position: 'center',
        grow: false,
        ...options,
        // Force apply styles after render
        didOpen: (popup) => {
            // Apply modern styling
            applyModernStyling(popup);
            
            // Custom didOpen callback
            if (options.didOpen) {
                options.didOpen(popup);
            }
        }
    });
};

// Function to apply modern styling
function applyModernStyling(popup) {
    // Force apply popup styling
    popup.style.borderRadius = '20px';
    popup.style.boxShadow = '0 20px 60px rgba(102, 126, 234, 0.3)';
    popup.style.border = 'none';
    popup.style.padding = '35px';
    popup.style.fontFamily = "'Poppins', sans-serif";
    popup.style.position = 'relative';
    popup.style.overflow = 'hidden';
    popup.style.background = 'white';
    
    // Add gradient top border
    const gradientBorder = document.createElement('div');
    gradientBorder.style.cssText = `
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 5px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        z-index: 1;
    `;
    popup.insertBefore(gradientBorder, popup.firstChild);
    
    // Style title
    const title = popup.querySelector('.swal2-title');
    if (title) {
        title.style.fontFamily = "'Poppins', sans-serif";
        title.style.fontWeight = '700';
        title.style.fontSize = '24px';
        title.style.color = '#333';
        title.style.marginBottom = '15px';
        title.style.textAlign = 'center';
        title.style.position = 'relative';
        title.style.zIndex = '2';
        title.style.lineHeight = '1.3';
    }
    
    // Style content
    const content = popup.querySelector('.swal2-html-container');
    if (content) {
        content.style.fontFamily = "'Poppins', sans-serif";
        content.style.fontSize = '16px';
        content.style.color = '#666';
        content.style.lineHeight = '1.6';
        content.style.margin = '0 0 25px 0';
        content.style.position = 'relative';
        content.style.zIndex = '2';
        content.style.textAlign = 'center';
    }
    
    // Style icon
    const icon = popup.querySelector('.swal2-icon');
    if (icon) {
        icon.style.margin = '20px auto 25px auto';
        icon.style.border = 'none';
        icon.style.position = 'relative';
        icon.style.zIndex = '2';
        
        if (icon.classList.contains('swal2-warning')) {
            icon.style.borderColor = '#FF9800';
            icon.style.color = '#FF9800';
            
            const iconContent = icon.querySelector('.swal2-icon-content');
            if (iconContent) {
                iconContent.style.color = '#FF9800';
                iconContent.style.fontSize = '60px';
                iconContent.style.fontWeight = '600';
            }
        }
    }
    
    // Style buttons
    const confirmBtn = popup.querySelector('.swal2-confirm');
    if (confirmBtn) {
        applyButtonStyle(confirmBtn, 'delete');
    }
    
    const cancelBtn = popup.querySelector('.swal2-cancel');
    if (cancelBtn) {
        applyButtonStyle(cancelBtn, 'cancel');
    }
    
    // Style actions container
    const actions = popup.querySelector('.swal2-actions');
    if (actions) {
        actions.style.marginTop = '30px';
        actions.style.gap = '15px';
        actions.style.justifyContent = 'center';
        actions.style.flexWrap = 'wrap';
        actions.style.position = 'relative';
        actions.style.zIndex = '2';
    }
}

// Function to apply button styling
function applyButtonStyle(button, type) {
    // Common button styles
    button.style.border = 'none';
    button.style.borderRadius = '12px';
    button.style.padding = '14px 28px';
    button.style.fontFamily = "'Poppins', sans-serif";
    button.style.fontWeight = '600';
    button.style.fontSize = '15px';
    button.style.minWidth = '130px';
    button.style.cursor = 'pointer';
    button.style.transition = 'all 0.3s ease';
    button.style.position = 'relative';
    button.style.overflow = 'hidden';
    button.style.textTransform = 'none';
    button.style.letterSpacing = '0.5px';
    
    if (type === 'delete') {
        // Delete button styling
        button.style.background = 'linear-gradient(135deg, #f44336 0%, #d32f2f 100%)';
        button.style.color = 'white';
        button.style.boxShadow = '0 6px 20px rgba(244, 67, 54, 0.3)';
        
        // Add shimmer effect
        const shimmer = document.createElement('div');
        shimmer.style.cssText = `
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s ease;
            pointer-events: none;
        `;
        button.appendChild(shimmer);
        
        // Hover effects
        button.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-2px)';
            this.style.boxShadow = '0 8px 25px rgba(244, 67, 54, 0.4)';
            this.style.background = 'linear-gradient(135deg, #f66356 0%, #e53935 100%)';
            shimmer.style.left = '100%';
        });
        
        button.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
            this.style.boxShadow = '0 6px 20px rgba(244, 67, 54, 0.3)';
            this.style.background = 'linear-gradient(135deg, #f44336 0%, #d32f2f 100%)';
            shimmer.style.left = '-100%';
        });
        
        button.addEventListener('mousedown', function() {
            this.style.transform = 'translateY(0)';
            this.style.boxShadow = '0 4px 15px rgba(244, 67, 54, 0.3)';
        });
        
    } else if (type === 'cancel') {
        // Cancel button styling
        button.style.background = 'white';
        button.style.color = '#666';
        button.style.border = '2px solid #e1e5e9';
        
        // Add shimmer effect
        const shimmer = document.createElement('div');
        shimmer.style.cssText = `
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(0, 0, 0, 0.05), transparent);
            transition: left 0.5s ease;
            pointer-events: none;
        `;
        button.appendChild(shimmer);
        
        // Hover effects
        button.addEventListener('mouseenter', function() {
            this.style.background = '#f8f9fa';
            this.style.color = '#495057';
            this.style.borderColor = '#667eea';
            this.style.transform = 'translateY(-2px)';
            this.style.boxShadow = '0 6px 20px rgba(0, 0, 0, 0.1)';
            shimmer.style.left = '100%';
        });
        
        button.addEventListener('mouseleave', function() {
            this.style.background = 'white';
            this.style.color = '#666';
            this.style.borderColor = '#e1e5e9';
            this.style.transform = 'translateY(0)';
            this.style.boxShadow = 'none';
            shimmer.style.left = '-100%';
        });
        
        button.addEventListener('mousedown', function() {
            this.style.transform = 'translateY(0)';
            this.style.boxShadow = '0 2px 10px rgba(0, 0, 0, 0.1)';
        });
    }
}

// Enhanced alert functions dengan styling yang dipaksa
window.alertSuccess = function(title, text = '', options = {}) {
    return Swal.fire({
        icon: 'success',
        title: title,
        text: text,
        confirmButtonText: 'OK',
        buttonsStyling: false,
        customClass: {
            popup: 'swal2-popup-modern-success',
            confirmButton: 'swal2-confirm swal2-styled swal2-success-btn'
        },
        didOpen: (popup) => {
            applyModernStyling(popup);
            const confirmBtn = popup.querySelector('.swal2-confirm');
            if (confirmBtn) {
                applyButtonStyle(confirmBtn, 'success');
            }
        },
        ...options
    });
};

window.alertError = function(title, text = '', options = {}) {
    return Swal.fire({
        icon: 'error',
        title: title,
        text: text,
        confirmButtonText: 'OK',
        buttonsStyling: false,
        customClass: {
            popup: 'swal2-popup-modern-error',
            confirmButton: 'swal2-confirm swal2-styled swal2-error-btn'
        },
        didOpen: (popup) => {
            applyModernStyling(popup);
            const confirmBtn = popup.querySelector('.swal2-confirm');
            if (confirmBtn) {
                applyButtonStyle(confirmBtn, 'error');
            }
        },
        ...options
    });
};

window.alertWarning = function(title, text = '', options = {}) {
    return Swal.fire({
        icon: 'warning',
        title: title,
        text: text,
        confirmButtonText: 'OK',
        buttonsStyling: false,
        customClass: {
            popup: 'swal2-popup-modern-warning',
            confirmButton: 'swal2-confirm swal2-styled swal2-warning-btn'
        },
        didOpen: (popup) => {
            applyModernStyling(popup);
            const confirmBtn = popup.querySelector('.swal2-confirm');
            if (confirmBtn) {
                applyButtonStyle(confirmBtn, 'warning');
            }
        },
        ...options
    });
};

window.alertInfo = function(title, text = '', options = {}) {
    return Swal.fire({
        icon: 'info',
        title: title,
        text: text,
        confirmButtonText: 'OK',
        buttonsStyling: false,
        customClass: {
            popup: 'swal2-popup-modern-info',
            confirmButton: 'swal2-confirm swal2-styled swal2-info-btn'
        },
        didOpen: (popup) => {
            applyModernStyling(popup);
            const confirmBtn = popup.querySelector('.swal2-confirm');
            if (confirmBtn) {
                applyButtonStyle(confirmBtn, 'info');
            }
        },
        ...options
    });
};

// Enhanced loading functions
window.showLoading = function(title = 'Memproses...', text = 'Mohon tunggu sebentar') {
    return Swal.fire({
        title: title,
        text: text,
        allowOutsideClick: false,
        allowEscapeKey: false,
        showConfirmButton: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
};

window.hideLoading = function() {
    Swal.close();
};

// Enhanced toast notifications
window.toastSuccess = function(title, options = {}) {
    return Swal.fire({
        toast: true,
        position: 'top-end',
        icon: 'success',
        title: title,
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
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
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer);
            toast.addEventListener('mouseleave', Swal.resumeTimer);
        },
        ...options
    });
};

// Apply button styling for different types
function applyButtonStyle(button, type) {
    // Common styles
    button.style.border = 'none';
    button.style.borderRadius = '12px';
    button.style.padding = '14px 28px';
    button.style.fontFamily = "'Poppins', sans-serif";
    button.style.fontWeight = '600';
    button.style.fontSize = '15px';
    button.style.minWidth = '130px';
    button.style.cursor = 'pointer';
    button.style.transition = 'all 0.3s ease';
    button.style.position = 'relative';
    button.style.overflow = 'hidden';
    button.style.textTransform = 'none';
    button.style.letterSpacing = '0.5px';
    button.style.color = 'white';
    
    // Type-specific styles
    switch(type) {
        case 'success':
            button.style.background = 'linear-gradient(135deg, #4CAF50 0%, #45a049 100%)';
            button.style.boxShadow = '0 6px 20px rgba(76, 175, 80, 0.3)';
            break;
        case 'error':
        case 'delete':
            button.style.background = 'linear-gradient(135deg, #f44336 0%, #d32f2f 100%)';
            button.style.boxShadow = '0 6px 20px rgba(244, 67, 54, 0.3)';
            break;
        case 'warning':
            button.style.background = 'linear-gradient(135deg, #FF9800 0%, #F57C00 100%)';
            button.style.boxShadow = '0 6px 20px rgba(255, 152, 0, 0.3)';
            break;
        case 'info':
            button.style.background = 'linear-gradient(135deg, #2196F3 0%, #1976D2 100%)';
            button.style.boxShadow = '0 6px 20px rgba(33, 150, 243, 0.3)';
            break;
        case 'cancel':
            button.style.background = 'white';
            button.style.color = '#666';
            button.style.border = '2px solid #e1e5e9';
            button.style.boxShadow = 'none';
            break;
    }
}

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    console.log('Enhanced Confirm Delete System initialized');
    
    // Override default SweetAlert2 styling
    const style = document.createElement('style');
    style.textContent = `
        .swal2-popup {
            border-radius: 20px !important;
            box-shadow: 0 20px 60px rgba(102, 126, 234, 0.3) !important;
            font-family: 'Poppins', sans-serif !important;
        }
    `;
    document.head.appendChild(style);
});
