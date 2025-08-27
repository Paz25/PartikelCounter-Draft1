<?php $pct = ($sampling_total ?? 0) > 0 ? round(($sampling_now / $sampling_total) * 100, 1) : 0; ?>

<div class="section p-2">
    <?= csrf_field() ?>

    <div class="row g-2 align-items-center">
        <div class="col-12 col-md">
            <div class="d-flex align-items-center gap-2">
                <label for="isoClass" class="form-label mb-0">ISO Class Number</label>
                <select id="isoClass" name="iso_class" class="form-select form-select-sm w-auto">
                    <?php for ($i = 1; $i <= 9; $i++): ?>
                        <option value="<?= $i ?>" <?= ($iso_class == $i ? 'selected' : '') ?>><?= $i ?></option>
                    <?php endfor; ?>
                </select>
            </div>
        </div>

        <div class="col-auto">
            <button type="submit" class="btn btn-primary btn-sm">Simpan Limit</button>
        </div>

        <div class="col-12">
            <div class="small text-muted border-top pt-2">
                calculating (<?= esc($elapsed_sec) ?> s), sampling <?= esc($sampling_now) ?> of
                <?= esc($sampling_total) ?>
            </div>
        </div>
    </div>
</div>