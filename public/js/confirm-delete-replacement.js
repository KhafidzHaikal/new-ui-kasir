/**
 * Confirm Delete Replacement - Override Browser Default
 * Mengganti semua confirm() browser dengan design modern
 */

// Override browser confirm() function
window.originalConfirm = window.confirm;

window.confirm = function(message) {
    // Jika pesan mengandung kata "hapus" atau "delete", gunakan confirmDelete
    if (message.toLowerCase().includes('hapus') || 
        message.toLowerCase().includes('delete') || 
        message.toLowerCase().includes('yakin')) {
        
        // Return Promise yang kompatibel dengan if statement
        let result = false;
        
        confirmDelete('Konfirmasi Hapus', message)
            .then(swalResult => {
                result = swalResult.isConfirmed;
                
                // Trigger event untuk memberitahu bahwa confirm selesai
                window.dispatchEvent(new CustomEvent('confirmComplete', {
                    detail: { confirmed: result }
                }));
            });
        
        // Return false untuk mencegah eksekusi langsung
        // Eksekusi akan dilanjutkan melalui event handler
        return false;
    }
    
    // Untuk confirm lainnya, gunakan original
    return originalConfirm(message);
};

// Enhanced confirmDelete dengan styling yang dipaksa
window.confirmDelete = function(title = 'Konfirmasi', text = 'Apakah Anda yakin?', options = {}) {
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
        buttonsStyling: false,
        width: 450,
        padding: '2em',
        background: '#fff',
        backdrop: true,
        customClass: {
            popup: 'swal2-popup-enhanced',
            title: 'swal2-title-enhanced',
            htmlContainer: 'swal2-content-enhanced',
            confirmButton: 'swal2-confirm-enhanced',
            cancelButton: 'swal2-cancel-enhanced',
            actions: 'swal2-actions-enhanced'
        },
        didOpen: (popup) => {
            // Force apply modern styling
            applyEnhancedStyling(popup);
        },
        ...options
    });
};

// Function untuk apply styling yang dipaksa
function applyEnhancedStyling(popup) {
    // Popup container styling
    popup.style.cssText += `
        border-radius: 20px !important;
        box-shadow: 0 25px 70px rgba(102, 126, 234, 0.35) !important;
        border: none !important;
        padding: 40px !important;
        font-family: 'Poppins', sans-serif !important;
        position: relative !important;
        overflow: hidden !important;
        background: white !important;
        max-width: 90vw !important;
    `;
    
    // Add gradient top border
    if (!popup.querySelector('.gradient-border')) {
        const gradientBorder = document.createElement('div');
        gradientBorder.className = 'gradient-border';
        gradientBorder.style.cssText = `
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 6px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            z-index: 10;
        `;
        popup.insertBefore(gradientBorder, popup.firstChild);
    }
    
    // Style title
    const title = popup.querySelector('.swal2-title');
    if (title) {
        title.style.cssText += `
            font-family: 'Poppins', sans-serif !important;
            font-weight: 700 !important;
            font-size: 26px !important;
            color: #333 !important;
            margin-bottom: 20px !important;
            text-align: center !important;
            line-height: 1.3 !important;
            position: relative !important;
            z-index: 2 !important;
        `;
    }
    
    // Style content
    const content = popup.querySelector('.swal2-html-container');
    if (content) {
        content.style.cssText += `
            font-family: 'Poppins', sans-serif !important;
            font-size: 17px !important;
            color: #666 !important;
            line-height: 1.6 !important;
            margin: 0 0 30px 0 !important;
            text-align: center !important;
            position: relative !important;
            z-index: 2 !important;
        `;
    }
    
    // Style icon
    const icon = popup.querySelector('.swal2-icon');
    if (icon) {
        icon.style.cssText += `
            margin: 25px auto 30px auto !important;
            border: none !important;
            position: relative !important;
            z-index: 2 !important;
        `;
        
        if (icon.classList.contains('swal2-warning')) {
            icon.style.borderColor = '#FF9800 !important';
            icon.style.color = '#FF9800 !important';
            
            const iconContent = icon.querySelector('.swal2-icon-content');
            if (iconContent) {
                iconContent.style.cssText += `
                    color: #FF9800 !important;
                    font-size: 65px !important;
                    font-weight: 600 !important;
                `;
            }
        }
    }
    
    // Style buttons
    const confirmBtn = popup.querySelector('.swal2-confirm');
    if (confirmBtn) {
        applyButtonStyling(confirmBtn, 'confirm');
    }
    
    const cancelBtn = popup.querySelector('.swal2-cancel');
    if (cancelBtn) {
        applyButtonStyling(cancelBtn, 'cancel');
    }
    
    // Style actions container
    const actions = popup.querySelector('.swal2-actions');
    if (actions) {
        actions.style.cssText += `
            margin-top: 35px !important;
            gap: 18px !important;
            justify-content: center !important;
            flex-wrap: wrap !important;
            position: relative !important;
            z-index: 2 !important;
        `;
    }
}

