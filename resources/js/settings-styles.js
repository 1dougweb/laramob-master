/**
 * JavaScript for handling the styles settings page functionality
 */
document.addEventListener('DOMContentLoaded', function() {
    // Array of ids of fields with color pickers
    const colorFields = [
        'primary_color', 'secondary_color', 'sidebar_bg_color', 
        'text_color', 'icon_color', 'hover_bg_color', 
        'hover_text_color', 'hover_icon_color', 'active_bg_color', 
        'active_text_color', 'active_icon_color'
    ];
    
    // For each color field, set up the two-way binding
    colorFields.forEach(field => {
        const colorPicker = document.getElementById(field);
        const textInput = document.getElementById(field + '_text');
        
        if (colorPicker && textInput) {
            // Sync from color picker to text input
            colorPicker.addEventListener('input', function() {
                textInput.value = this.value;
                updatePreview();
            });
            
            // Sync from text input to color picker
            textInput.addEventListener('input', function() {
                // Ensure the value is a valid hex color
                if (/^#[0-9A-F]{6}$/i.test(this.value)) {
                    colorPicker.value = this.value;
                    updatePreview();
                }
            });
        }
    });
    
    // Update the preview based on current values
    function updatePreview() {
        // Sidebar preview
        updateElementStyle('preview_sidebar_bg', 'backgroundColor', 'sidebar_bg_color');
        updateElementStyle('preview_text', 'color', 'text_color');
        updateElementStyle('preview_icon', 'color', 'icon_color');
        
        // Hover states
        updateElementStyle('preview_menu_hover', 'backgroundColor', 'hover_bg_color');
        updateElementStyle('preview_hover_text', 'color', 'hover_text_color');
        updateElementStyle('preview_hover_icon', 'color', 'hover_icon_color');
        
        // Active states
        updateElementStyle('preview_menu_active', 'backgroundColor', 'active_bg_color');
        updateElementStyle('preview_active_text', 'color', 'active_text_color');
        updateElementStyle('preview_active_icon', 'color', 'active_icon_color');
        
        // UI Elements
        updateElementStyle('preview_primary_button', 'backgroundColor', 'primary_color');
        updateElementStyle('preview_secondary_button', 'borderColor', 'secondary_color');
        updateElementStyle('preview_input', 'borderColor', 'primary_color', false);
        updateElementStyle('preview_select', 'borderColor', 'primary_color', false);
        
        // Logo
        updateElementStyle('preview_logo_bg', 'backgroundColor', 'primary_color');
    }
    
    // Helper to update an element's style based on a color input
    function updateElementStyle(elementId, styleProp, inputId, direct = true) {
        const element = document.getElementById(elementId);
        if (!element) return;
        
        let value;
        if (direct) {
            const input = document.getElementById(inputId);
            if (input) value = input.value;
        } else {
            const input = document.getElementById(inputId + '_text');
            if (input) value = input.value;
        }
        
        if (value) {
            element.style[styleProp] = value;
        }
    }
    
    // Initialize the preview
    updatePreview();
    
    // Font family changes
    const fontFamilySelect = document.getElementById('font_family');
    if (fontFamilySelect) {
        fontFamilySelect.addEventListener('change', function() {
            document.documentElement.style.setProperty('--font-family', this.value);
        });
    }
    
    // Border radius changes
    const borderRadiusSelect = document.getElementById('border_radius');
    if (borderRadiusSelect) {
        borderRadiusSelect.addEventListener('change', function() {
            let radius = '0.375rem'; // default
            
            switch (this.value) {
                case 'none':
                    radius = '0';
                    break;
                case 'small':
                    radius = '0.25rem';
                    break;
                case 'large':
                    radius = '0.5rem';
                    break;
                case 'full':
                    radius = '9999px';
                    break;
            }
            
            const previewElements = document.querySelectorAll('#preview_primary_button, #preview_secondary_button, #preview_input, #preview_select');
            previewElements.forEach(el => {
                el.style.borderRadius = radius;
            });
        });
    }
}); 