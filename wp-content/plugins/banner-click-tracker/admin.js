jQuery(document).ready(function ($) {
    // Datos simulados para la gráfica y la tabla



    // Inicializar la gráfica con los datos (grafica real en clicks.php)
    // var ctx = document.getElementById('').getContext('2d');
    // var clicksChart = new Chart(ctx, {
    //     type: 'bar',
    //     data: {
    //         labels: clickData.map(function (row) { return row.banner; }),
    //         datasets: [{
    //             label: 'Clics',
    //             data: clickData.map(function (row) { return row.clics; }),
    //             backgroundColor: 'rgba(106, 90, 205, 0.5)',
    //             borderColor: 'rgba(106, 90, 205, 1)',
    //             borderWidth: 1
    //         }]
    //     },
    //     options: {
    //         scales: {
    //             y: {
    //                 beginAtZero: true
    //             }
    //         }
    //     }
    // });

    // Manejar el filtrado de datos
    $('#filters').submit(function (event) {
        event.preventDefault();
        var dateStart = $('#date-start').val();
        var dateEnd = $('#date-end').val();
        var bannerName = $('#banner-name').val();
        var userName = $('#user-name').val();

        // Filtrar los datos según los criterios de búsqueda (simulación)
        var filteredData = clickData.filter(function (row) {
            return (!bannerName || row.banner.includes(bannerName)) &&
                (!userName || row.usuario.includes(userName));
        });

        // Actualizar la tabla con los datos filtrados
        tableBody.empty();
        filteredData.forEach(function (row) {
            tableBody.append('<tr><td>' + row.id + '</td><td>' + row.usuario + '</td><td>' + row.correo + '</td><td>' + row.ip + '</td><td>' + row.banner + '</td><td>' + row.clics + '</td></tr>');
        });

        // Actualizar la gráfica con los datos filtrados
        clicksChart.data.labels = filteredData.map(function (row) { return row.banner; });
        clicksChart.data.datasets[0].data = filteredData.map(function (row) { return row.clics; });
        clicksChart.update();
    });
});
jQuery(document).ready(function ($) {
    // Manejar el modal para agregar banners
    var addModal = document.getElementById("addBannerModal");
    var addBtn = document.getElementById("addBannerButton");
    var addCloseBtn = addModal.querySelector(".close");

    addBtn.onclick = function () {
        addModal.style.display = "block";
    }

    addCloseBtn.onclick = function () {
        addModal.style.display = "none";
    }

    window.onclick = function (event) {
        if (event.target == addModal) {
            addModal.style.display = "none";
        }
    }

    // Manejar el modal para editar banners
    var editModal = document.getElementById("editBannerModal");
    var editCloseBtn = editModal.querySelector(".close");

    editCloseBtn.onclick = function () {
        editModal.style.display = "none";
    }

    window.onclick = function (event) {
        if (event.target == editModal) {
            editModal.style.display = "none";
        }
    }
});

function openEditBannerModal(bannerId) {
    console.log("ID del banner a editar:", bannerId);
    // Realizar una petición AJAX para obtener los datos del banner
    jQuery.ajax({
        url: ajaxurl,
        type: 'POST',
        data: {
            action: 'get_banner_details',
            banner_id: bannerId
        },
        success: function (response) {
            console.log("Respuesta AJAX:", response);
            if (response.success) {
                var banner = response.data;
                jQuery('#editBannerForm input[name="banner_id"]').val(banner.id);
                jQuery('#editBannerForm input[name="banner_name"]').val(banner.banner_name);
                jQuery('#editBannerForm input[name="banner_image"]').val(banner.banner_image);
                jQuery('#editBannerForm input[name="banner_url"]').val(banner.banner_url);
                jQuery('#editBannerForm input[name="location_on_site"]').val(banner.location_on_site);
                jQuery('#editBannerForm select[name="page_id"]').val(banner.page_id);
                jQuery('#editBannerForm select[name="bannerCustomer"]').val(banner.customer_id);

                document.getElementById("editBannerModal").style.display = "block";
            } else {
                alert('Error al obtener los datos del banner: ' + response.data);
            }
        },
        error: function (xhr, status, error) {
            console.log("Error AJAX:", error);
            alert('Error en la petición AJAX: ' + error);
        }
    });
}



document.addEventListener('DOMContentLoaded', function () {

    var addCustomerForm = document.getElementById('add-customer-form');
    addCustomerForm.addEventListener('submit', function (e) {
        e.preventDefault();

        var formData = new FormData(addCustomerForm);
        formData.append('action', 'create_customer');

        fetch(ajaxurl, {
            method: 'POST',
            body: formData
        }).then(response => response.json()).then(data => {
            if (data.success) {
                alert('Cliente agregado exitosamente.');
                modal.style.display = 'none';
                // Recargar tabla de clientes...
            } else {
                alert('Error al agregar cliente.');
            }
        });
    });

    // Leer clientes y llenar la tabla
    function loadCustomers() {
        var formData = new FormData();
        formData.append('action', 'read_customers');

        fetch(ajaxurl, {
            method: 'POST',
            body: formData
        }).then(response => response.json()).then(data => {
            if (data.success) {
                var customers = data.data;
                var tbody = document.querySelector('#data-table tbody');
                tbody.innerHTML = '';

                customers.forEach(customer => {
                    var row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${customer.id}</td>
                        <td>${customer.company_name}</td>
                        <td>${customer.customer_name}</td>
                        <td>${customer.email}</td>
                        <td>${customer.phone_number}</td>
                        <td>${customer.address}</td>
                        <td>${customer.is_active ? 'Sí' : 'No'}</td>
                        <td>${customer.package_type_id}</td>
                        <td>
                            <button data-id="${customer.id}" class="edit-btn">Editar</button>
                            <button data-id="${customer.id}" class="delete-btn">Eliminar</button>
                        </td>
                    `;
                    tbody.appendChild(row);
                });

                // Agregar eventos de edición y eliminación
                document.querySelectorAll('.edit-btn').forEach(button => {
                    button.addEventListener('click', function () {
                        var id = this.getAttribute('data-id');
                        // Mostrar modal de edición y cargar datos del cliente...
                    });
                });

                document.querySelectorAll('.delete-btn').forEach(button => {
                    button.addEventListener('click', function () {
                        var id = this.getAttribute('data-id');
                        if (confirm('¿Está seguro de que desea eliminar este cliente?')) {
                            var formData = new FormData();
                            formData.append('action', 'delete_customer');
                            formData.append('id', id);

                            fetch(ajaxurl, {
                                method: 'POST',
                                body: formData
                            }).then(response => response.json()).then(data => {
                                if (data.success) {
                                    alert('Cliente eliminado exitosamente.');
                                    loadCustomers();
                                } else {
                                    alert('Error al eliminar cliente.');
                                }
                            });
                        }
                    });
                });
            } else {
                alert('Error al cargar clientes.');
            }
        });
    }

    loadCustomers();
});


