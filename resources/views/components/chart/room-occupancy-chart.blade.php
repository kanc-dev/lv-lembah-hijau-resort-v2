<div id="room-occupancy-chart"></div>


<script>
    // Ambil data dari API untuk occupancy
    fetch('/room-occupancy-data') // Periksa endpoint API
        .then(response => response.json())
        .then(data => {
            console.log('API Response:', data); // Debugging respons API

            // Pastikan data memiliki struktur yang diharapkan
            if (!data || !data.data) {
                throw new Error('Invalid API response format.');
            }

            const occupancyData = data.data;

            // Persiapkan data untuk chart
            const chartOptions = {
                chart: {
                    type: 'pie',
                    height: 350
                },
                labels: ['Kamar Terisi', 'Kamar Kosong'],
                series: [occupancyData.occupied, occupancyData.empty],
                // colors: ['#4caf50', '#f44336'], // Warna hijau untuk terisi, merah untuk kosong
                tooltip: {
                    y: {
                        formatter: function(val) {
                            return val.toFixed(2) + '%'; // Format dengan 2 desimal
                        }
                    }
                },
                tooltip: {
                    y: {
                        formatter: function(val) {
                            return val + "%";
                        }
                    }
                },
                legend: {
                    position: 'bottom', // Memindahkan legend ke bawah chart
                    horizontalAlign: 'center', // Menempatkan legend di tengah
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

            // Membuat dan menampilkan chart
            let chart = new ApexCharts(document.querySelector("#room-occupancy-chart"), chartOptions);
            chart.render();
        })
        .catch(error => console.error('Error fetching data:', error)); // Menangani error jika terjadi
</script>
