<?php

function sgc_get_clicks_data($date_start = null, $date_end = null, $device = null, $browser = null)
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'clicks';

    $query = "SELECT * FROM $table_name WHERE 1=1";

    if ($date_start) {
        $query .= $wpdb->prepare(" AND click_time >= %s", $date_start);
    }
    if ($date_end) {
        $query .= $wpdb->prepare(" AND click_time <= %s", $date_end . ' 23:59:59');
    }
    if ($device) {
        $query .= $wpdb->prepare(" AND device LIKE %s", '%' . $wpdb->esc_like($device) . '%');
    }
    if ($browser) {
        $query .= $wpdb->prepare(" AND browser LIKE %s", '%' . $wpdb->esc_like($browser) . '%');
    }

    $results = $wpdb->get_results($query);
    return $results;
}

function sgc_clicks_page()
{
    $date_start = isset($_GET['date-start']) ? sanitize_text_field($_GET['date-start']) : null;
    $date_end = isset($_GET['date-end']) ? sanitize_text_field($_GET['date-end']) : null;
    $device = isset($_GET['device']) ? sanitize_text_field($_GET['device']) : null;
    $browser = isset($_GET['browser']) ? sanitize_text_field($_GET['browser']) : null;

    $clicks_data = sgc_get_clicks_data($date_start, $date_end, $device, $browser);
?>
    <div class="wrap">
        <div id="sgc-dashboard">
            <div class="container">
                <header class="header">
                    <div class="logo">
                        <img src="<?php echo plugins_url('img/logo-sgc.png', __FILE__); ?>" alt="Logo">
                    </div>
                    <h1>Gestión de Clicks</h1>
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
                            <h2>Filtrar Búsqueda</h2>
                            <form id="filters" method="GET" action="">
                                <input type="hidden" name="page" value="sgc-clicks">
                                <label for="date-range">Seleccione un rango de fechas:</label>
                                <input type="date" id="date-start" name="date-start" value="<?php echo esc_attr($date_start); ?>">
                                <input type="date" id="date-end" name="date-end" value="<?php echo esc_attr($date_end); ?>">

                                <label for="device">Dispositivo:</label>
                                <input type="text" id="device" name="device" value="<?php echo esc_attr($device); ?>">

                                <label for="browser">Navegador:</label>
                                <input type="text" id="browser" name="browser" value="<?php echo esc_attr($browser); ?>">

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
                                    <th>#</th>
                                    <th>Dirección IP</th>
                                    <th>Dispositivo</th>
                                    <th>Ciudad</th>
                                    <th>Navegador</th>
                                    <th>Fecha de clic</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (!empty($clicks_data)) {
                                    foreach ($clicks_data as $index => $click) {
                                        $click_time = strtotime($click->click_time); // Convert click_time to timestamp
                                        $formatted_date = date('Y-m-d', $click_time); // Format date (YYYY-MM-DD)
                                        echo "<tr>";
                                        echo "<td>" . ($index + 1) . "</td>";
                                        echo "<td>" . esc_html($click->ip_address) . "</td>";
                                        echo "<td>" . esc_html($click->device) . "</td>";
                                        echo "<td>" . esc_html($click->city) . "</td>";
                                        echo "<td>" . esc_html($click->browser) . "</td>";
                                        echo "<td>" . esc_html($formatted_date) . "</td>"; // Display formatted date
                                        echo "</tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='6'>No hay datos disponibles.</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </section>
                </main>
            </div>
        </div>
    </div>
    <style>
        <?php include(plugin_dir_path(__FILE__) . 'clicks.css'); ?>
    </style>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns"></script>
    <script>
document.addEventListener('DOMContentLoaded', function() {
    var clicksData = <?php echo json_encode($clicks_data); ?>;

    var ctx = document.getElementById('clicksChart').getContext('2d');

    // Transformar los datos PHP a formato Chart.js
    var transformedData = clicksData.reduce((acc, click) => {
        var date = new Date(click.click_time).toISOString().split('T')[0]; // Usar solo la fecha (YYYY-MM-DD)
        if (!acc[date]) {
            acc[date] = 0;
        }
        acc[date]++;
        return acc;
    }, {});

    var labels = Object.keys(transformedData);
    var data = Object.values(transformedData);

    var clicksChart = new Chart(ctx, {
        type: 'line', 
        data: {
            labels: labels,
            datasets: [{
                label: 'Número de Clics',
                data: data,
                backgroundColor: 'rgba(106, 90, 205, 0.5)',
                borderColor: 'rgba(106, 90, 205, 1)',
                borderWidth: 1,
                pointRadius: 8 
            }]
        },
        options: {
            responsive: true,
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return 'Fecha: ' + context.label + ', Clics: ' + context.raw;
                        }
                    }
                }
            },
            scales: {
                x: {
                    title: {
                        display: true,
                        text: 'Fecha'
                    }
                },
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Número de Clics'
                    }
                }
            }
        }
    });
});
</script>


    <?php
}

function sgc_clicks_enqueue_scripts($hook_suffix)
{
    if ($hook_suffix == 'toplevel_page_sgc-plugin' || $hook_suffix == 'sgc-plugin_page_sgc-clicks') {
        wp_enqueue_script('chart-js', 'https://cdn.jsdelivr.net/npm/chart.js', array(), null, true);
    }
}
add_action('admin_enqueue_scripts', 'sgc_clicks_enqueue_scripts');
?>
