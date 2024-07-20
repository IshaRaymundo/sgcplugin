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
            if (response.success) {
                console.log('AJAX request successful', response.data); // Verificación
            } else {
                console.log('AJAX request failed', response.data); // Verificación
            }
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
