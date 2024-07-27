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
                                <canvas id="customersChart"></canvas>
                            </div>
                        </section>

                        <aside class="filter-container">
                            <button id="add-customer-btn">Agregar Cliente</button>
                            <h2>Filtrar Búsqueda</h2>
                            <form id="filters" onsubmit="return false;">
                                <label for="date-range">Seleccione un rango de fechas:</label>
                                <input type="date" id="date-start" name="date-start">
                                <input type="date" id="date-end" name="date-end">

                                <label for="company-name">Nombre de la empresa:</label>
                                <input type="text" id="company-name" name="company_name">

                                <label for="customer-name">Nombre del cliente:</label>
                                <input type="text" id="customer-name" name="customer_name">

                                <label for="is_active">¿Está activo?</label>
                                <select id="is_active" name="is_active">
                                    <option value="">Todos</option>
                                    <option value="1">Sí</option>
                                    <option value="0">No</option>
                                </select>

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
                                    <th>Tipo de paquete</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $customers = sgc_get_customers();
                                $counter = 1;
                                foreach ($customers as $customer) {
                                    echo "<tr>
                                        <td>{$counter}</td>
                                        <td>{$customer['company_name']}</td>
                                        <td>{$customer['customer_name']}</td>
                                        <td>{$customer['email']}</td>
                                        <td>{$customer['phone_number']}</td>
                                        <td>{$customer['address']}</td>
                                        <td>" . ($customer['is_active'] ? 'Sí' : 'No') . "</td>
                                        <td>{$customer['package_name']}</td>
                                        <td>
                                            <a href='" . admin_url('admin-post.php?action=delete_customer&id=' . $customer['id']) . "' class='btn btn-delete'>Eliminar</a>
                                            <a href='#' class='update-customer-btn' data-id='{$customer['id']}'>Editar</a>
                                        </td>
                                    </tr>";
                                    $counter++;
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

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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

            closeUpdateButtons.forEach(function(button) {
                button.onclick = function() {
                    modalUpdate.style.display = 'none';
                }
            });

            document.querySelectorAll('.update-customer-btn').forEach(function(button) {
                button.addEventListener('click', function(event) {
                    event.preventDefault();

                    var customerId = this.getAttribute('data-id');

                    fetch('<?php echo admin_url('admin-ajax.php?action=get_customer&customer_id='); ?>' + customerId)
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                var customer = data.data;
                                document.getElementById('update-customer-id').value = customer.id;
                                document.getElementById('update-customer-title').value = customer.customer_name;
                                document.getElementById('update-customer-company').value = customer.company_name;
                                document.getElementById('update-customer-email').value = customer.email;
                                document.getElementById('update-customer-telephone').value = customer.phone_number;
                                document.getElementById('update-customer-address').value = customer.address;
                                document.getElementById('update-customer-active').checked = customer.is_active;
                                document.getElementById('update-customer-subscription').value = customer.package_type_id;

                                modalUpdate.style.display = 'block';
                            } else {
                                alert('Error al cargar los datos del cliente');
                            }
                        });
                });
            });

            // Cerrar modal cuando se haga clic fuera del contenido
            window.onclick = function(event) {
                if (event.target == modalAdd) {
                    modalAdd.style.display = 'none';
                }
                if (event.target == modalUpdate) {
                    modalUpdate.style.display = 'none';
                }
            };

            // Handle form submission for filtering
            var filterForm = document.getElementById('filters');
            filterForm.addEventListener('submit', function(event) {
                event.preventDefault(); // Prevenir el comportamiento predeterminado del formulario

                var formData = new FormData(filterForm);
                var params = new URLSearchParams(formData).toString();

                var xhr = new XMLHttpRequest();
                xhr.open('GET', '<?php echo admin_url('admin-ajax.php?action=filter_customers&'); ?>' + params, true);
                xhr.onload = function() {
                    if (xhr.status === 200) {
                        var customers = JSON.parse(xhr.responseText);
                        updateCustomerTable(customers);
                    } else {
                        alert('Hubo un error al filtrar los clientes');
                    }
                };
                xhr.send();
            });

            // Function to update the customer table with new data
            function updateCustomerTable(customers) {
                var tbody = document.querySelector('#data-table tbody');
                tbody.innerHTML = ''; // Limpiar el contenido existente

                customers.forEach(function(customer) {
                    var row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${customer.id}</td>
                        <td>${customer.company_name}</td>
                        <td>${customer.customer_name}</td>
                        <td>${customer.email}</td>
                        <td>${customer.phone_number}</td>
                        <td>${customer.address}</td>
                        <td>${customer.is_active ? 'Sí' : 'No'}</td>
                        <td>${customer.package_name}</td>
                        <td>
                            <a href="<?php echo admin_url('admin-post.php?action=delete_customer&id='); ?>${customer.id}" class="btn btn-delete">Eliminar</a>
                            <a href="#" class="update-customer-btn" data-id="${customer.id}">Editar</a>
                        </td>
                    `;
                    tbody.appendChild(row);
                });
            }

            // Función para obtener los datos de los clientes
            function fetchCustomerData() {
                return fetch('<?php echo admin_url('admin-ajax.php?action=get_customers_data'); ?>')
                    .then(response => response.json());
            }

            // Crear el gráfico de Chart.js
            function createChart(data) {
                const ctx = document.getElementById('customersChart').getContext('2d');
                const packageNames = [...new Set(data.map(customer => customer.package_name))];
                const chartData = packageNames.map(package => {
                    return data.filter(customer => customer.package_name === package).length;
                });

                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: packageNames,
                        datasets: [{
                            label: 'Número de Clientes por Tipo de Paquete',
                            data: chartData,
                            backgroundColor: 'rgba(75, 192, 192, 0.2)',
                            borderColor: 'rgba(75, 192, 192, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    // Configura el formato de los ticks del eje Y
                                    callback: function(value) {
                                        // Muestra solo números enteros
                                        return Number.isInteger(value) ? value : '';
                                    }
                                }
                            }
                        }
                    }
                });
            }

            // Obtener los datos y crear el gráfico
            fetchCustomerData().then(data => {
                createChart(data);
            });
        });
    </script>

<?php
}

// Acción para obtener un cliente por ID
add_action('wp_ajax_get_customer', 'get_customer');
function get_customer()
{
    global $wpdb;

    $customer_id = intval($_GET['customer_id']);
    if ($customer_id <= 0) {
        wp_send_json_error('Invalid customer ID');
        wp_die();
    }

    // Consulta para obtener los datos del cliente
    $query = $wpdb->prepare(
        "SELECT c.*, p.package_name 
         FROM wp_customers c 
         LEFT JOIN wp_package_type p ON c.package_type_id = p.id 
         WHERE c.id = %d",
        $customer_id
    );
    $customer = $wpdb->get_row($query, ARRAY_A);

    if ($customer) {
        wp_send_json_success($customer);
    } else {
        wp_send_json_error('Customer not found');
    }

    wp_die();
}

// Acción para obtener clientes filtrados
add_action('wp_ajax_filter_customers', 'filter_customers');
function filter_customers()
{
    global $wpdb;

    $date_start = $_GET['date-start'];
    $date_end = $_GET['date-end'];
    $company_name = $_GET['company_name'];
    $customer_name = $_GET['customer_name'];
    $is_active = $_GET['is_active'];

    $query = "SELECT c.*, p.package_name FROM wp_customers c LEFT JOIN wp_package_type p ON c.package_type_id = p.id WHERE 1=1";

    if ($date_start && $date_end) {
        $query .= $wpdb->prepare(" AND c.created_at BETWEEN %s AND %s", $date_start, $date_end);
    }

    if ($company_name) {
        $query .= $wpdb->prepare(" AND c.company_name LIKE %s", '%' . $wpdb->esc_like($company_name) . '%');
    }

    if ($customer_name) {
        $query .= $wpdb->prepare(" AND c.customer_name LIKE %s", '%' . $wpdb->esc_like($customer_name) . '%');
    }

    if ($is_active !== '') {
        $query .= $wpdb->prepare(" AND c.is_active = %d", $is_active);
    }

    $customers = $wpdb->get_results($query, ARRAY_A);

    echo json_encode($customers);
    wp_die();
}

// Función para obtener los datos de los clientes en formato JSON
add_action('wp_ajax_get_customers_data', 'sgc_get_customers_data');
function sgc_get_customers_data() {
    global $wpdb;
    $results = $wpdb->get_results("SELECT company_name, customer_name, package_name FROM wp_customers JOIN wp_package_type ON wp_customers.package_type_id = wp_package_type.id", ARRAY_A);
    wp_send_json($results);
}

function sgc_customers_enqueue_scripts($hook_suffix) {
    if ($hook_suffix == 'toplevel_page_sgc-plugin' || $hook_suffix == 'sgc-plugin_page_sgc-customers') {
        wp_enqueue_script('chart-js', 'https://cdn.jsdelivr.net/npm/chart.js', array(), null, true);
        wp_enqueue_script('sgc-admin-js', plugins_url('admin.js', __FILE__), array('jquery', 'chart-js'), null, true);
        wp_localize_script('sgc-admin-js', 'ajaxurl', admin_url('admin-ajax.php'));
    }
}
add_action('admin_enqueue_scripts', 'sgc_customers_enqueue_scripts');
?>
