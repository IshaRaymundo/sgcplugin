<?php

function sgc_get_clicks_data() {
  global $wpdb;
  $table_name = $wpdb->prefix . 'clicks';
  $query = "SELECT * FROM $table_name";
  $results = $wpdb->get_results($query);
  return $results;
}

function sgc_clicks_page() {
  $clicks_data = sgc_get_clicks_data();
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
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var clicksData = <?php echo json_encode($clicks_data); ?>;

            var ctx = document.getElementById('clicksChart').getContext('2d');

            // Transformar los datos PHP a formato Chart.js
            var transformedData = clicksData.map(function(click, index) {
                return {
                    x: index + 1,
                    y: 1,
                    device: click.device,
                    browser: click.browser,
                    ip_address: click.ip_address
                };
            });

            var clicksChart = new Chart(ctx, {
                type: 'bubble',
                data: {
                    datasets: [{
                        label: 'Clics',
                        data: transformedData,
                        backgroundColor: 'rgba(106, 90, 205, 0.5)',
                        borderColor: 'rgba(106, 90, 205, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    var data = context.raw;
                                    return 'Dispositivo: ' + data.device + ', Navegador: ' + data.browser + ', IP: ' + data.ip_address;
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            type: 'linear',
                            position: 'bottom',
                            title: {
                                display: true,
                                text: 'ID'
                            }
                        },
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Clics'
                            }
                        }
                    }
                }
            });
        });
    </script>
    <?php
}

function sgc_clicks_enqueue_scripts($hook_suffix) {
    if ($hook_suffix == 'toplevel_page_sgc-plugin' || $hook_suffix == 'sgc-plugin_page_sgc-clicks') {
        wp_enqueue_script('chart-js', 'https://cdn.jsdelivr.net/npm/chart.js', array(), null, true);
    }
}
add_action('admin_enqueue_scripts', 'sgc_clicks_enqueue_scripts');
?>