<?php
/**
 * Plugin Name: Banner Click Tracker
 * Description: Rastrea las interacciones de los usuarios con los banners.
 * Version: 1.0
 * Author: UT
 */

// Incluir los archivos necesarios
include(plugin_dir_path(__FILE__) . 'clicks.php');
include(plugin_dir_path(__FILE__) . 'customers.php');
include(plugin_dir_path(__FILE__) . 'reports.php');

// Registrar el menú de administración
function sgc_register_menu_page() {
    add_menu_page(
        'SGC Plugin', 'SGC', 'manage_options', 'sgc-plugin', 'sgc_display_admin_page', 'dashicons-chart-line', 6
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
                        <img src="path/to/logo.png" alt="Logo">
                        <h1>Gestión de Banners</h1>
                    </div>
                    <nav class="nav-bar">
                        <ul>
                            <li><a href="admin.php?page=sgc-plugin">Inicio</a></li>
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
                                    <th>Usuario</th>
                                    <th>Correo Electrónico</th>
                                    <th>IP</th>
                                    <th>Banner</th>
                                    <th>Clics</th>
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
    <style>
        /* Incluye el CSS aquí o en un archivo separado */
        <?php include(plugin_dir_path(__FILE__) . 'styles.css'); ?>
    </style>
    <?php
}

// Incluir los scripts necesarios para la página de administración principal y las páginas de clicks, clientes y reportes
function sgc_enqueue_scripts($hook_suffix) {
    if ($hook_suffix == 'toplevel_page_sgc-plugin' || $hook_suffix == 'sgc-plugin_page_sgc-clicks' || $hook_suffix == 'sgc-plugin_page_sgc-customers' || $hook_suffix == 'sgc-plugin_page_sgc-reports') {
        wp_enqueue_script('chart-js', 'https://cdn.jsdelivr.net/npm/chart.js', array(), null, true);
        wp_enqueue_script('sgc-admin-js', plugins_url('admin.js', __FILE__), array('jquery', 'chart-js'), null, true);
    }
}
add_action('admin_enqueue_scripts', 'sgc_enqueue_scripts');
