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
include(plugin_dir_path(__FILE__) . 'customers-crud.php');
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
function sgc_register_menu_page()
{
    add_menu_page(
        'SGC Plugin',
        'SGC',
        'manage_options',
        'sgc-plugin',
        'sgc_display_admin_page',
        'dashicons-chart-line',
        6
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
function sgc_display_admin_page()
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'banners';
    $banners = $wpdb->get_results("SELECT * FROM $table_name", ARRAY_A);
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
                                    <th>Imagen</th>
                                    <th>URL</th>
                                    <th>Localización</th>
                                    <th>Total de clics</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($banners as $banner) { ?>
                                    <tr>
                                        <td><?php echo esc_html($banner['id']); ?></td>
                                        <td><?php echo esc_html($banner['banner_name']); ?></td>
                                        <td><img src="<?php echo esc_url($banner['banner_image']); ?>" alt="<?php echo esc_html($banner['banner_name']); ?>" style="max-width: 100px;"></td>
                                        <td><a href="<?php echo esc_url($banner['banner_url']); ?>" target="_blank"><?php echo esc_html($banner['banner_url']); ?></a></td>
                                        <td><?php echo esc_html($banner['location_on_site']); ?></td>
                                        <td><?php echo esc_html($banner['total_clicks']); ?></td>
                                    </tr>
                                <?php } ?>
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
            <form id="addBannerForm" method="post" action="<?php echo admin_url('admin-post.php'); ?>">
                <input type="hidden" name="action" value="add_banner">

                <label for="bannerName">Nombre del banner:</label>
                <input type="text" id="bannerName" name="bannerName">

                <label for="bannerImage">Imagen del banner (URL):</label>
                <input type="text" id="bannerImage" name="bannerImage">

                <label for="bannerUrl">URL del banner:</label>
                <input type="text" id="bannerUrl" name="bannerUrl">

                <label for="bannerLocation">Localización del banner:</label>
                <input type="text" id="bannerLocation" name="bannerLocation">
                <label for="bannerCustomer">Seleccionar Cliente:</label>
                <select id="bannerCustomer" name="bannerCustomer">
                    <?php
                    // Obtener clientes desde la base de datos
                    global $wpdb;
                    $customers = $wpdb->get_results("SELECT id, customer_name FROM {$wpdb->prefix}customers", ARRAY_A);
                    foreach ($customers as $customer) {
                        echo '<option value="' . esc_attr($customer['id']) . '">' . esc_html($customer['customer_name']) . '</option>';
                    }
                    ?>
                </select>

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
function sgc_enqueue_scripts($hook_suffix)
{
    if ($hook_suffix == 'toplevel_page_sgc-plugin' || $hook_suffix == 'sgc-plugin_page_sgc-clicks' || $hook_suffix == 'sgc-plugin_page_sgc-customers' || $hook_suffix == 'sgc-plugin_page_sgc-reports' || $hook_suffix == 'sgc-create-banner') {
        wp_enqueue_script('chart-js', 'https://cdn.jsdelivr.net/npm/chart.js', array(), null, true);
        wp_enqueue_script('sgc-admin-js', plugins_url('admin.js', __FILE__), array('jquery', 'chart-js'), null, true);
    }
}
add_action('admin_enqueue_scripts', 'sgc_enqueue_scripts');

// Registrar el shortcode
function register_click_shortcode()
{
    add_shortcode('banner_click', 'banner_click_function');
}
add_action('init', 'register_click_shortcode');

// Función para el shortcode de banner_click
function banner_click_function($atts)
{
    global $wpdb;

    $atts = shortcode_atts(
        array(
            'id' => '', // ID del banner
        ),
        $atts,
        'banner_click'
    );

    if (empty($atts['id'])) {
        return 'ID del banner no especificado.';
    }

    $banner_id = intval($atts['id']);
    $table_name = $wpdb->prefix . 'banners';

    // Obtener los datos del banner desde la base de datos
    $banner = $wpdb->get_row($wpdb->prepare("
        SELECT * FROM $table_name WHERE id = %d
    ", $banner_id), ARRAY_A);

    if (!$banner) {
        return 'Banner no encontrado.';
    }

    // Devolver el HTML del banner
    return '<a href="' . esc_url($banner['banner_url']) . '" onclick="registerClick(event, \'' . esc_url($banner['banner_url']) . '\')">
                <img src="' . esc_url($banner['banner_image']) . '" alt="' . esc_attr($banner['banner_name']) . '">
            </a>';
}
add_shortcode('banner_click', 'banner_click_function');


// Manejar la solicitud AJAX
function handle_click_ajax()
{
    global $wpdb;

    $table_name = $wpdb->prefix . 'clicks';

    $ip_address = $_SERVER['REMOTE_ADDR'];

    // Usar la API de geolocalización de ipinfo.io para obtener la ciudad
    $api_key = '7f5c5fb09a4e42';
    $response = wp_remote_get("http://ipinfo.io/{$ip_address}/json?token={$api_key}");

    if (is_wp_error($response)) {
        $city = 'Unknown';
        error_log('Error fetching IP info: ' . $response->get_error_message());
    } else {
        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body, true);
        $city = isset($data['city']) ? $data['city'] : 'Cancún';
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

// Crear las tablas en la base de datos al activar el plugin
function create_package_type_table()
{
    global $wpdb;

    $table_name = $wpdb->prefix . 'package_type';
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        package_name varchar(100) NOT NULL,
        package_description text NOT NULL,
        PRIMARY KEY (id)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);

    if ($wpdb->last_error) {
        error_log("Error creating package_type table: " . $wpdb->last_error);
    }
}
register_activation_hook(__FILE__, 'create_package_type_table');

// Crear la tabla wp_customers al activar el plugin
function sgc_create_customers_table() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'customers';
    
    $charset_collate = $wpdb->get_charset_collate();
    
    $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        company_name varchar(255) NOT NULL,
        customer_name varchar(255) NOT NULL,
        email varchar(100) NOT NULL,
        phone_number varchar(15) NOT NULL,
        address text NOT NULL,
        is_active boolean NOT NULL DEFAULT 0,
        package_type_id mediumint(9) NOT NULL,
        PRIMARY KEY (id)
    ) $charset_collate;";
    
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}

register_activation_hook(__FILE__, 'sgc_create_customers_table');

// Crear la tabla wp_banners al activar el plugin
function create_banners_table()
{
    global $wpdb;

    $table_name = $wpdb->prefix . 'banners';
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        banner_name varchar(255) NOT NULL,
        banner_image varchar(255) NOT NULL,
        banner_url varchar(255) NOT NULL,
        location_on_site varchar(255) NOT NULL,
        total_clicks mediumint(9) NOT NULL DEFAULT 0,
        PRIMARY KEY (id)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}
register_activation_hook(__FILE__, 'create_banners_table');

// Crear la tabla wp_customer_banners al activar el plugin
function create_customer_banners_table()
{
    global $wpdb;

    $table_name = $wpdb->prefix . 'customer_banners';
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        customer_id mediumint(9) NOT NULL,
        banner_id mediumint(9) NOT NULL,
        PRIMARY KEY (id),
        FOREIGN KEY (customer_id) REFERENCES {$wpdb->prefix}customers(id) ON DELETE CASCADE,
        FOREIGN KEY (banner_id) REFERENCES {$wpdb->prefix}banners(id) ON DELETE CASCADE
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}
register_activation_hook(__FILE__, 'create_customer_banners_table');


//Agregar cliente
function sgc_add_customer()
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'customers';

    $company_name = $_POST['company_name'];
    $customer_name = $_POST['customer_name'];
    $email = $_POST['email'];
    $phone_number = $_POST['phone_number'];
    $address = $_POST['address'];
    $is_active = isset($_POST['is_active']) ? 1 : 0;
    $package_type_id = $_POST['package_type_id'];

    $wpdb->insert($table_name, array(
        'company_name' => $company_name,
        'customer_name' => $customer_name,
        'email' => $email,
        'phone_number' => $phone_number,
        'address' => $address,
        'is_active' => $is_active,
        'package_type_id' => $package_type_id
    ));

    wp_redirect(admin_url('admin.php?page=sgc-customers'));
    exit;
}

add_action('admin_post_add_customer', 'sgc_add_customer');

//Leer Clientes
function sgc_get_customers()
{
    global $wpdb;
    $table_customers = $wpdb->prefix . 'customers';
    $table_package_type = $wpdb->prefix . 'package_type';

    $query = "
        SELECT 
            c.id, 
            c.company_name, 
            c.customer_name, 
            c.email, 
            c.phone_number, 
            c.address, 
            c.is_active, 
            c.package_type_id, 
            p.package_name AS package_name
        FROM 
            $table_customers c
        INNER JOIN 
            $table_package_type p
        ON 
            c.package_type_id = p.id
    ";

    $results = $wpdb->get_results($query, ARRAY_A);
    if ($wpdb->last_error) {
        error_log('Error in SQL Query: ' . $wpdb->last_error);
    }
    return $results;
}

//Leer Cliente
function sgc_get_customer() {
    global $wpdb;
    $customer_id = intval($_GET['customer_id']); // Asegúrate de sanitizar el ID
    
    if (!$customer_id) {
        wp_send_json_error('Invalid customer ID');
    }

    $table_name = $wpdb->prefix . 'customers';
    $customer = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $customer_id), ARRAY_A);
    
    if ($customer) {
        wp_send_json_success($customer);
    } else {
        wp_send_json_error('Customer not found');
    }
}
add_action('wp_ajax_get_customer', 'sgc_get_customer');



