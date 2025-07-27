/**
 * Modal Form Enhanced - Modern form functionality
 * Consistent dengan theme #667eea → #764ba2
 */

class ModalFormEnhanced {
    constructor() {
        this.init();
    }

    init() {
        this.setupFormValidation();
        this.setupFormSubmission();
        this.setupModalEvents();
        this.setupFormEnhancements();
        this.injectStyles();
    }

    injectStyles() {
        if (document.getElementById('modal-form-enhanced-styles')) return;

        const style = document.createElement('style');
        style.id = 'modal-form-enhanced-styles';
        style.textContent = `
            .form-floating {
                position: relative;
            }
            
            .form-floating .form-control {
                padding-top: 20px;
                padding-bottom: 8px;
            }
            
            .form-floating label {
                position: absolute;
                top: 0;
                left: 16px;
                height: 100%;
                padding: 12px 0;
                pointer-events: none;
                border: none;
                transform-origin: 0 0;
                transition: opacity 0.1s ease-in-out, transform 0.1s ease-in-out;
                color: #6c757d;
                font-size: 14px;
                font-weight: 400;
            }
            
            .form-floating .form-control:focus ~ label,
            .form-floating .form-control:not(:placeholder-shown) ~ label {
                opacity: 0.65;
                transform: scale(0.85) translateY(-0.5rem) translateX(0.15rem);
                color: #667eea;
                font-weight: 500;
            }
        `;
        document.head.appendChild(style);
    }

