/**
 * Delete Function Override dengan Toasty - STABLE VERSION
 * Menggunakan Toasty untuk toast notifications yang lebih stabil
 */

// Modern confirmDelete function dengan styling yang dipaksa
function modernConfirmDelete(title = 'Hapus Data?', text = 'Data yang dihapus tidak dapat dikembalikan!') {
    return new Promise((resolve) => {
        // Create and inject CSS if not exists
        if (!document.getElementById('modern-confirm-css')) {
            const css = document.createElement('style');
            css.id = 'modern-confirm-css';
            css.textContent = `
                .swal2-popup.modern-delete {
                    border-radius: 20px !important;
                    box-shadow: 0 25px 70px rgba(102, 126, 234, 0.4) !important;
                    border: none !important;
                    padding: 40px !important;
                    font-family: 'Poppins', sans-serif !important;
                    position: relative !important;
                    overflow: hidden !important;
                    background: white !important;
                    width: 480px !important;
                    max-width: 90vw !important;
                }
                
                .swal2-popup.modern-delete::before {
                    content: '';
                    position: absolute;
                    top: 0;
                    left: 0;
                    right: 0;
                    height: 6px;
                    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
                    z-index: 1;
                }
                
                .swal2-popup.modern-delete .swal2-title {
                    font-family: 'Poppins', sans-serif !important;
                    font-weight: 700 !important;
                    font-size: 26px !important;
                    color: #333 !important;
                    margin-bottom: 20px !important;
                    text-align: center !important;
                    line-height: 1.3 !important;
                    position: relative !important;
                    z-index: 2 !important;
                }
                
                .swal2-popup.modern-delete .swal2-html-container {
                    font-family: 'Poppins', sans-serif !important;
                    font-size: 17px !important;
                    color: #666 !important;
                    line-height: 1.6 !important;
                    margin: 0 0 30px 0 !important;
                    text-align: center !important;
                    position: relative !important;
                    z-index: 2 !important;
                }
                
                .swal2-popup.modern-delete .swal2-icon {
                    margin: 25px auto 30px auto !important;
                    border: none !important;
                    position: relative !important;
                    z-index: 2 !important;
                }
                
                .swal2-popup.modern-delete .swal2-icon.swal2-warning {
                    border-color: #FF9800 !important;
                    color: #FF9800 !important;
                }
                
                .swal2-popup.modern-delete .swal2-icon.swal2-warning .swal2-icon-content {
                    color: #FF9800 !important;
                    font-size: 65px !important;
                    font-weight: 600 !important;
                }
                
                .swal2-popup.modern-delete .swal2-actions {
                    margin-top: 35px !important;
                    gap: 18px !important;
                    justify-content: center !important;
                    flex-wrap: wrap !important;
                    position: relative !important;
                    z-index: 2 !important;
                }
                
                .swal2-confirm.modern-delete-btn {
                    background: linear-gradient(135deg, #f44336 0%, #d32f2f 100%) !important;
                    color: white !important;
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
                    box-shadow: 0 8px 25px rgba(244, 67, 54, 0.35) !important;
                }
                
                .swal2-confirm.modern-delete-btn:hover {
                    transform: translateY(-3px) scale(1.02) !important;
                    box-shadow: 0 12px 35px rgba(244, 67, 54, 0.45) !important;
                    background: linear-gradient(135deg, #f66356 0%, #e53935 100%) !important;
                }
                
                .swal2-confirm.modern-delete-btn:active {
                    transform: translateY(-1px) scale(0.98) !important;
                    box-shadow: 0 6px 20px rgba(244, 67, 54, 0.4) !important;
                }
                
                .swal2-cancel.modern-cancel-btn {
                    background: white !important;
                    color: #666 !important;
                    border: 2px solid #e1e5e9 !important;
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
                    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08) !important;
                }
                
                .swal2-cancel.modern-cancel-btn:hover {
                    background: #f8f9fa !important;
                    color: #495057 !important;
                    border-color: #667eea !important;
                    transform: translateY(-3px) scale(1.02) !important;
                    box-shadow: 0 8px 25px rgba(102, 126, 234, 0.15) !important;
                }
                
                .swal2-cancel.modern-cancel-btn:active {
                    transform: translateY(-1px) scale(0.98) !important;
                    box-shadow: 0 3px 12px rgba(0, 0, 0, 0.12) !important;
                }
                
                .swal2-backdrop-show {
                    background: rgba(0, 0, 0, 0.65) !important;
                    backdrop-filter: blur(4px) !important;
                }
                
                @media (max-width: 768px) {
                    .swal2-popup.modern-delete {
                        margin: 15px !important;
                        padding: 30px !important;
                        width: calc(100vw - 30px) !important;
                    }
                    
                    .swal2-popup.modern-delete .swal2-title {
                        font-size: 22px !important;
                    }
                    
                    .swal2-popup.modern-delete .swal2-html-container {
                        font-size: 15px !important;
                    }
                    
                    .swal2-confirm.modern-delete-btn,
                    .swal2-cancel.modern-cancel-btn {
                        padding: 14px 28px !important;
                        font-size: 15px !important;
                        min-width: 120px !important;
                    }
                }
            `;
            document.head.appendChild(css);
        }
        
        // Show SweetAlert2 dengan styling modern
        Swal.fire({
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
            width: 480,
            padding: '2em',
            background: '#fff',
            backdrop: true,
            customClass: {
                popup: 'modern-delete',
                confirmButton: 'modern-delete-btn',
                cancelButton: 'modern-cancel-btn'
            },
            showClass: {
                popup: 'animate__animated animate__fadeInDown animate__faster'
            },
            hideClass: {
                popup: 'animate__animated animate__fadeOutUp animate__faster'
            }
        }).then((result) => {
            resolve(result);
        });
    });
}

