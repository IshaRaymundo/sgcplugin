// js/click-script.js
function registerClick(event, url) {
    event.preventDefault();
    
    console.log('Click registered'); // Verificación

    jQuery.ajax({
        url: ajax_object.ajax_url,
        type: 'POST',
        data: {
            action: 'register_click',
            url: url
        },
        success: function(response) {
            console.log('AJAX request successful'); // Verificación
            window.location.href = url;
        },
        error: function(error) {
            console.log('AJAX request failed', error); // Verificación
        }
    });
}

jQuery(document).ready(function($) {
    console.log('Script loaded'); // Verificación
});