    setupFormValidation() {
        // Real-time validation
        $(document).on('input blur', '.form-control', function() {
            const $input = $(this);
            const value = $input.val().trim();
            const isRequired = $input.prop('required') || $input.closest('.form-group').find('label').hasClass('required');
            
            // Clear previous validation
            $input.removeClass('is-valid is-invalid');
            $input.siblings('.invalid-feedback, .valid-feedback').remove();
            
            if (isRequired && !value) {
                $input.addClass('is-invalid');
                $input.after('<div class="invalid-feedback">Field ini wajib diisi</div>');
                return;
            }
            
            // Email validation
            if ($input.attr('type') === 'email' && value) {
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(value)) {
                    $input.addClass('is-invalid');
                    $input.after('<div class="invalid-feedback">Format email tidak valid</div>');
                    return;
                }
            }
            
            // Phone validation
            if ($input.attr('type') === 'tel' && value) {
                const phoneRegex = /^[\d\s\-\+\(\)]+$/;
                if (!phoneRegex.test(value) || value.length < 10) {
                    $input.addClass('is-invalid');
                    $input.after('<div class="invalid-feedback">Nomor telepon tidak valid (minimal 10 digit)</div>');
                    return;
                }
            }
            
            // Number validation
            if ($input.attr('type') === 'number' && value) {
                const min = $input.attr('min');
                const max = $input.attr('max');
                const numValue = parseFloat(value);
                
                if (min && numValue < parseFloat(min)) {
                    $input.addClass('is-invalid');
                    $input.after(`<div class="invalid-feedback">Nilai minimal ${min}</div>`);
                    return;
                }
                
                if (max && numValue > parseFloat(max)) {
                    $input.addClass('is-invalid');
                    $input.after(`<div class="invalid-feedback">Nilai maksimal ${max}</div>`);
                    return;
                }
            }
            
            // Password validation
            if ($input.attr('type') === 'password' && value) {
                if (value.length < 6) {
                    $input.addClass('is-invalid');
                    $input.after('<div class="invalid-feedback">Password minimal 6 karakter</div>');
                    return;
                }
            }
            
            // If validation passes
            if (value) {
                $input.addClass('is-valid');
                $input.after('<div class="valid-feedback">Valid</div>');
            }
        });
    }

    setupFormSubmission() {
        // Enhanced form submission
        $(document).on('submit', '.modal form', function(e) {
            e.preventDefault();
            
            const $form = $(this);
            const $modal = $form.closest('.modal');
            const $submitBtn = $form.find('button[type="submit"], .btn-primary');
            
            // Validate form
            if (!modalFormEnhanced.validateForm($form)) {
                toastError('Mohon periksa kembali form Anda');
                return;
            }
            
            // Show loading state
            $submitBtn.addClass('loading').prop('disabled', true);
            const originalText = $submitBtn.text();
            $submitBtn.text('Memproses...');
            
            // Get form data
            const formData = new FormData($form[0]);
            const url = $form.attr('action');
            const method = $form.attr('method') || 'POST';
            
            // Submit form
            $.ajax({
                url: url,
                method: method,
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    $submitBtn.removeClass('loading').prop('disabled', false).text(originalText);
                    
                    if (response.success) {
                        $modal.modal('hide');
                        
                        // Reload table if exists
                        if (typeof table !== 'undefined' && table.ajax) {
                            table.ajax.reload();
                        }
                        
                        toastSuccess(response.message || 'Data berhasil disimpan!');
                    } else {
                        toastError(response.message || 'Terjadi kesalahan');
                    }
                },
                error: function(xhr) {
                    $submitBtn.removeClass('loading').prop('disabled', false).text(originalText);
                    
                    if (xhr.status === 422) {
                        // Validation errors
                        const errors = xhr.responseJSON.errors;
                        modalFormEnhanced.showValidationErrors($form, errors);
                        toastError('Mohon periksa kembali form Anda');
                    } else {
                        toastError('Terjadi kesalahan saat menyimpan data');
                    }
                }
            });
        });
    }

    setupModalEvents() {
        // Modal show event
        $(document).on('show.bs.modal', '.modal', function() {
            const $modal = $(this);
            
            // Reset form
            const $form = $modal.find('form');
            if ($form.length) {
                $form[0].reset();
                $form.find('.form-control').removeClass('is-valid is-invalid');
                $form.find('.invalid-feedback, .valid-feedback').remove();
            }
            
            // Focus first input
            setTimeout(() => {
                $modal.find('.form-control:visible:first').focus();
            }, 300);
        });
        
        // Modal hide event
        $(document).on('hide.bs.modal', '.modal', function() {
            const $modal = $(this);
            
            // Reset loading states
            $modal.find('.btn.loading').removeClass('loading').prop('disabled', false);
        });
    }

    setupFormEnhancements() {
        // Auto-resize textarea
        $(document).on('input', 'textarea.form-control', function() {
            this.style.height = 'auto';
            this.style.height = (this.scrollHeight) + 'px';
        });
        
        // File input enhancement
        $(document).on('change', 'input[type="file"].form-control', function() {
            const $input = $(this);
            const files = this.files;
            
            if (files.length > 0) {
                const fileName = files.length === 1 ? files[0].name : `${files.length} files selected`;
                $input.next('.file-info').remove();
                $input.after(`<small class="file-info text-muted mt-1">${fileName}</small>`);
            }
        });
        
        // Number input formatting
        $(document).on('input', 'input[type="number"].form-control', function() {
            const $input = $(this);
            let value = $input.val();
            
            // Format number with thousand separators for display
            if (value && !$input.is(':focus')) {
                const formatted = parseFloat(value).toLocaleString('id-ID');
                $input.data('raw-value', value);
                $input.val(formatted);
            }
        });
        
        $(document).on('focus', 'input[type="number"].form-control', function() {
            const $input = $(this);
            const rawValue = $input.data('raw-value');
            if (rawValue) {
                $input.val(rawValue);
            }
        });
        
        // Phone number formatting
        $(document).on('input', 'input[type="tel"].form-control', function() {
            let value = this.value.replace(/\D/g, '');
            
            // Format Indonesian phone number
            if (value.startsWith('62')) {
                value = value.replace(/^62/, '+62 ');
            } else if (value.startsWith('0')) {
                value = value.replace(/^0/, '0');
            }
            
            // Add spacing
            if (value.length > 3) {
                value = value.replace(/(\d{3,4})(\d{4})(\d{4})/, '$1-$2-$3');
            }
            
            this.value = value;
        });
    }

    validateForm($form) {
        let isValid = true;
        
        $form.find('.form-control[required], .form-control').each(function() {
            const $input = $(this);
            const value = $input.val().trim();
            const isRequired = $input.prop('required') || $input.closest('.form-group').find('label').hasClass('required');
            
            // Clear previous validation
            $input.removeClass('is-valid is-invalid');
            $input.siblings('.invalid-feedback').remove();
            
            if (isRequired && !value) {
                $input.addClass('is-invalid');
                $input.after('<div class="invalid-feedback">Field ini wajib diisi</div>');
                isValid = false;
                return;
            }
            
            // Additional validations...
            if ($input.attr('type') === 'email' && value) {
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(value)) {
                    $input.addClass('is-invalid');
                    $input.after('<div class="invalid-feedback">Format email tidak valid</div>');
                    isValid = false;
                    return;
                }
            }
            
            if (value) {
                $input.addClass('is-valid');
            }
        });
        
        return isValid;
    }

    showValidationErrors($form, errors) {
        Object.keys(errors).forEach(field => {
            const $input = $form.find(`[name="${field}"]`);
            if ($input.length) {
                $input.addClass('is-invalid');
                $input.siblings('.invalid-feedback').remove();
                $input.after(`<div class="invalid-feedback">${errors[field][0]}</div>`);
            }
        });
    }

    // Utility functions
    createFloatingLabel(inputId, labelText, required = false) {
        return `
            <div class="form-floating">
                <input type="text" class="form-control" id="${inputId}" name="${inputId}" placeholder=" " ${required ? 'required' : ''}>
                <label for="${inputId}" ${required ? 'class="required"' : ''}>${labelText}</label>
            </div>
        `;
    }

    createFormGroup(inputType, inputId, labelText, options = {}) {
        const required = options.required ? 'required' : '';
        const placeholder = options.placeholder || '';
        const helpText = options.helpText || '';
        const requiredClass = options.required ? 'required' : '';
        
        return `
            <div class="form-group">
                <label for="${inputId}" class="${requiredClass}">${labelText}</label>
                <input type="${inputType}" class="form-control" id="${inputId}" name="${inputId}" placeholder="${placeholder}" ${required}>
                ${helpText ? `<small class="form-text text-muted">${helpText}</small>` : ''}
            </div>
        `;
    }

    createSelectGroup(selectId, labelText, options = [], config = {}) {
        const required = config.required ? 'required' : '';
        const requiredClass = config.required ? 'required' : '';
        const helpText = config.helpText || '';
        
        let optionsHtml = '<option value="">Pilih...</option>';
        options.forEach(option => {
            optionsHtml += `<option value="${option.value}">${option.text}</option>`;
        });
        
        return `
            <div class="form-group">
                <label for="${selectId}" class="${requiredClass}">${labelText}</label>
                <select class="form-control" id="${selectId}" name="${selectId}" ${required}>
                    ${optionsHtml}
                </select>
                ${helpText ? `<small class="form-text text-muted">${helpText}</small>` : ''}
            </div>
        `;
    }

    createTextareaGroup(textareaId, labelText, options = {}) {
        const required = options.required ? 'required' : '';
        const placeholder = options.placeholder || '';
        const rows = options.rows || 3;
        const helpText = options.helpText || '';
        const requiredClass = options.required ? 'required' : '';
        
        return `
            <div class="form-group">
                <label for="${textareaId}" class="${requiredClass}">${labelText}</label>
                <textarea class="form-control" id="${textareaId}" name="${textareaId}" rows="${rows}" placeholder="${placeholder}" ${required}></textarea>
                ${helpText ? `<small class="form-text text-muted">${helpText}</small>` : ''}
            </div>
        `;
    }
}