//Actualizar Cliente
function sgc_update_customer()
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'customers';

    $customer_id = intval($_POST['customer_id']);
    $customer_name = sanitize_text_field($_POST['customer_name']);
    $company_name = sanitize_text_field($_POST['company_name']);
    $email = sanitize_email($_POST['email']);
    $phone_number = sanitize_text_field($_POST['phone_number']);
    $address = sanitize_text_field($_POST['address']);
    $is_active = isset($_POST['is_active']) ? 1 : 0;
    $package_type_id = intval($_POST['package_type_id']);

    $wpdb->update(
        $table_name,
        array(
            'customer_name' => $customer_name,
            'company_name' => $company_name,
            'email' => $email,
            'phone_number' => $phone_number,
            'address' => $address,
            'is_active' => $is_active,
            'package_type_id' => $package_type_id
        ),
        array('id' => $customer_id),
        array('%s', '%s', '%s', '%s', '%s', '%d', '%d'),
        array('%d')
    );

    wp_redirect(admin_url('admin.php?page=sgc-customers'));
    exit;
}
add_action('admin_post_update_customer', 'sgc_update_customer');

//Eliminar Cliente

function sgc_delete_customer()
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'customers';

    $id = $_GET['id'];
    $wpdb->delete($table_name, array('id' => $id));

    wp_redirect(admin_url('admin.php?page=sgc-customers'));
    exit;
}

