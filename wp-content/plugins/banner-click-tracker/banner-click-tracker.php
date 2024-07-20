<?php
/*
Plugin Name: Banner Click Tracker
Description: Rastrea las interacciones de los usuarios con los banners.
Version: 1.0
Author: UT
*/

// Incluir los archivos necesarios
include(plugin_dir_path(__FILE__) . 'clicks.php');
include(plugin_dir_path(__FILE__) . 'customers.php');
include(plugin_dir_path(__FILE__) . 'reports.php');
include(plugin_dir_path(__FILE__) . 'home.php');

if (file_exists(plugin_dir_path(__FILE__) . 'vendor/autoload.php')) {
    require plugin_dir_path(__FILE__) . 'vendor/autoload.php';
} else {
    error_log('Error: No se encontró el archivo autoload.php de Composer');
    return; // Detener la ejecución del plugin si no se encuentra autoload.php
}

use Jenssegers\Agent\Agent;

// Registrar el menú de administración
function sgc_register_menu_page() {
    add_menu_page(
        'SGC Plugin', 'SGC', 'manage_options', 'sgc-plugin', 'sgc_display_admin_page', 'dashicons-chart-line', 6
    );
    add_submenu_page(
        'sgc-plugin',
        'Inicio',
        'Inicio',
        'manage_options',
        'sgc-home',
        'sgc_home_page'
    );
    add_submenu_page(
        'sgc-plugin',
        'Banners',
        'Banners',
        'manage_options',
        'sgc-plugin',
        'sgc_display_admin_page'
    );
    add_submenu_page(
        'sgc-plugin',
        'Clicks',
        'Clicks',
        'manage_options',
        'sgc-clicks',
        'sgc_clicks_page'
    );
    add_submenu_page(
        'sgc-plugin',
        'Clientes',
        'Clientes',
        'manage_options',
        'sgc-customers',
        'sgc_customers_page'
    );
    add_submenu_page(
        'sgc-plugin',
        'Reportes',
        'Reportes',
        'manage_options',
        'sgc-reports',
        'sgc_reports_page'
    );
}
add_action('admin_menu', 'sgc_register_menu_page');

// Mostrar la página de administración principal
function sgc_display_admin_page() {
    ?>
    <div class="wrap">
        <div id="sgc-dashboard">
            <div class="container">
                <header class="header">
                    <div class="logo">
                        <h1>Gestión de Banners</h1>
                    </div>
                    <nav class="nav-bar">
                        <ul>
                            <li><a href="admin.php?page=sgc-home">Inicio</a></li>
                            <li><a href="admin.php?page=sgc-plugin">Banners</a></li>
                            <li><a href="admin.php?page=sgc-clicks">Clicks</a></li>
                            <li><a href="admin.php?page=sgc-customers">Clientes</a></li>
                            <li><a href="admin.php?page=sgc-reports">Reportes</a></li>
                        </ul>
                    </nav>
                </header>
                <main class="main-content">
                    <div class="chart-and-filter">
                        <section class="chart-container">
                            <h2>Seguimiento de clics</h2>
                            <p>Obtén informes detallados sobre el seguimiento de clics por medio de gráficos.</p>
                            <div id="chart">
                                <canvas id="clicksChart"></canvas>
                            </div>
                        </section>
                        <aside class="filter-container">
                        <button id="addBannerButton" class="add-banner-button">Agregar banner</button>
                            <h2>Filtrar Búsqueda</h2>
                            <form id="filters">
                                <label for="date-range">Seleccione un rango de fechas:</label>
                                <input type="date" id="date-start" name="date-start">
                                <input type="date" id="date-end" name="date-end">
                                
                                <label for="banner-name">Nombre del banner:</label>
                                <input type="text" id="banner-name" name="banner-name">
                                
                                <label for="user-name">Nombre del usuario:</label>
                                <input type="text" id="user-name" name="user-name">
                                
                                <button type="submit">Filtrar</button>
                            </form>
                        </aside>
                    </div>
                    <section class="table-container">
                        <h2>Tabla de clics</h2>
                        <p>Obtén informes detallados sobre el seguimiento de clics por medio de tablas.</p>
                        <table id="data-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nombre del banner</th>
                                    <th>Localización</th>
                                    <th>Fecha</th>
                                    <th>Total de clics</th>
                                    <th>Total de usuarios</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Datos llenados por JavaScript -->
                            </tbody>
                        </table>
                    </section>
                </main>
            </div>
        </div>
    </div>
    <div id="addBannerModal" class="modal"> 
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Agregar nuevo banner</h2>
            <form id="addBannerForm">
                <label for="bannerName">Nombre del banner:</label>
                <input type="text" id="bannerName" name="bannerName">
                
                <label for="bannerLocation">Localización del banner:</label>
                <input type="text" id="bannerLocation" name="bannerLocation">
                
                <button type="submit">Agregar</button>
            </form>
        </div>
    </div>
    <style>
        /* Incluye el CSS aquí o en un archivo separado */
        <?php include(plugin_dir_path(__FILE__) . 'styles.css'); ?>
        
    </style>
    <?php
}

