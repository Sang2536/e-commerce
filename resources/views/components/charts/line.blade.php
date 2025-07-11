@props(['id' => 'line-chart', 'data'])

<canvas id="{{ $id }}" class="chart-canvas" height="300"></canvas>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const ctx = document.getElementById('{{ $id }}').getContext('2d');
            const data = @json($data);
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: data.labels,
                    datasets: [{
                        label: 'Doanh thu (VNĐ)',
                        data: data.data,
                        borderColor: '#5e72e4',
                        tension: 0.4,
                        borderWidth: 3,
                        pointRadius: 3,
                        backgroundColor: 'rgba(94, 114, 228, 0.1)',
                        fill: true,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        y: {
                            ticks: {
                                callback: function(value) {
                                    return value.toLocaleString('vi-VN') + 'đ';
                                }
                            }
                        }
                    }
                }
            });
        });
    </script>
@endpush
