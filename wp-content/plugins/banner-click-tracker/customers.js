document.addEventListener('DOMContentLoaded', function() {
    // Fetch chart data and render charts
    fetch(`${ajaxurl}?action=get_chart_data`)
        .then(response => response.json())
        .then(data => {
            console.log('Chart data response:', data); // Log response
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

    var activityChart = new Chart(ctx, {
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

    var packageChart = new Chart(ctx, {
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