// Initialize enhanced modal forms
const modalFormEnhanced = new ModalFormEnhanced();

// Global utility functions
window.ModalFormUtils = {
    // Show modal with form
    showModal: function(modalId, title, formAction, formMethod = 'POST') {
        const $modal = $(`#${modalId}`);
        if ($modal.length) {
            $modal.find('.modal-title').text(title);
            $modal.find('form').attr('action', formAction).attr('method', formMethod);
            $modal.modal('show');
        }
    },
    
    // Hide modal
    hideModal: function(modalId) {
        $(`#${modalId}`).modal('hide');
    },
    
    // Populate form with data
    populateForm: function(modalId, data) {
        const $modal = $(`#${modalId}`);
        const $form = $modal.find('form');
        
        Object.keys(data).forEach(key => {
            const $input = $form.find(`[name="${key}"]`);
            if ($input.length) {
                $input.val(data[key]).trigger('input');
            }
        });
    },
    
    // Create form builder
    FormBuilder: {
        input: modalFormEnhanced.createFormGroup,
        select: modalFormEnhanced.createSelectGroup,
        textarea: modalFormEnhanced.createTextareaGroup,
        floating: modalFormEnhanced.createFloatingLabel
    }
};

// Auto-initialize when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    console.log('✅ Modal Form Enhanced System initialized');
    console.log('✅ Modern form validation and styling ready');
});

// Export untuk module systems
if (typeof module !== 'undefined' && module.exports) {
    module.exports = {
        ModalFormEnhanced,
        ModalFormUtils
    };
}