// Function untuk styling button
function applyButtonStyling(button, type) {
    // Common button styles
    button.style.cssText += `
        border: none !important;
        border-radius: 14px !important;
        padding: 16px 32px !important;
        font-family: 'Poppins', sans-serif !important;
        font-weight: 600 !important;
        font-size: 16px !important;
        min-width: 140px !important;
        cursor: pointer !important;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
        position: relative !important;
        overflow: hidden !important;
        text-transform: none !important;
        letter-spacing: 0.5px !important;
        outline: none !important;
    `;
    
    if (type === 'confirm') {
        // Confirm button (Delete) styling
        button.style.cssText += `
            background: linear-gradient(135deg, #f44336 0%, #d32f2f 100%) !important;
            color: white !important;
            box-shadow: 0 8px 25px rgba(244, 67, 54, 0.35) !important;
        `;
        
        // Add shimmer effect
        if (!button.querySelector('.shimmer-effect')) {
            const shimmer = document.createElement('div');
            shimmer.className = 'shimmer-effect';
            shimmer.style.cssText = `
                position: absolute;
                top: 0;
                left: -100%;
                width: 100%;
                height: 100%;
                background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.25), transparent);
                transition: left 0.6s ease;
                pointer-events: none;
            `;
            button.appendChild(shimmer);
        }
        
        // Enhanced hover effects
        button.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-3px) scale(1.02)';
            this.style.boxShadow = '0 12px 35px rgba(244, 67, 54, 0.45)';
            this.style.background = 'linear-gradient(135deg, #f66356 0%, #e53935 100%)';
            const shimmer = this.querySelector('.shimmer-effect');
            if (shimmer) shimmer.style.left = '100%';
        });
        
        button.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0) scale(1)';
            this.style.boxShadow = '0 8px 25px rgba(244, 67, 54, 0.35)';
            this.style.background = 'linear-gradient(135deg, #f44336 0%, #d32f2f 100%)';
            const shimmer = this.querySelector('.shimmer-effect');
            if (shimmer) shimmer.style.left = '-100%';
        });
        
        button.addEventListener('mousedown', function() {
            this.style.transform = 'translateY(-1px) scale(0.98)';
            this.style.boxShadow = '0 6px 20px rgba(244, 67, 54, 0.4)';
        });
        
    } else if (type === 'cancel') {
        // Cancel button styling
        button.style.cssText += `
            background: white !important;
            color: #666 !important;
            border: 2px solid #e1e5e9 !important;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08) !important;
        `;
        
        // Add shimmer effect
        if (!button.querySelector('.shimmer-effect')) {
            const shimmer = document.createElement('div');
            shimmer.className = 'shimmer-effect';
            shimmer.style.cssText = `
                position: absolute;
                top: 0;
                left: -100%;
                width: 100%;
                height: 100%;
                background: linear-gradient(90deg, transparent, rgba(0, 0, 0, 0.06), transparent);
                transition: left 0.6s ease;
                pointer-events: none;
            `;
            button.appendChild(shimmer);
        }
        
        // Enhanced hover effects
        button.addEventListener('mouseenter', function() {
            this.style.background = '#f8f9fa';
            this.style.color = '#495057';
            this.style.borderColor = '#667eea';
            this.style.transform = 'translateY(-3px) scale(1.02)';
            this.style.boxShadow = '0 8px 25px rgba(102, 126, 234, 0.15)';
            const shimmer = this.querySelector('.shimmer-effect');
            if (shimmer) shimmer.style.left = '100%';
        });
        
        button.addEventListener('mouseleave', function() {
            this.style.background = 'white';
            this.style.color = '#666';
            this.style.borderColor = '#e1e5e9';
            this.style.transform = 'translateY(0) scale(1)';
            this.style.boxShadow = '0 4px 15px rgba(0, 0, 0, 0.08)';
            const shimmer = this.querySelector('.shimmer-effect');
            if (shimmer) shimmer.style.left = '-100%';
        });
        
        button.addEventListener('mousedown', function() {
            this.style.transform = 'translateY(-1px) scale(0.98)';
            this.style.boxShadow = '0 3px 12px rgba(0, 0, 0, 0.12)';
        });
    }
}

