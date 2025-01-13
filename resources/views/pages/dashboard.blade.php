@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Dashboard'])
    <div class="container-fluid">
        <div class="row mt-7">
            <div class="col-lg-19 mb-lg-0 mb-4">
                <div class="card z-index-2 h-100">
                    <div class="card-header pb-0 pt-3 bg-transparent">
                        <h1 class="my-0">Sistem Prediksi Harga Bahan Pangan Jawa Timur</h1>
                    </div>
                    <div class="card-body p-3">
                        <br><br>

                        <!-- Filter Tahun -->
                        <div class="mb-4">
                            <label for="filter-tahun" class="form-label">Filter Berdasarkan Tahun:</label>
                            <select id="filter-tahun" class="form-select" style="width: 200px;">
                                <!-- Tahun akan diisi secara dinamis -->
                            </select>
                        </div>

                        <!-- Chart Canvas -->
                        <div class="chart-container">
                            <canvas id="chart" width="400" height="200"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @include('layouts.footers.auth.footer')
    </div>
@endsection
@push('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const tahunSelect = document.getElementById('filter-tahun');
        const chartCanvas = document.getElementById('chart').getContext('2d');
        let chartInstance;

        // Fetch years dynamically
        fetch('/get-available-years')
            .then(response => response.json())
            .then(years => {
                years.forEach(year => {
                    const option = document.createElement('option');
                    option.value = year;
                    option.textContent = year;
                    tahunSelect.appendChild(option);
                });

                // Load initial chart data (first year or current year)
                if (years.length > 0) {
                    tahunSelect.value = years[0];
                    loadChartData(years[0]);
                }
            });

        // Load chart data based on selected year
        function loadChartData(year) {
            fetch(`/get-chart-data?year=${year}`)
                .then(response => response.json())
                .then(data => {
                    const labels = data.map(item => item.nama_kategori);
                    const averages = data.map(item => item.rata_rata);

                    if (chartInstance) {
                        chartInstance.destroy(); // Destroy previous chart instance
                    }

                    chartInstance = new Chart(chartCanvas, {
                        type: 'bar',
                        data: {
                            labels: labels,
                            datasets: [{
                                label: `Rata-rata Harga Tahun ${year}`,
                                data: averages,
                                backgroundColor: 'rgba(75, 192, 192, 0.5)',
                                borderColor: 'rgba(75, 192, 192, 1)',
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                legend: {
                                    position: 'top',
                                },
                            },
                            scales: {
                                y: {
                                    beginAtZero: true
                                }
                            }
                        }
                    });
                });
        }

        // Event listener for year selection
        tahunSelect.addEventListener('change', function() {
            const selectedYear = this.value;
            loadChartData(selectedYear);
        });
    });
</script>
@endpush

