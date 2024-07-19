<?php
// Función para mostrar la página de customers
function sgc_customers_page()
{
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
                            <button id="addCustomersButton" class="add-customers-button">
                                Agregar cliente
                            </button>
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
                                    <th>Número telefónico</th>
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
    <div id="addCustomersModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Agregar nuevo cliente</h2>
            <form id="addCustomerForm">
                <label for="customerName">Nombre del Cliente:</label>
                <input type="text" id="customerName" name="customerName">

                <label for="bannerLocation">Localización del banner:</label>
                <input type="text" id="bannerLocation" name="bannerLocation">

                <button type="submit">Agregar</button>
            </form>
        </div>
    </div>
    <style>
        <?php include(plugin_dir_path(__FILE__) . 'customers.css'); ?>
    </style>
<?php
}

// Incluir los scripts necesarios para la página de clientes
function sgc_customers_enqueue_scripts($hook_suffix)
{
    if ($hook_suffix == 'toplevel_page_sgc-plugin' || $hook_suffix == 'sgc-plugin_page_sgc-customers') {
        wp_enqueue_script('chart-js', 'https://cdn.jsdelivr.net/npm/chart.js', array(), null, true);
        wp_enqueue_script('sgc-customers-js', plugins_url('customers.js', __FILE__), array('jquery', 'chart-js'), null, true);
    }
}
add_action('admin_enqueue_scripts', 'sgc_customers_enqueue_scripts');
?>