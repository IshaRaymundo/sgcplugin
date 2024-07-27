<?php
// Función para mostrar la página de reportes
function sgc_reports_page()
{
?>
    <div class="wrap">
        <div id="sgc-reports">
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
                <div class="buttons">
                    <select id="table-select">
                        <option value="" disabled selected>Seleccione una tabla</option>
                        <option value="clicks">Clics</option>
                        <option value="banners">Banners</option>
                        <option value="customers">Clientes</option>
                    </select>
                    <button class="excel-btn" id="export-excel-btn" disabled>Excel</button>
                    <button class="pdf-btn" id="export-pdf-btn" disabled>PDF</button>
                    <button class="back-btn" onclick="window.location.href='admin.php?page=sgc-home'">Regresar</button>
                </div>

                <style>
                    <?php include(plugin_dir_path(__FILE__) . 'reports.css'); ?>
                </style>
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        document.getElementById('table-select').addEventListener('change', function() {
                            var tableName = this.options[this.selectedIndex].text;
                            document.getElementById('report-title').textContent = 'Reporte de ' + tableName.replace('wp_', '');

                            // Habilitar los botones de exportación
                            document.getElementById('export-excel-btn').disabled = false;
                            document.getElementById('export-pdf-btn').disabled = false;
                        });

                        document.getElementById('export-excel-btn').addEventListener('click', function() {
                            var selectedTable = document.getElementById('table-select').value;
                            window.location.href = ajaxurl + '?action=export_to_excel&table=' + selectedTable;
                        });

                        document.getElementById('export-pdf-btn').addEventListener('click', function() {
                            var selectedTable = document.getElementById('table-select').value;
                            window.location.href = ajaxurl + '?action=export_to_pdf&table=' + selectedTable;
                        });
                    });
                </script>
            <?php
        }

        // Incluir los scripts necesarios para la página de reportes
        function sgc_reports_enqueue_scripts($hook_suffix)
        {
            if ($hook_suffix == 'sgc-plugin_page_sgc-reports') {
                wp_enqueue_script('chart-js', 'https://cdn.jsdelivr.net/npm/chart.js', array(), null, true);
                wp_enqueue_script('sgc-reports-js', plugins_url('reports.js', __FILE__), array('jquery', 'chart-js'), null, true);
            }
        }
        add_action('admin_enqueue_scripts', 'sgc_reports_enqueue_scripts');
            ?>