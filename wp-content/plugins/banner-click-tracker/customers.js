document.addEventListener('DOMContentLoaded', function() {
    var modalAdd = document.getElementById('modal');
    var addButton = document.getElementById('add-customer-btn');
    var closeAddButton = document.getElementsByClassName('close-btn')[0];

    addButton.onclick = function() {
        modalAdd.style.display = 'block';
    }

    closeAddButton.onclick = function() {
        modalAdd.style.display = 'none';
    }

    window.onclick = function(event) {
        if (event.target == modalAdd) {
            modalAdd.style.display = 'none';
        }
    }

    // Fetch chart data and render charts
    fetch(`${ajaxurl}?action=get_chart_data`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                renderActivityChart(data.data.activity_data);
                renderPackageChart(data.data.package_data);
            } else {
                console.error('Error fetching chart data:', data.data);
            }
        })
        .catch(error => console.error('Error fetching chart data:', error));
});

function renderActivityChart(activityData) {
    var ctx = document.getElementById('activityChart').getContext('2d');
    var labels = activityData.map(item => item.is_active ? 'Activo' : 'Inactivo');
    var counts = activityData.map(item => item.count);

    new Chart(ctx, {
        type: 'pie',
        data: {
            labels: labels,
            datasets: [{
                data: counts,
                backgroundColor: ['#36a2eb', '#ff6384']
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                }
            }
        }
    });
}

function renderPackageChart(packageData) {
    var ctx = document.getElementById('packageChart').getContext('2d');
    var labels = packageData.map(item => item.package_name);
    var counts = packageData.map(item => item.count);

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Número de Clientes',
                data: counts,
                backgroundColor: '#36a2eb'
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                }
            },
            scales: {
                x: {
                    beginAtZero: true
                }
            }
        }
    });
}
