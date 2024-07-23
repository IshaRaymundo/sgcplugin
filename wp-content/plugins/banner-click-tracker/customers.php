<?php
global $wpdb;

// Consulta para obtener los datos de la tabla package_type
$package_types = $wpdb->get_results("SELECT id, package_name FROM wp_package_type");

// Función para mostrar la página de customers
function sgc_customers_page()
{
    global $package_types; // Traemos la variable global al contexto de la función
?>

    <div class="wrap">
        <div id="sgc-dashboard">
            <div class="container">
                <header class="header">
                    <div class="logo">
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
                                <canvas id="activityChart"></canvas>
                                <canvas id="packageChart"></canvas>
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

                                <label for="user-name">¿Está ctivo?:</label>
                                <input type="text" id="user-name" name="user-name">

                                <button type="submit">Filtrar</button>
                            </form>
                        </aside>
                    </div>
                    <section class="table-container">
                        <h2>Tabla de Clientes</h2>
                        <table id="data-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nombre de la empresa</th>
                                    <th>Nombre del cliente</th>
                                    <th>Correo electrónico</th>
                                    <th>Teléfono</th>
                                    <th>Dirección</th>
                                    <th>Activo</th>
                                    <th>Tipo de paquete</th> <!-- Asegúrate de que esta columna esté en el HTML -->
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $customers = sgc_get_customers();
                                foreach ($customers as $customer) {
                                    echo "<tr>
                    <td>{$customer['id']}</td>
                    <td>{$customer['company_name']}</td>
                    <td>{$customer['customer_name']}</td>
                    <td>{$customer['email']}</td>
                    <td>{$customer['phone_number']}</td>
                    <td>{$customer['address']}</td>
                    <td>" . ($customer['is_active'] ? 'Sí' : 'No') . "</td>
                    <td>{$customer['package_name']}</td> <!-- Muestra el nombre del paquete aquí -->
                    <td>
                        <a href='" . admin_url('admin-post.php?action=delete_customer&id=' . $customer['id']) . "'>Eliminar</a>
                        <a href='#' class='update-customer-btn' data-id='{$customer['id']}'>Editar</a>
                    </td>
                </tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </section>


                </main>
            </div>
        </div>
    </div>

    <!-- Modal_add -->
    <div id="modal-add" class="modal">
        <div class="modal-content">
            <span class="close-btn" data-modal="modal-add">&times;</span>
            <h2>Agregar Nuevo Cliente</h2>
            <form id="add-customer-form" method="POST" action="<?php echo admin_url('admin-post.php'); ?>">
                <input type="hidden" name="action" value="add_customer">
                <label for="customer-title">Nombre del cliente:</label>
                <input type="text" id="customer-title" name="customer_name" required>

                <label for="customer-company">Empresa:</label>
                <input type="text" id="customer-company" name="company_name" required>

                <label for="customer-email">Correo electrónico:</label>
                <input type="email" id="customer-email" name="email" required>

                <label for="customer-telephone">Teléfono:</label>
                <input type="text" id="customer-telephone" name="phone_number" required>

                <label for="customer-address">Dirección:</label>
                <input type="text" id="customer-address" name="address" required>

                <label for="customer-active">Activo:</label>
                <input type="checkbox" id="customer-active" name="is_active">

                <label for="customer-subscription">Plan del Cliente:</label>
                <select id="customer-subscription" name="package_type_id">
                    <?php foreach ($package_types as $package) : ?>
                        <option value="<?php echo esc_attr($package->id); ?>"><?php echo esc_html($package->package_name); ?></option>
                    <?php endforeach; ?>
                </select>

                <button type="submit">Agregar Cliente</button>
            </form>
        </div>
    </div>

    <!-- Modal_update -->
    <div id="modal-update" class="modal">
        <div class="modal-content">
            <span class="close-btn" data-modal="modal-update">&times;</span>
            <h2>Actualizar Cliente</h2>
            <form id="update-customer-form" method="POST" action="<?php echo admin_url('admin-post.php'); ?>">
                <input type="hidden" name="action" value="update_customer">
                <input type="hidden" id="update-customer-id" name="customer_id">
                <label for="update-customer-title">Nombre del cliente:</label>
                <input type="text" id="update-customer-title" name="customer_name" required>

                <label for="update-customer-company">Empresa:</label>
                <input type="text" id="update-customer-company" name="company_name" required>

                <label for="update-customer-email">Correo electrónico:</label>
                <input type="email" id="update-customer-email" name="email" required>

                <label for="update-customer-telephone">Teléfono:</label>
                <input type="text" id="update-customer-telephone" name="phone_number" required>

                <label for="update-customer-address">Dirección:</label>
                <input type="text" id="update-customer-address" name="address" required>

                <label for="update-customer-active">Activo:</label>
                <input type="checkbox" id="update-customer-active" name="is_active">

                <label for="update-customer-subscription">Plan del Cliente:</label>
                <select id="update-customer-subscription" name="package_type_id">
                    <?php foreach ($package_types as $package) : ?>
                        <option value="<?php echo esc_attr($package->id); ?>"><?php echo esc_html($package->package_name); ?></option>
                    <?php endforeach; ?>
                </select>

                <button type="submit">Actualizar Cliente</button>
            </form>
        </div>
    </div>


    <style>
        <?php include(plugin_dir_path(__FILE__) . 'customers.css'); ?>
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Modal Add
            var modalAdd = document.getElementById('modal-add');
            var addButton = document.getElementById('add-customer-btn');
            var closeButtons = document.querySelectorAll('.close-btn[data-modal="modal-add"]');

            addButton.onclick = function() {
                modalAdd.style.display = 'block';
            }

            closeButtons.forEach(function(button) {
                button.onclick = function() {
                    modalAdd.style.display = 'none';
                }
            });

            // Modal Update
            var modalUpdate = document.getElementById('modal-update');
            var closeUpdateButtons = document.querySelectorAll('.close-btn[data-modal="modal-update"]');

            // Event delegation for update buttons
            document.querySelector('#data-table').addEventListener('click', function(event) {
                if (event.target.classList.contains('update-customer-btn')) {
                    var customerId = event.target.getAttribute('data-id');

                    // Fetch customer data via AJAX
                    fetch(`${ajaxurl}?action=get_customer&customer_id=${customerId}`)
                        .then(response => response.json())
                        .then(data => {
                            // Populate fields
                            document.getElementById('update-customer-id').value = data.id;
                            document.getElementById('update-customer-title').value = data.customer_name;
                            document.getElementById('update-customer-company').value = data.company_name;
                            document.getElementById('update-customer-email').value = data.email;
                            document.getElementById('update-customer-telephone').value = data.phone_number;
                            document.getElementById('update-customer-address').value = data.address;
                            document.getElementById('update-customer-active').checked = data.is_active;

                            // Set selected package type
                            var packageSelect = document.getElementById('update-customer-subscription');
                            for (var i = 0; i < packageSelect.options.length; i++) {
                                if (packageSelect.options[i].value == data.package_type_id) {
                                    packageSelect.options[i].selected = true;
                                    break;
                                }
                            }

                            modalUpdate.style.display = 'block';
                        })
                        .catch(error => console.error('Error fetching customer data:', error));
                }
            });

            closeUpdateButtons.forEach(function(button) {
                button.onclick = function() {
                    modalUpdate.style.display = 'none';
                }
            });

            window.onclick = function(event) {
                if (event.target == modalAdd) {
                    modalAdd.style.display = 'none';
                }
                if (event.target == modalUpdate) {
                    modalUpdate.style.display = 'none';
                }
            }
        });
    </script>
<?php
}

// Incluir los scripts necesarios para la página de clientes
function sgc_customers_enqueue_scripts($hook_suffix)
{
    if ($hook_suffix == 'toplevel_page_sgc-plugin' || $hook_suffix == 'sgc-plugin_page_sgc-customers') {
        wp_enqueue_script('chart-js', 'https://cdn.jsdelivr.net/npm/chart.js', array(), null, true);
        wp_enqueue_script('sgc-admin-js', plugins_url('admin.js', __FILE__), array('jquery', 'chart-js'), null, true);
        wp_enqueue_script('sgc-customers-js', plugins_url('admin/customers.js', __FILE__), array('jquery', 'chart-js'), null, true);

        wp_localize_script('sgc-customers-js', 'ajaxurl', admin_url('admin-ajax.php'));
    }
}
add_action('admin_enqueue_scripts', 'sgc_customers_enqueue_scripts');


?>