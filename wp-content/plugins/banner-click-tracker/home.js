jQuery(document).ready(function($) {
    const ctx = document.getElementById('homeChart').getContext('2d');
    console.log('Contexto del canvas:', ctx); // Verifica que el contexto se obtiene correctamente

    const chartConfig = {
        type: 'bar',
        data: {
            labels: ['Ban 1', 'Ban 2', 'Ban 3', 'Ban 4', 'Ban 5', 'Ban 6'],
            datasets: [{
                label: 'Clics',
                data: [120, 150, 300, 250, 200, 170],
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    };

    console.log('Configuración del gráfico:', chartConfig); // Verifica la configuración del gráfico

    new Chart(ctx, chartConfig);
});
