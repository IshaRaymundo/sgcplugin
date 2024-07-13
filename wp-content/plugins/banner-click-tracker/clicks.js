jQuery(document).ready(function($) {
    // Datos simulados para la gráfica de puntos
    var clickData = [
        { id: 1, usuario: 'Usuario 1', correo: 'admin1@test.com', ip: '255.255.255.0', banner: 'Banner 1', clics: 23 },
        { id: 2, usuario: 'Usuario 2', correo: 'admin2@test.com', ip: '255.255.255.1', banner: 'Banner 2', clics: 43 },
        { id: 3, usuario: 'Usuario 3', correo: 'admin3@test.com', ip: '197.145.123', banner: 'Banner 3', clics: 54 },
        { id: 4, usuario: 'Usuario 4', correo: 'admin4@test.com', ip: '192.167.0.8', banner: 'Banner 4', clics: 22 },
        { id: 5, usuario: 'Usuario 5', correo: 'admin5@test.com', ip: '192.156.10.0', banner: 'Banner 5', clics: 12 }
    ];

    // Inicializar la gráfica de puntos con los datos
    var ctx = document.getElementById('clicksChart').getContext('2d');
    var clicksChart = new Chart(ctx, {
        type: 'scatter',
        data: {
            datasets: [{
                label: 'Clics',
                data: clickData.map(function(row) { return { x: row.id, y: row.clics }; }),
                backgroundColor: 'rgba(106, 90, 205, 0.5)',
                borderColor: 'rgba(106, 90, 205, 1)',
                borderWidth: 1
            }]
        },
        options: {
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
