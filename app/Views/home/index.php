<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>

<div class="mx-auto card-narrow">
    <div class="card shadow-sm">
        <div class="card-body" id="partikel-container">

            <!-- Loading -->
            <div id="loading">Loading data...</div>

        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    async function fetchPartikel() {
        try {
            const res = await fetch("<?= base_url('/partikelcounterbuffer') ?>");
            const json = await res.json();

            if (!json.data) {
                document.getElementById('partikel-container').innerHTML = "<p class='text-danger'>No data found</p>";
                return;
            }

            const partikel = json.data;

            // Build HTML
            const html = `
                <!-- Header atas -->
                <div class="row border-bottom pb-2 mb-3">
                    <div class="col-8 fw-bold" onclick="window.location.href='/particle'" style="cursor:pointer;">
                        Particle Counter ${partikel.mac_address ?? 'Room Name'}
                    </div>
                    <div class="col fw-bold">Signal Dbm ${partikel.SignalDb ?? '-'}</div>
                </div>

                <!-- Judul kolom -->
                <div class="row fw-bold border-bottom py-2">
                    <div class="col-4">Particle size (µm)</div>
                    <div class="col-8 d-flex flex-column">
                        <div class="text-center">Particle count/m³</div>
                        <div class="row">
                            <div class="col-6 text-center">Limit (ISO 7)</div>
                            <div class="col-6 text-center">Actual</div>
                        </div>
                    </div>
                </div>

                <!-- Data rows -->
                <div class="row py-2 border-bottom">
                    <div class="col-4">&ge; 0.3</div>
                    <div class="col-4 text-center">${partikel.Limit03 ?? '-'}</div>
                    <div class="col-4 text-center">${partikel.Value03 ?? '-'}</div>
                </div>
                <div class="row py-2 border-bottom">
                    <div class="col-4">&ge; 0.5</div>
                    <div class="col-4 text-center">${partikel.Limit05 ?? '-'}</div>
                    <div class="col-4 text-center">${partikel.Value05 ?? '-'}</div>
                </div>
                <div class="row py-2 border-bottom">
                    <div class="col-4">&ge; 1.0</div>
                    <div class="col-4 text-center">${partikel.Limit10 ?? '-'}</div>
                    <div class="col-4 text-center">${partikel.Value10 ?? '-'}</div>
                </div>
                <div class="row py-2 border-bottom">
                    <div class="col-4">&ge; 5.0</div>
                    <div class="col-4 text-center">${partikel.Limit50 ?? '-'}</div>
                    <div class="col-4 text-center">${partikel.Value50 ?? '-'}</div>
                </div>
                <div class="row py-2 border-bottom">
                    <div class="col-4">&ge; 10.0</div>
                    <div class="col-4 text-center">${partikel.Limit100 ?? '-'}</div>
                    <div class="col-4 text-center">${partikel.Value100 ?? '-'}</div>
                </div>

                <div class="row mt-3">
                    <div class="col-6 fw-bold">Time update</div>
                    <div class="col-6 text-end">${partikel.waktu ?? '-'}</div>
                </div>
                <div class="row">
                    <div class="col-6 fw-bold">Status</div>
                    <div class="col-6 text-end">${partikel.Status ?? '-'}</div>
                </div>
            `;

            document.getElementById('partikel-container').innerHTML = html;

        } catch (err) {
            console.error(err);
            document.getElementById('partikel-container').innerHTML = "<p class='text-danger'>Error loading data</p>";
        }
    }

    // load saat pertama kali
    fetchPartikel();

    // refresh otomatis setiap 10 detik
    setInterval(fetchPartikel, 10000);
</script>
<?= $this->endSection() ?>