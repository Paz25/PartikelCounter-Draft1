<div class="section">
  <div class="table-responsive">
    <table class="table table-sm table-striped table-bordered align-middle table-tight mb-0">
      <thead class="table-light align-middle">
        <tr>
          <th class="text-center" rowspan="2" style="width:160px;">Partikel Size<br>(µm)</th>
          <th class="text-center" colspan="2">Particle/M³</th>
        </tr>
        <tr>
          <th class="text-center">Value</th>
          <th class="text-center">Limit</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($rows as $i => $r): ?>
          <tr>
            <td class="text-center fw-semibold"><?= esc($r['label']) ?></td>
            <td class="text-center"><?= number_format($r['value']) ?></td>
            <td>
              <input type="number" class="form-control form-control-sm text-end"
                     name="limits[<?= $i ?>]" value="<?= esc($r['limit']) ?>">
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
