<?php
/**
 * Plugin Name: Banner Click Tracker
 * Description: Rastrea las interacciones de los usuarios con los banners.
 * Version: 1.0
 * Author: UT
 */

// Registrar el menú de administración
function sgc_register_menu_page() {
    add_menu_page(
        'SGC Plugin', 'SGC', 'manage_options', 'sgc-plugin', 'sgc_display_admin_page', 'dashicons-chart-line', 6
    );
}
add_action('admin_menu', 'sgc_register_menu_page');

// Mostrar la página de administración
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
                            <li><a href="#inicio">Inicio</a></li>
                            <li><a href="#banners">Banners</a></li>
                            <li><a href="#clicks">Clicks</a></li>
                            <li><a href="#usuarios">Usuarios</a></li>
                            <li><a href="#reportes">Reportes</a></li>
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

// Incluir Chart.js y el script de administración en la página de administración
function sgc_enqueue_scripts() {
    wp_enqueue_script('chart-js', 'https://cdn.jsdelivr.net/npm/chart.js', array(), null, true);
    wp_enqueue_script('sgc-admin-js', plugins_url('admin.js', __FILE__), array('jquery', 'chart-js'), null, true);
}
add_action('admin_enqueue_scripts', 'sgc_enqueue_scripts');

// Script para generar la gráfica con Chart.js y manejar el filtrado
function sgc_admin_js() {
    ?>
    <script>
    jQuery(document).ready(function($) {
        // Datos simulados para la gráfica y la tabla
        var clickData = [
            { id: 1, usuario: 'Usuario 1', correo: 'admin1@test.com', ip: '255.255.255.0', banner: 'Banner 1', clics: 23 },
            { id: 2, usuario: 'Usuario 2', correo: 'admin2@test.com', ip: '255.255.255.1', banner: 'Banner 2', clics: 43 },
            { id: 3, usuario: 'Usuario 3', correo: 'admin3@test.com', ip: '197.145.123', banner: 'Banner 3', clics: 54 },
            { id: 4, usuario: 'Usuario 4', correo: 'admin4@test.com', ip: '192.167.0.8', banner: 'Banner 4', clics: 22 },
            { id: 5, usuario: 'Usuario 5', correo: 'admin5@test.com', ip: '192.156.10.0', banner: 'Banner 5', clics: 12 }
        ];

        // Inicializar la tabla con los datos
        var tableBody = $('#data-table tbody');
        clickData.forEach(function(row) {
            tableBody.append('<tr><td>' + row.id + '</td><td>' + row.usuario + '</td><td>' + row.correo + '</td><td>' + row.ip + '</td><td>' + row.banner + '</td><td>' + row.clics + '</td></tr>');
        });

        // Inicializar la gráfica con los datos
        var ctx = document.getElementById('clicksChart').getContext('2d');
        var clicksChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: clickData.map(function(row) { return row.banner; }),
                datasets: [{
                    label: 'Clics',
                    data: clickData.map(function(row) { return row.clics; }),
                    backgroundColor: 'rgba(106, 90, 205, 0.5)',
                    borderColor: 'rgba(106, 90, 205, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Manejar el filtrado de datos
        $('#filters').submit(function(event) {
            event.preventDefault();
            var dateStart = $('#date-start').val();
            var dateEnd = $('#date-end').val();
            var bannerName = $('#banner-name').val();
            var userName = $('#user-name').val();

            // Filtrar los datos según los criterios de búsqueda (simulación)
            var filteredData = clickData.filter(function(row) {
                return (!bannerName || row.banner.includes(bannerName)) &&
                       (!userName || row.usuario.includes(userName));
            });

            // Actualizar la tabla con los datos filtrados
            tableBody.empty();
            filteredData.forEach(function(row) {
                tableBody.append('<tr><td>' + row.id + '</td><td>' + row.usuario + '</td><td>' + row.correo + '</td><td>' + row.ip + '</td><td>' + row.banner + '</td><td>' + row.clics + '</td></tr>');
            });

            // Actualizar la gráfica con los datos filtrados
            clicksChart.data.labels = filteredData.map(function(row) { return row.banner; });
            clicksChart.data.datasets[0].data = filteredData.map(function(row) { return row.clics; });
            clicksChart.update();
        });
    });
    </script>
    <?php
}
add_action('admin_footer', 'sgc_admin_js');
?>
