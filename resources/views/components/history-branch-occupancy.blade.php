<div id="history-branch-occupancy" class="{{ $branchId ?? '' }}"></div>

<script>
    fetch("/api/occupancy/room-history?branch_id={{ $branchId ?? '' }}") // Gantilah URL API sesuai kebutuhan
        .then(response => response.json())
        .then(data => {
            // Opsi untuk chart
            let options = {
                series: data.series, // Menggunakan data series yang diterima dari API
                chart: {
                    type: 'bar', // Jenis chart yang digunakan
                    height: 350
                },
                plotOptions: {
                    bar: {
                        horizontal: false, // Chart vertikal
                        columnWidth: '55%',
                        borderRadius: 5,
                        borderRadiusApplication: 'end'
                    },
                },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    show: true,
                    width: 2,
                    colors: ['transparent']
                },
                xaxis: {
                    categories: data.categories, // Data kategori (tanggal) yang diterima dari API
                },
                yaxis: {
                    title: {
                        text: 'Jumlah Kamar Terisi' // Mengubah judul sesuai dengan occupancy
                    },
                    min: 0, // Menentukan nilai minimum pada sumbu Y
                    max: Math.max(...data.series.flatMap(s => s.data)) +
                        2, // Memberikan sedikit ruang di atas nilai tertinggi
                    tickAmount: 6,
                    labels: {
                        formatter: function(value) {
                            return value.toFixed(0); // Format angka tanpa koma
                        }
                    }
                },
                fill: {
                    opacity: 1
                },
                tooltip: {
                    y: {
                        formatter: function(val) {
                            return val + " kamar"; // Format tooltip untuk menampilkan jumlah kamar
                        }
                    }
                }
            };

            // Membuat dan menampilkan chart
            let chart = new ApexCharts(document.querySelector("#history-branch-occupancy"), options);
            chart.render();
        })
        .catch(error => console.error('Error:', error)); // Menangani error jika terjadi
</script>
