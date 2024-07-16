<?php
// Función para mostrar la página de inicio
function sgc_home_page() {
    ?>
    <div class="wrap">
        <div id="sgc-home">
            <div class="container">
                <header class="header">
                    <div class="logo">
                        <img src="path/to/logo.png" alt="Logo">
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
                    <section class="welcome-section">
                        <h1>Bienvenido al Sistema de Gestión de Clics</h1>
                        <p>
                            Este sistema ha sido desarrollado para facilitar la gestión y el seguimiento de los clics en los banners publicitarios en la plataforma del Directorio Escolar. A través de este panel de control, los administradores pueden:
                        </p>
                        <ul>
                            <li> • Monitorear los clics en los banners en tiempo real.</li>
                            <li> • Visualizar estadísticas sobre el rendimiento de los banners.</li>
                            <li> • Visualizar los usuarios y sus interacciones en una gráfica.</li>
                            <li> • Generar y enviar informes detallados.</li>
                        </ul>
                        <p>
                            Utilice el menú lateral para navegar a las diferentes secciones del sistema y obtener información detallada.
                        </p>
                        <h2>¡Gracias por utilizar nuestro sistema!</h2>
                    </section>
                    <section class="chart-section">
                        <canvas id="homeChart"></canvas>
                    </section>
                </main>
            </div>
        </div>
    </div>
    <style>
        <?php include(plugin_dir_path(__FILE__) . 'home.css'); ?>
    </style>
    <?php
}

// Incluir los scripts necesarios para la página de inicio
function sgc_home_enqueue_scripts($hook_suffix) {
    if ($hook_suffix == 'toplevel_page_sgc-home') {
        wp_enqueue_script('chart-js', 'https://cdn.jsdelivr.net/npm/chart.js', array(), null, true);
        wp_enqueue_script('sgc-home-js', plugins_url('home.js', __FILE__), array('jquery', 'chart-js'), null, true);
    }
}
add_action('admin_enqueue_scripts', 'sgc_home_enqueue_scripts');