add_action('admin_post_delete_customer', 'sgc_delete_customer');

function sgc_get_chart_data()
{
    global $wpdb;

    // Obtener datos de actividad
    $activity_data = $wpdb->get_results("
        SELECT is_active, COUNT(*) as count
        FROM {$wpdb->prefix}customers
        GROUP BY is_active
    ", ARRAY_A);

    // Obtener datos de tipos de paquete
    $package_data = $wpdb->get_results("
        SELECT pt.package_name, COUNT(*) as count
        FROM {$wpdb->prefix}customers c
        INNER JOIN {$wpdb->prefix}package_type pt ON c.package_type_id = pt.id
        GROUP BY pt.package_name
    ", ARRAY_A);

    wp_send_json_success(array(
        'activity_data' => $activity_data,
        'package_data' => $package_data
    ));
}
add_action('wp_ajax_get_chart_data', 'sgc_get_chart_data');
add_action('wp_ajax_nopriv_get_chart_data', 'sgc_get_chart_data');

//Agregar Banners

function sgc_add_banner()
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'banners';

    $banner_name = sanitize_text_field($_POST['banner_name']);
    $banner_image = esc_url($_POST['banner_image']);
    $banner_url = esc_url($_POST['banner_url']);
    $location_on_site = sanitize_text_field($_POST['location_on_site']);

    $wpdb->insert($table_name, array(
        'banner_name' => $banner_name,
        'banner_image' => $banner_image,
        'banner_url' => $banner_url,
        'location_on_site' => $location_on_site
    ));

    wp_redirect(admin_url('admin.php?page=sgc-banners'));
    exit;
}
add_action('admin_post_add_banner', 'sgc_add_banner');

//Actualizar el total de clics

function update_banner_clicks($banner_id)
{
    global $wpdb;
    $clicks_table = $wpdb->prefix . 'clicks';
    $banners_table = $wpdb->prefix . 'banners';

    $total_clicks = $wpdb->get_var($wpdb->prepare("
        SELECT COUNT(*)
        FROM $clicks_table
        WHERE banner_id = %d
    ", $banner_id));

    $wpdb->update($banners_table, array(
        'total_clicks' => $total_clicks
    ), array('id' => $banner_id));
}

//Relación banner-cliente

function sgc_add_customer_banner_relation($customer_id, $banner_id)
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'customer_banners';

    $wpdb->insert($table_name, array(
        'customer_id' => $customer_id,
        'banner_id' => $banner_id
    ));
}

function sgc_banner_shortcode($atts)
{
    $atts = shortcode_atts(array(
        'id' => ''
    ), $atts, 'sgc_banner');

    if (empty($atts['id'])) {
        return '';
    }

    global $wpdb;
    $table_name = $wpdb->prefix . 'banners';

    $banner = $wpdb->get_row($wpdb->prepare("
        SELECT *
        FROM $table_name
        WHERE id = %d
    ", $atts['id']));

    if (!$banner) {
        return '';
    }

    return '<a href="' . esc_url($banner->banner_url) . '" target="_blank">
                <img src="' . esc_url($banner->banner_image) . '" alt="' . esc_attr($banner->banner_name) . '">
            </a>';
}
add_shortcode('sgc_banner', 'sgc_banner_shortcode');


?>