// Enqueue scripts para la página de administración principal y las páginas de clicks, clientes y reportes
function sgc_enqueue_scripts($hook_suffix) {
    if ($hook_suffix == 'toplevel_page_sgc-plugin' || $hook_suffix == 'sgc-plugin_page_sgc-clicks' || $hook_suffix == 'sgc-plugin_page_sgc-customers' || $hook_suffix == 'sgc-plugin_page_sgc-reports' || $hook_suffix == 'sgc-create-banner') {
        wp_enqueue_script('chart-js', 'https://cdn.jsdelivr.net/npm/chart.js', array(), null, true);
        wp_enqueue_script('sgc-admin-js', plugins_url('admin.js', __FILE__), array('jquery', 'chart-js'), null, true);
    }
}
add_action('admin_enqueue_scripts', 'sgc_enqueue_scripts');

// Registrar el shortcode
function register_click_shortcode() {
    add_shortcode('banner_click', 'banner_click_function');
}
add_action('init', 'register_click_shortcode');

// Función para el shortcode de banner_click
function banner_click_function($atts) {
    $atts = shortcode_atts(
        array(
            'url' => '',
            'image' => '',
        ),
        $atts,
        'banner_click'
    );

    return '<a href="' . esc_url($atts['url']) . '" onclick="registerClick(event, \'' . esc_url($atts['url']) . '\')">
                <img src="' . esc_url($atts['image']) . '" alt="Banner">
            </a>';
}

// Enqueue el script JavaScript
function enqueue_click_script() {
    wp_enqueue_script('click-script', plugins_url('/js/click-script.js', __FILE__), array('jquery'), null, true);
    wp_localize_script('click-script', 'ajax_object', array('ajax_url' => admin_url('admin-ajax.php')));
}
add_action('wp_enqueue_scripts', 'enqueue_click_script');

// Manejar la solicitud AJAX
function handle_click_ajax() {
    global $wpdb;

    $table_name = $wpdb->prefix . 'clicks';

    $ip_address = $_SERVER['REMOTE_ADDR'];

    // Usar la API de geolocalización de ipinfo.io para obtener la ciudad
    $response = wp_remote_get("http://ipinfo.io/{$ip_address}/json");
    if (is_wp_error($response)) {
        $city = 'Unknown';
        error_log('Error fetching IP info: ' . $response->get_error_message());
    } else {
        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body, true);
        $city = isset($data['city']) ? $data['city'] : 'Unknown';
    }

    $device = $_SERVER['HTTP_USER_AGENT'];
    $agent = new Agent();
    $agent->setUserAgent($device);
    $browser = $agent->browser();

    $data = array(
        'ip_address' => $ip_address,
        'city' => $city,
        'device' => $device,
        'browser' => $browser,
        'click_time' => current_time('mysql'),
    );

    error_log('Received AJAX request');
    error_log(print_r($data, true));

    $result = $wpdb->insert($table_name, $data);

    if ($result === false) {
        error_log('Error inserting data: ' . $wpdb->last_error);
        wp_send_json_error(array('message' => 'Error inserting data: ' . $wpdb->last_error));
    } else {
        error_log('Data inserted successfully');
        wp_send_json_success(array('message' => 'Data inserted successfully'));
    }

    wp_die();
}
add_action('wp_ajax_nopriv_register_click', 'handle_click_ajax');
add_action('wp_ajax_register_click', 'handle_click_ajax');

// Crear la tabla en la base de datos al activar el plugin
function create_clicks_table() {
    global $wpdb;

    $table_name = $wpdb->prefix . 'clicks';
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        ip_address varchar(100) NOT NULL,
        city varchar(100),
        device text NOT NULL,
        browser varchar(100),
        click_time datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
        PRIMARY KEY (id)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}
register_activation_hook(__FILE__, 'create_clicks_table');

?>
