/**
 * DataTables Fix JS - Minimal JavaScript untuk fix masalah spesifik
 * 1. Remove validation classes
 * 2. Fix dropdown icons
 * 3. Maintain search input positioning
 */

$(document).ready(function() {
    
    // Function untuk apply fixes
    function applyDataTablesFixes() {
        $('.dataTables_wrapper').each(function() {
            const $wrapper = $(this);
            
            // 1. Remove validation classes dan messages
            $wrapper.find('.valid-feedback, .invalid-feedback, .validation-message').remove();
            $wrapper.find('.is-valid, .is-invalid').removeClass('is-valid is-invalid');
            $wrapper.find('.was-validated').removeClass('was-validated');
            
            // 2. Fix dropdown select - remove custom styling
            const $select = $wrapper.find('.dataTables_length select');
            $select.css({
                'background-image': 'none',
                '-webkit-appearance': 'menulist',
                '-moz-appearance': 'menulist',
                'appearance': 'menulist',
                'padding-right': '12px'
            });
            
            // 3. Fix search input positioning
            const $filter = $wrapper.find('.dataTables_filter');
            const $input = $filter.find('input');
            
            $filter.css({
                'text-align': 'right',
                'float': 'right'
            });
            
            $input.css({
                'text-align': 'left',
                'margin-left': '0.5em',
                'display': 'inline-block',
                'width': 'auto',
                'vertical-align': 'middle'
            });
            
            // 4. Fix show entries positioning
            const $length = $wrapper.find('.dataTables_length');
            $length.css({
                'text-align': 'left',
                'float': 'left'
            });
            
            $length.find('select').css({
                'margin': '0 0.5em',
                'display': 'inline-block',
                'width': 'auto',
                'vertical-align': 'middle'
            });
        });
    }
    
    // Apply fixes immediately
    setTimeout(applyDataTablesFixes, 100);
    
    // Apply fixes after DataTables initialization
    $(document).on('init.dt', function() {
        setTimeout(applyDataTablesFixes, 200);
    });
    
    // Apply fixes after DataTables draw
    $(document).on('draw.dt', function() {
        setTimeout(applyDataTablesFixes, 100);
    });
    
    // Maintain search input position during typing
    $(document).on('input keyup', '.dataTables_filter input', function() {
        const $input = $(this);
        $input.css({
            'text-align': 'left',
            'transform': 'none',
            'position': 'static'
        });
    });
    
    // Fix search input on focus/blur
    $(document).on('focus', '.dataTables_filter input', function() {
        $(this).css('text-align', 'left');
    });
    
    $(document).on('blur', '.dataTables_filter input', function() {
        $(this).css('text-align', 'left');
    });
    
    // Remove validation on select change
    $(document).on('change', '.dataTables_length select', function() {
        const $select = $(this);
        $select.removeClass('is-valid is-invalid');
        $select.siblings('.valid-feedback, .invalid-feedback').remove();
        
        // Ensure no custom icons
        $select.css({
            'background-image': 'none',
            'padding-right': '12px'
        });
    });
    
    // Monitor for new DataTables
    const observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            if (mutation.type === 'childList') {
                mutation.addedNodes.forEach(function(node) {
                    if (node.nodeType === 1) {
                        const $node = $(node);
                        if ($node.hasClass('dataTables_wrapper') || $node.find('.dataTables_wrapper').length > 0) {
                            setTimeout(applyDataTablesFixes, 300);
                        }
                    }
                });
            }
        });
    });
    
    observer.observe(document.body, {
        childList: true,
        subtree: true
    });
    
    console.log('✅ DataTables Fix applied');
    console.log('✅ Validation messages removed');
    console.log('✅ Dropdown icons fixed');
    console.log('✅ Search input positioning fixed');
});

// Global function untuk manual fix
window.fixDataTables = function() {
    $('.dataTables_wrapper').each(function() {
        const $wrapper = $(this);
        
        // Remove validation
        $wrapper.find('.valid-feedback, .invalid-feedback, .validation-message').remove();
        $wrapper.find('.is-valid, .is-invalid').removeClass('is-valid is-invalid');
        
        // Fix select
        $wrapper.find('.dataTables_length select').css({
            'background-image': 'none',
            'appearance': 'menulist',
            'padding-right': '12px'
        });
        
        // Fix search input
        $wrapper.find('.dataTables_filter input').css({
            'text-align': 'left',
            'transform': 'none',
            'position': 'static'
        });
    });
    
    console.log('✅ Manual DataTables fix applied');
};
