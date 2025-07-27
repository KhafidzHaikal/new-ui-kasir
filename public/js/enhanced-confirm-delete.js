/**
 * Enhanced Confirm Delete System - Restored Beautiful Version
 * 
 * Mengembalikan tampilan confirming popup yang bagus seperti alert di stok produk
 * dengan design modern dan user experience yang excellent
 */

(function() {
    'use strict';
    
    console.log('🎨 Enhanced Confirm Delete System loaded');
    
    /**
     * Enhanced confirmDelete function dengan styling yang dipaksa
     */
    window.confirmDelete = function(title = 'Konfirmasi Hapus', text = 'Apakah Anda yakin ingin menghapus data ini? Data yang dihapus tidak dapat dikembalikan!', options = {}) {
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
                actions: 'swal2-actions-modern',
                icon: 'swal2-icon-modern'
            },
            showClass: {
                popup: 'animate__animated animate__fadeInDown animate__faster',
                backdrop: 'swal2-backdrop-show'
            },
            hideClass: {
                popup: 'animate__animated animate__fadeOutUp animate__faster',
                backdrop: 'swal2-backdrop-hide'
            },
            backdrop: true,
            heightAuto: true,
            width: 480,
            padding: '0',
            background: '#fff',
            position: 'center',
            grow: false,
            ...options,
            // Force apply styles after render
            didOpen: (popup) => {
                applyEnhancedStyling(popup);
                
                // Custom didOpen callback
                if (options.didOpen) {
                    options.didOpen(popup);
                }
            }
        });
    };

    /**
     * Enhanced confirmDeleteWithCallback - untuk integrasi yang mudah
     */
    window.confirmDeleteWithCallback = function(options = {}) {
        const defaults = {
            title: 'Konfirmasi Hapus',
            text: 'Apakah Anda yakin ingin menghapus data ini? Data yang dihapus tidak dapat dikembalikan!',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal',
            onConfirm: () => Promise.resolve(),
            onCancel: () => {},
            showLoading: true
        };

        const config = { ...defaults, ...options };

        return Swal.fire({
            title: config.title,
            text: config.text,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: config.confirmButtonText,
            cancelButtonText: config.cancelButtonText,
            reverseButtons: false,
            allowOutsideClick: false,
            allowEscapeKey: true,
            focusCancel: true,
            buttonsStyling: false,
            customClass: {
                popup: 'swal2-popup-modern-delete',
                confirmButton: 'swal2-confirm swal2-styled',
                cancelButton: 'swal2-cancel swal2-styled'
            },
            didOpen: (popup) => {
                applyEnhancedStyling(popup);
            }
        }).then((result) => {
            if (result.isConfirmed) {
                if (config.showLoading) {
                    Swal.showLoading();
                }
                return config.onConfirm();
            } else {
                // User cancelled - call onCancel and return resolved promise
                config.onCancel();
                return Promise.resolve();
            }
        });
    };

    /**
     * Function to apply enhanced modern styling
     */
    function applyEnhancedStyling(popup) {
        // Main popup styling
        popup.style.cssText += `
            border-radius: 25px !important;
            box-shadow: 0 25px 80px rgba(102, 126, 234, 0.25) !important;
            border: none !important;
            padding: 0 !important;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif !important;
            position: relative !important;
            overflow: hidden !important;
            background: white !important;
            max-width: 480px !important;
        `;
        
        // Add beautiful gradient header
        const gradientHeader = document.createElement('div');
        gradientHeader.style.cssText = `
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 6px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            z-index: 10;
        `;
        popup.insertBefore(gradientHeader, popup.firstChild);

        // Add content wrapper with proper padding
        const contentWrapper = document.createElement('div');
        contentWrapper.style.cssText = `
            padding: 40px 35px 35px 35px;
            position: relative;
            z-index: 5;
        `;
        
        // Move all content to wrapper
        const children = Array.from(popup.children).filter(child => child !== gradientHeader);
        children.forEach(child => {
            contentWrapper.appendChild(child);
        });
        popup.appendChild(contentWrapper);
        
        // Style icon with enhanced design
        const icon = popup.querySelector('.swal2-icon');
        if (icon) {
            icon.style.cssText += `
                margin: 0 auto 25px auto !important;
                border: none !important;
                position: relative !important;
                z-index: 6 !important;
                width: 80px !important;
                height: 80px !important;
                border-radius: 50% !important;
                background: linear-gradient(135deg, #ff6b6b, #ee5a24) !important;
                display: flex !important;
                align-items: center !important;
                justify-content: center !important;
                animation: pulse 2s infinite !important;
            `;
            
            if (icon.classList.contains('swal2-warning')) {
                const iconContent = icon.querySelector('.swal2-icon-content');
                if (iconContent) {
                    iconContent.style.cssText += `
                        color: white !important;
                        font-size: 40px !important;
                        font-weight: 700 !important;
                        text-shadow: 0 2px 4px rgba(0,0,0,0.2) !important;
                    `;
                }
            }
        }
        
        // Style title with modern typography
        const title = popup.querySelector('.swal2-title');
        if (title) {
            title.style.cssText += `
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif !important;
                font-weight: 700 !important;
                font-size: 26px !important;
                color: #2c3e50 !important;
                margin-bottom: 15px !important;
                text-align: center !important;
                position: relative !important;
                z-index: 6 !important;
                line-height: 1.3 !important;
                letter-spacing: -0.5px !important;
            `;
        }
        
        // Style content text
        const content = popup.querySelector('.swal2-html-container');
        if (content) {
            content.style.cssText += `
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif !important;
                font-size: 16px !important;
                color: #7f8c8d !important;
                line-height: 1.6 !important;
                margin: 0 0 30px 0 !important;
                position: relative !important;
                z-index: 6 !important;
                text-align: center !important;
                font-weight: 400 !important;
            `;
        }
        
        // Style buttons with enhanced design
        const confirmBtn = popup.querySelector('.swal2-confirm');
        if (confirmBtn) {
            applyEnhancedButtonStyle(confirmBtn, 'delete');
        }
        
        const cancelBtn = popup.querySelector('.swal2-cancel');
        if (cancelBtn) {
            applyEnhancedButtonStyle(cancelBtn, 'cancel');
        }
        
        // Style actions container
        const actions = popup.querySelector('.swal2-actions');
        if (actions) {
            actions.style.cssText += `
                margin-top: 35px !important;
                gap: 15px !important;
                justify-content: center !important;
                flex-wrap: wrap !important;
                position: relative !important;
                z-index: 6 !important;
                padding: 0 !important;
            `;
        }

        // Add subtle background pattern
        const backgroundPattern = document.createElement('div');
        backgroundPattern.style.cssText = `
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: radial-gradient(circle at 20% 80%, rgba(102, 126, 234, 0.05) 0%, transparent 50%),
                        radial-gradient(circle at 80% 20%, rgba(118, 75, 162, 0.05) 0%, transparent 50%);
            z-index: 1;
            pointer-events: none;
        `;
        popup.appendChild(backgroundPattern);
    }

    /**
     * Apply enhanced button styling
     */
    function applyEnhancedButtonStyle(button, type) {
        const baseStyle = `
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif !important;
            font-weight: 600 !important;
            font-size: 15px !important;
            padding: 12px 30px !important;
            border-radius: 25px !important;
            border: none !important;
            cursor: pointer !important;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
            text-transform: none !important;
            letter-spacing: 0.3px !important;
            position: relative !important;
            z-index: 6 !important;
            min-width: 120px !important;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1) !important;
        `;

        if (type === 'delete') {
            button.style.cssText += baseStyle + `
                background: linear-gradient(135deg, #e74c3c, #c0392b) !important;
                color: white !important;
                box-shadow: 0 4px 15px rgba(231, 76, 60, 0.3) !important;
            `;
            
            button.addEventListener('mouseenter', () => {
                button.style.cssText += `
                    transform: translateY(-2px) !important;
                    box-shadow: 0 8px 25px rgba(231, 76, 60, 0.4) !important;
                    background: linear-gradient(135deg, #c0392b, #a93226) !important;
                `;
            });
            
            button.addEventListener('mouseleave', () => {
                button.style.cssText += `
                    transform: translateY(0) !important;
                    box-shadow: 0 4px 15px rgba(231, 76, 60, 0.3) !important;
                    background: linear-gradient(135deg, #e74c3c, #c0392b) !important;
                `;
            });
        } else if (type === 'cancel') {
            button.style.cssText += baseStyle + `
                background: linear-gradient(135deg, #95a5a6, #7f8c8d) !important;
                color: white !important;
                box-shadow: 0 4px 15px rgba(149, 165, 166, 0.3) !important;
            `;
            
            button.addEventListener('mouseenter', () => {
                button.style.cssText += `
                    transform: translateY(-2px) !important;
                    box-shadow: 0 8px 25px rgba(149, 165, 166, 0.4) !important;
                    background: linear-gradient(135deg, #7f8c8d, #6c7b7d) !important;
                `;
            });
            
            button.addEventListener('mouseleave', () => {
                button.style.cssText += `
                    transform: translateY(0) !important;
                    box-shadow: 0 4px 15px rgba(149, 165, 166, 0.3) !important;
                    background: linear-gradient(135deg, #95a5a6, #7f8c8d) !important;
                `;
            });
        }
    }

    /**
     * Success notification after delete - Fixed with simple Font Awesome icon
     */
    window.showDeleteSuccess = function(message = 'Data berhasil dihapus!') {
        return Swal.fire({
            title: 'Berhasil!',
            text: message,
            timer: 2500,
            timerProgressBar: true,
            showConfirmButton: false,
            customClass: {
                popup: 'swal2-popup-success-modern'
            },
            // Use custom HTML instead of default success icon
            html: `
                <div style="text-align: center; margin: 20px 0;">
                    <div style="
                        width: 80px;
                        height: 80px;
                        background: linear-gradient(135deg, #27ae60, #2ecc71);
                        border-radius: 50%;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        margin: 0 auto 20px auto;
                        box-shadow: 0 8px 25px rgba(39, 174, 96, 0.3);
                        animation: successPulse 0.6s ease-out;
                    ">
                        <i class="fa fa-check" style="
                            color: white;
                            font-size: 32px;
                            font-weight: bold;
                        "></i>
                    </div>
                    <h3 style="
                        color: #27ae60;
                        font-weight: 700;
                        font-size: 24px;
                        margin: 0 0 15px 0;
                        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                    ">Berhasil!</h3>
                    <p style="
                        color: #666;
                        font-size: 16px;
                        margin: 0;
                        line-height: 1.5;
                        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                    ">${message}</p>
                </div>
            `,
            didOpen: (popup) => {
                // Apply modern popup styling
                popup.style.cssText += `
                    border-radius: 20px !important;
                    box-shadow: 0 20px 60px rgba(39, 174, 96, 0.2) !important;
                    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif !important;
                    padding: 30px !important;
                `;
                
                // Add CSS animation for success pulse
                if (!document.getElementById('successPulseAnimation')) {
                    const style = document.createElement('style');
                    style.id = 'successPulseAnimation';
                    style.textContent = `
                        @keyframes successPulse {
                            0% {
                                transform: scale(0.8);
                                opacity: 0;
                            }
                            50% {
                                transform: scale(1.1);
                            }
                            100% {
                                transform: scale(1);
                                opacity: 1;
                            }
                        }
                    `;
                    document.head.appendChild(style);
                }
            }
        });
    };

    /**
     * Error notification - Fixed with simple Font Awesome icon
     */
    window.showDeleteError = function(message = 'Gagal menghapus data!') {
        return Swal.fire({
            title: 'Gagal!',
            confirmButtonText: 'OK',
            customClass: {
                popup: 'swal2-popup-error-modern'
            },
            // Use custom HTML instead of default error icon
            html: `
                <div style="text-align: center; margin: 20px 0;">
                    <div style="
                        width: 80px;
                        height: 80px;
                        background: linear-gradient(135deg, #e74c3c, #c0392b);
                        border-radius: 50%;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        margin: 0 auto 20px auto;
                        box-shadow: 0 8px 25px rgba(231, 76, 60, 0.3);
                        animation: errorShake 0.6s ease-out;
                    ">
                        <i class="fa fa-times" style="
                            color: white;
                            font-size: 32px;
                            font-weight: bold;
                        "></i>
                    </div>
                    <h3 style="
                        color: #e74c3c;
                        font-weight: 700;
                        font-size: 24px;
                        margin: 0 0 15px 0;
                        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                    ">Gagal!</h3>
                    <p style="
                        color: #666;
                        font-size: 16px;
                        margin: 0;
                        line-height: 1.5;
                        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                    ">${message}</p>
                </div>
            `,
            didOpen: (popup) => {
                // Apply modern popup styling
                popup.style.cssText += `
                    border-radius: 20px !important;
                    box-shadow: 0 20px 60px rgba(231, 76, 60, 0.2) !important;
                    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif !important;
                    padding: 30px !important;
                `;
                
                // Style confirm button
                const confirmBtn = popup.querySelector('.swal2-confirm');
                if (confirmBtn) {
                    applyEnhancedButtonStyle(confirmBtn, 'delete');
                }
                
                // Add CSS animation for error shake
                if (!document.getElementById('errorShakeAnimation')) {
                    const style = document.createElement('style');
                    style.id = 'errorShakeAnimation';
                    style.textContent = `
                        @keyframes errorShake {
                            0%, 100% {
                                transform: translateX(0);
                            }
                            10%, 30%, 50%, 70%, 90% {
                                transform: translateX(-5px);
                            }
                            20%, 40%, 60%, 80% {
                                transform: translateX(5px);
                            }
                        }
                    `;
                    document.head.appendChild(style);
                }
            }
        });
    };

    // Add CSS animations
    const animationCSS = `
        <style>
        @keyframes pulse {
            0% {
                transform: scale(1);
                box-shadow: 0 0 0 0 rgba(255, 107, 107, 0.4);
            }
            70% {
                transform: scale(1.05);
                box-shadow: 0 0 0 10px rgba(255, 107, 107, 0);
            }
            100% {
                transform: scale(1);
                box-shadow: 0 0 0 0 rgba(255, 107, 107, 0);
            }
        }
        
        .swal2-popup-modern-delete {
            animation: modalSlideIn 0.4s cubic-bezier(0.4, 0, 0.2, 1) !important;
        }
        
        @keyframes modalSlideIn {
            from {
                opacity: 0;
                transform: scale(0.8) translateY(-50px);
            }
            to {
                opacity: 1;
                transform: scale(1) translateY(0);
            }
        }
        
        .swal2-backdrop-show {
            animation: backdropFadeIn 0.3s ease-out !important;
        }
        
        @keyframes backdropFadeIn {
            from { opacity: 0; }
            to { opacity: 0.4; }
        }
        </style>
    `;
    
    document.head.insertAdjacentHTML('beforeend', animationCSS);
    
    console.log('✅ Enhanced Confirm Delete System ready');
    
})();
