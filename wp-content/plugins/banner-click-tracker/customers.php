<?php
// Función para mostrar la página de customers
function sgc_customers_page() {
    ?>
    <div class="wrap">
        <div id="sgc-dashboard">
            <div class="container">
                <header class="header">
                    <div class="logo">
                        <img src="path/to/logo.png" alt="Logo">
                        <h1>Gestión de Clientes</h1>
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
                            <h2>Seguimiento de clientes</h2>
                            <p>Obtén informes detallados sobre el seguimiento de clientes por medio de gráficos.</p>
                            <div id="chart">
                                <canvas id="customersChart"></canvas>
                            </div>
                        </section>
                        <aside class="filter-container">
                            <button id="add-customer-btn">Agregar Cliente</button>
                            <h2>Filtrar Búsqueda</h2>
                            <form id="filters">
                                <label for="date-range">Seleccione un rango de fechas:</label>
                                <input type="date" id="date-start" name="date-start">
                                <input type="date" id="date-end" name="date-end">
                                
                                <label for="banner-name">Nombre del banner:</label>
                                <input type="text" id="banner-name" name="banner-name">
                                
                                <label for="user-name">Nombre del cliente:</label>
                                <input type="text" id="user-name" name="user-name">
                                
                                <button type="submit">Filtrar</button>
                            </form>
                        </aside>
                    </div>
                    <section class="table-container">
                        <h2>Tabla de Clientes</h2>
                        <p>Obtén informes detallados sobre el seguimiento de clientes por medio de tablas.</p>
                        <table id="data-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nombre de la empresa</th>
                                    <th>Nombre del cliente</th>
                                    <th>Correo electrónico</th>
                                    <th>Dirección</th>
                                    <th>Activo</th>
                                    <th>Tipo de paquete</th>
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

    <!-- Modal -->
    <div id="modal" class="modal">
        <div class="modal-content">
            <span class="close-btn">&times;</span>
            <h2>Agregar Nuevo Cliente</h2>
            <form id="add-customer-form">
                <label for="customer-title">Nombre del cliente:</label>
                <input type="text" id="customer-title" name="customer-title">
                
                <label for="customer-company">Empresa:</label>
                <input type="text" id="customer-company" name="customer-company">

                <label for="customer-email">Correo electrónico:</label>
                <input type="email" id="customer-email" name="customer-email">
                
                <label for="customer-telephone">Teléfono:</label>
                <input type="text" id="customer-telephone" name="customer-telephone">

                <label for="customer-address">Teléfono:</label>
                <input type="text" id="customer-address" name="customer-address">

                <label for="customer-subscription">Plan del Cliente:</label>
                <select id="customer-subscription" name="customer-subscription">
                    <option value="Basico Plus">Básico Plus</option>
                    <option value="Lite">Lite</option>
                    <option value="Lite Plus">Lite Plus</option>
                    <option value="MKTGOLD">MKTGOLD</option>
                    <option value="MKTZOOM">MKTZOOM</option>
                </select>

                
                <button type="submit">Agregar Cliente</button>
            </form>
        </div>
    </div>

    <style>
        <?php include(plugin_dir_path(__FILE__) . 'customers.css'); ?>
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var modal = document.getElementById('modal');
            var addButton = document.getElementById('add-customer-btn');
            var closeButton = document.getElementsByClassName('close-btn')[0];

            addButton.onclick = function() {
                modal.style.display = 'block';
            }

            closeButton.onclick = function() {
                modal.style.display = 'none';
            }

            window.onclick = function(event) {
                if (event.target == modal) {
                    modal.style.display = 'none';
                }
            }
        });
    </script>
    <?php
}

// Incluir los scripts necesarios para la página de clientes
function sgc_customers_enqueue_scripts($hook_suffix) {
    if ($hook_suffix == 'toplevel_page_sgc-plugin' || $hook_suffix == 'sgc-plugin_page_sgc-customers') {
        wp_enqueue_script('chart-js', 'https://cdn.jsdelivr.net/npm/chart.js', array(), null, true);
        wp_enqueue_script('sgc-admin-js', plugins_url('admin.js', __FILE__), array('jquery', 'chart-js'), null, true);
    }
}
add_action('admin_enqueue_scripts', 'sgc_customers_enqueue_scripts');
?>
