// js/click-script.js
function registerClick(event, url, bannerId) {
    event.preventDefault();

    console.log('Click registered'); // Verificación

    jQuery.ajax({
        url: ajax_object.ajax_url,
        type: 'POST',
        data: {
            action: 'register_click',
            url: url,
            banner_id: bannerId // Asegúrate de que esto se envía correctamente
        },
        success: function(response) {
            if (response.success) {
                console.log('AJAX request successful', response.data); // Verificación
            } else {
                console.log('AJAX request failed', response.data); // Verificación
            }
            window.location.href = url;
        },
        error: function(xhr, status, error) {
            console.log('AJAX request failed', error); // Verificación
            console.log('Response:', xhr.responseText); // Verificación
        }
    });
}

jQuery(document).ready(function($) {
    console.log('Script loaded'); // Verificación
});
