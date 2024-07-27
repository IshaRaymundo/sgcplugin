<?php
// Función para mostrar la página de reportes
function sgc_reports_page() {
    ?>
    <div class="wrap">
        <div id="sgc-reports">
            <div class="container">
                <header class="header">
                    <h1>Reporte anual del banner: Universidad</h1>
                </header>
                <div class="buttons">
                    <button class="filter-btn">Filtrar</button>
                    <button class="excel-btn" id="export-excel-btn">Excel</button>
                    <button class="pdf-btn" id="export-pdf-btn">PDF</button>
                    <button class="whatsapp-btn">WhatsApp</button>
                    <button class="email-btn">Correo</button>
                    <button class="back-btn" onclick="window.location.href='admin.php?page=sgc-home'">Regresar</button>
                </div>
                <div class="charts">
                    <div class="chart">
                        <h2>Enero-Marzo</h2>
                        <canvas id="chart1"></canvas>
                    </div>
                    <div class="chart">
                        <h2>Abril-Junio</h2>
                        <canvas id="chart2"></canvas>
                    </div>
                    <div class="chart">
                        <h2>Julio-Septiembre</h2>
                        <canvas id="chart3"></canvas>
                    </div>
                    <div class="chart">
                        <h2>Octubre-Diciembre</h2>
                        <canvas id="chart4"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <style>
        <?php include(plugin_dir_path(__FILE__) . 'reports.css'); ?>
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('export-excel-btn').addEventListener('click', function() {
                window.location.href = ajaxurl + '?action=export_to_excel';
            });

            document.getElementById('export-pdf-btn').addEventListener('click', function() {
                window.location.href = ajaxurl + '?action=export_to_pdf';
            });
        });
    </script>
    <?php
}

// Incluir los scripts necesarios para la página de reportes
function sgc_reports_enqueue_scripts($hook_suffix) {
    if ($hook_suffix == 'sgc-plugin_page_sgc-reports') {
        wp_enqueue_script('chart-js', 'https://cdn.jsdelivr.net/npm/chart.js', array(), null, true);
        wp_enqueue_script('sgc-reports-js', plugins_url('reports.js', __FILE__), array('jquery', 'chart-js'), null, true);
    }
}
add_action('admin_enqueue_scripts', 'sgc_reports_enqueue_scripts');
