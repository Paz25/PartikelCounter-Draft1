<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="mx-auto card-narrow">

    <!-- Header IP -->
    <div class="d-flex align-items-center justify-content-center mb-3">
        <span class="h5 mb-0">IP Device: <?= esc($device_ip) ?></span>
    </div>

    <div class="card shadow-sm border border-1">
        <div class="card-body">

            <!-- Table nilai -->
            <?= $this->include('particle/table_particle', ['rows' => $rows]) ?>

            <!-- ISO + Progress (gabung) -->
            <?= $this->include('particle/iso_progress', [
                'iso_class' => $iso_class,
                'elapsed_sec' => $elapsed_sec,
                'sampling_now' => $sampling_now,
                'sampling_total' => $sampling_total
            ]) ?>


            <!-- Parameter + Actions -->
            <div class="section p-2">
                <div class="row g-3">
                    <div class="col-12 col-md-6">
                        <?= $this->include('particle/parameter_particle', [
                            'trigger_mode' => $trigger_mode,
                            'cycle' => $cycle
                        ]) ?>
                    </div>
                    <div class="col-12 col-md-6">
                        <?= $this->include('particle/button_action') ?>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
<?= $this->endSection() ?>