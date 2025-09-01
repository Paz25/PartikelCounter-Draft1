<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>

<div class="mx-auto">
    <div class="row">
        <!-- Kolom tabel -->
        <div class="col-md-6 col-12">
            <div class="card shadow">
                <div class="card-body">
                    <div class="p-4" id="particle-container">
                        <div id="loading">Loading data...</div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Kolom grafik -->
        <div class="col-md-6 col-12">
            <div class="card shadow h-100 md:mih-h">
                <div class="card-body p-4 d-flex">
                    <div class="w-100 h-100 d-flex align-items-center justify-content-center">
                        <canvas id="particleChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    let chart;

    async function fetchParticle() {
        try {
            const res = await fetch("<?= base_url('/api/partikelcounterbuffer') ?>");
            const json = await res.json();

            if (!json.data) {
                document.getElementById('particle-container').innerHTML = "<p class='text-danger'>No data found</p>";
                return;
            }

            const particle = json.data;
            const isoClass = particle.iso_class ?? '-';

            const limitRes = await fetch("<?= base_url('/api/iso-limits') ?>/" + isoClass);
            const limitJson = await limitRes.json();
            const limits = limitJson.data ?? {};

            // === Set status badge ===
            const statusValue = particle.Status ?? '-';
            let statusBadge = `<span class="badge bg-secondary p-2 px-5">${statusValue}</span>`;

            if (statusValue.toLowerCase() === 'normal') {
                statusBadge = `<span class="badge bg-success p-2 px-5">Normal</span>`;
            } else if (statusValue.toLowerCase() === 'alarm') {
                statusBadge = `<span class="badge bg-danger text-white p-2 px-5">Alarm</span>`;
            }

            // === Render Tabel ===
            const html = `
                <!-- Header atas -->
                <div class="row border-bottom pb-2 mb-3">
                    <div class="col-8 fw-bold" onclick="window.location.href='/particle'" style="cursor:pointer;">
                        Particle Counter ${particle.mac_address ?? 'Room Name'}
                    </div>
                    <div class="col fw-bold">Signal Dbm ${particle.SignalDb ?? '-'}</div>
                </div>

                <!-- Judul kolom -->
                <div class="row fw-bold border-bottom py-2">
                    <div class="col-4">Particle size (µm)</div>
                    <div class="col-8 d-flex flex-column">
                        <div class="text-center">Particle count/m³</div>
                        <div class="row">
                            <div class="col-6 text-center">Limit (ISO ${isoClass})</div>
                            <div class="col-6 text-center">Actual</div>
                        </div>
                    </div>
                </div>

                <!-- Data rows -->
                <div class="row py-2 border-bottom">
                    <div class="col-4">&ge; 0.3</div>
                    <div class="col-4 text-center">${limits.Limit03 ?? '-'}</div>
                    <div class="col-4 text-center">${particle.Value03 ?? '-'}</div>
                </div>
                <div class="row py-2 border-bottom">
                    <div class="col-4">&ge; 0.5</div>
                    <div class="col-4 text-center">${limits.Limit05 ?? '-'}</div>
                    <div class="col-4 text-center">${particle.Value05 ?? '-'}</div>
                </div>
                <div class="row py-2 border-bottom">
                    <div class="col-4">&ge; 1.0</div>
                    <div class="col-4 text-center">${limits.Limit10 ?? '-'}</div>
                    <div class="col-4 text-center">${particle.Value10 ?? '-'}</div>
                </div>
                <div class="row py-2 border-bottom">
                    <div class="col-4">&ge; 5.0</div>
                    <div class="col-4 text-center">${limits.Limit50 ?? '-'}</div>
                    <div class="col-4 text-center">${particle.Value50 ?? '-'}</div>
                </div>

                <div class="row my-3">
                    <div class="col-6 fw-bold">Time update</div>
                    <div class="col-6 text-end">${particle.waktu ?? '-'}</div>
                </div>
                <div class="row">
                    <div class="col-6 fw-bold">Status</div>
                    <div class="col-6 text-end" onclick="window.location.href='/history'" style="cursor:pointer;">
                        ${statusBadge}
                    </div>
                </div>
            `;

            document.getElementById('particle-container').innerHTML = html;

            const labels = ["≥0.3µm", "≥0.5µm", "≥1.0µm", "≥10.0µm"];
            const actualValues = [
                particle.Value03 ?? 0,
                particle.Value05 ?? 0,
                particle.Value10 ?? 0,
                particle.Value100 ?? 0
            ];
            const limitValues = [
                particle.Limit03 ?? 0,
                particle.Limit05 ?? 0,
                particle.Limit10 ?? 0,
                particle.Limit100 ?? 0
            ];

            const ctx = document.getElementById('particleChart').getContext('2d');
            if (!chart) {
                chart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [
                            {
                                label: 'Actual',
                                data: actualValues,
                                backgroundColor: '#6dcefa'
                            },
                            {
                                label: `Limit (ISO ${isoClass})`,
                                data: limitValues,
                                backgroundColor: '#0052d6'
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: { position: 'top' }
                        },
                        scales: {
                            y: { beginAtZero: true }
                        }
                    }
                });
            } else {
                chart.data.datasets[0].data = actualValues;
                chart.data.datasets[1].data = limitValues;
                chart.data.datasets[1].label = `Limit (ISO ${isoClass})`;
                chart.update();
            }

        } catch (err) {
            console.error(err);
            document.getElementById('particle-container').innerHTML = "<p class='text-danger'>Error loading data</p>";
        }
    }

    fetchParticle();
    setInterval(fetchParticle, 10000);
</script>
<?= $this->endSection() ?>