jQuery(document).ready(function($) {
    const ctx1 = document.getElementById('chart1').getContext('2d');
    const ctx2 = document.getElementById('chart2').getContext('2d');
    const ctx3 = document.getElementById('chart3').getContext('2d');
    const ctx4 = document.getElementById('chart4').getContext('2d');

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

    new Chart(ctx1, chartConfig);
    new Chart(ctx2, chartConfig);
    new Chart(ctx3, chartConfig);
    new Chart(ctx4, chartConfig);
});