// Modern loading menggunakan Toasty
function showModernLoading(title = 'Memproses...', text = 'Mohon tunggu sebentar') {
    return toastLoading(`${title} ${text}`, {
        closable: false
    });
}

// Modern success menggunakan Toasty
function showModernSuccess(message = 'Berhasil!') {
    return toastSuccess(message, {
        duration: 3000
    });
}

// Modern error menggunakan Toasty
function showModernError(title = 'Gagal!', text = 'Terjadi kesalahan') {
    return toastError(text || title, {
        duration: 4000
    });
}

// Modern warning menggunakan Toasty
function showModernWarning(message = 'Peringatan!') {
    return toastWarning(message, {
        duration: 3500
    });
}

// Modern info menggunakan Toasty
function showModernInfo(message = 'Informasi') {
    return toastInfo(message, {
        duration: 3000
    });
}

// Enhanced deleteData function yang mengganti yang lama
function modernDeleteData(url, options = {}) {
    const config = {
        title: 'Hapus Data?',
        text: 'Data yang dihapus tidak dapat dikembalikan!',
        loadingText: 'Menghapus data...',
        successText: 'Data berhasil dihapus!',
        errorText: 'Tidak dapat menghapus data',
        ...options
    };
    
    modernConfirmDelete(config.title, config.text)
        .then(result => {
            if (result.isConfirmed) {
                // Show loading toast
                const loadingToast = showModernLoading(config.loadingText);
                
                // Perform delete
                $.post(url, {
                        '_token': $('[name=csrf-token]').attr('content'),
                        '_method': 'delete'
                    })
                    .done((response) => {
                        // Hide loading toast
                        if (loadingToast && loadingToast.remove) {
                            loadingToast.remove();
                        }
                        
                        // Reload table if exists
                        if (typeof table !== 'undefined' && table.ajax) {
                            table.ajax.reload();
                        }
                        
                        // Show success toast
                        showModernSuccess(config.successText);
                    })
                    .fail((errors) => {
                        // Hide loading toast
                        if (loadingToast && loadingToast.remove) {
                            loadingToast.remove();
                        }
                        
                        // Show error toast
                        showModernError('Gagal!', config.errorText);
                    });
            }
        });
}

// Auto-replace existing deleteData functions when DOM is ready
// document.addEventListener('DOMContentLoaded', function() {
//     // Wait a bit to ensure other scripts are loaded
//     setTimeout(() => {
//         // Override global deleteData function
//         if (typeof window.deleteData === 'function') {
//             window.originalDeleteData = window.deleteData;
//         }
        
//         // Replace with modern version
//         window.deleteData = modernDeleteData;
        
//         // Also make functions available globally
//         window.modernConfirmDelete = modernConfirmDelete;
//         window.showModernLoading = showModernLoading;
//         window.showModernSuccess = showModernSuccess;
//         window.showModernError = showModernError;
//         window.showModernWarning = showModernWarning;
//         window.showModernInfo = showModernInfo;
        
//         console.log('✅ Modern Delete Function Override with Toasty initialized');
//         console.log('✅ All deleteData() calls now use modern design with stable Toasty');
        
//         // Show notification that system is ready
//         setTimeout(() => {
//             if (typeof toastPrimary !== 'undefined') {
//                 toastPrimary('Modern Delete System with Toasty Ready!', {
//                     duration: 2000
//                 });
//             }
//         }, 1000);
        
//     }, 500);
// });

// Export functions for manual use
window.ModernDeleteToasty = {
    confirmDelete: modernConfirmDelete,
    showLoading: showModernLoading,
    showSuccess: showModernSuccess,
    showError: showModernError,
    showWarning: showModernWarning,
    showInfo: showModernInfo,
    deleteData: modernDeleteData
};
