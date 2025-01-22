<div id="room-occupancy-chart"></div>

<script>
    const pathSegments = window.location.pathname.split('/');

    const branchName = pathSegments[2];

    let apiUrl = '/room-occupancy-data';

    if (branchName) {
        const branchMapping = {
            'bandung': 1,
            'yogyakarta': 2,
            'surabaya': 3,
            'padang': 4,
            'makassar': 5,
        };

        const branchId = branchMapping[branchName];

        if (branchId) {
            apiUrl = `/room-occupancy-data?branch_id=${branchId}`;
        }
    }

    console.log('Fetching data from:', apiUrl);

    fetch(apiUrl)
        .then(response => response.json())
        .then(data => {
            console.log('API Response:', data);

            if (!data || !data.data) {
                throw new Error('Invalid API response format.');
            }

            const occupancyData = data.data;

            const chartOptions = {
                chart: {
                    type: 'pie',
                    height: 350
                },
                labels: ['Kamar Terisi', 'Kamar Kosong'],
                series: [occupancyData.occupied, occupancyData.empty],
                tooltip: {
                    y: {
                        formatter: function(val) {
                            return val.toFixed(2) + '%';
                        }
                    }
                },
                legend: {
                    position: 'bottom',
                    horizontalAlign: 'center',
                    floating: false,
                    fontSize: '14px',
                    itemMargin: {
                        horizontal: 5,
                        vertical: 10
                    }
                },
                responsive: [{
                    breakpoint: 480,
                    options: {
                        chart: {
                            width: 300
                        },
                        legend: {
                            position: 'bottom'
                        }
                    }
                }]
            };

            let chart = new ApexCharts(document.querySelector("#room-occupancy-chart"), chartOptions);
            chart.render();
        })
        .catch(error => console.error('Error fetching data:', error));
</script>