// Enhanced deleteData function yang menggunakan confirmDelete
window.deleteDataEnhanced = function(url, options = {}) {
    const defaultOptions = {
        title: 'Hapus Data?',
        text: 'Data yang dihapus tidak dapat dikembalikan!',
        loadingText: 'Menghapus data...',
        successText: 'Data berhasil dihapus!',
        errorText: 'Tidak dapat menghapus data'
    };
    
    const config = { ...defaultOptions, ...options };
    
    confirmDelete(config.title, config.text)
        .then(result => {
            if (result.isConfirmed) {
                // Show loading
                Swal.fire({
                    title: config.loadingText,
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    showConfirmButton: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                
                // Perform delete
                $.post(url, {
                        '_token': $('[name=csrf-token]').attr('content'),
                        '_method': 'delete'
                    })
                    .done((response) => {
                        Swal.close();
                        
                        // Reload table if exists
                        if (typeof table !== 'undefined' && table.ajax) {
                            table.ajax.reload();
                        }
                        
                        // Show success toast
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'success',
                            title: config.successText,
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true,
                            background: 'linear-gradient(135deg, #4CAF50 0%, #45a049 100%)',
                            color: 'white'
                        });
                    })
                    .fail((errors) => {
                        Swal.close();
                        
                        // Show error alert
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: config.errorText,
                            confirmButtonText: 'OK',
                            buttonsStyling: false,
                            customClass: {
                                confirmButton: 'swal2-confirm-enhanced'
                            },
                            didOpen: (popup) => {
                                applyEnhancedStyling(popup);
                            }
                        });
                    });
            }
        });
};

// Auto-replace existing deleteData functions
document.addEventListener('DOMContentLoaded', function() {
    // Override global deleteData if it exists
    if (typeof window.deleteData === 'function') {
        window.originalDeleteData = window.deleteData;
        window.deleteData = window.deleteDataEnhanced;
    }
    
    console.log('Confirm Delete Replacement System initialized');
});

// CSS Injection untuk memastikan styling
const style = document.createElement('style');
style.textContent = `
    .swal2-popup-enhanced {
        border-radius: 20px !important;
        box-shadow: 0 25px 70px rgba(102, 126, 234, 0.35) !important;
        font-family: 'Poppins', sans-serif !important;
    }
    
    .swal2-backdrop-show {
        background: rgba(0, 0, 0, 0.65) !important;
        backdrop-filter: blur(4px) !important;
    }
    
    @media (max-width: 768px) {
        .swal2-popup-enhanced {
            margin: 15px !important;
            padding: 30px !important;
            width: calc(100vw - 30px) !important;
        }
    }
`;
document.head.appendChild(style);
