<div class="table-responsive">
  <table class="table table-sm table-bordered align-middle mb-0">
    <thead class="table-light">
      <tr>
        <th class="text-center" style="width:240px;">Parameter Setting</th>
        <th class="text-center">Value</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td class="fw-semibold">Measurement Trigger</td>
        <td class="td-center">
          <div class="btn-group" role="group" aria-label="Trigger mode">
            <input type="radio" class="btn-check" name="trigger" id="triggerManual"
                   autocomplete="off" value="manual" <?= $trigger_mode==='manual'?'checked':'' ?>>
            <label class="btn btn-outline-secondary btn-sm" for="triggerManual">Manual</label>

            <input type="radio" class="btn-check" name="trigger" id="triggerAuto"
                   autocomplete="off" value="auto" <?= $trigger_mode==='auto'?'checked':'' ?>>
            <label class="btn btn-outline-success btn-sm" for="triggerAuto">Auto</label>
          </div>
        </td>
      </tr>
      <tr>
        <td class="fw-semibold">Auto Trigger Cycle</td>
        <td>
          <div class="input-group input-group-sm" style="max-width:220px;">
            <input type="number" min="5" step="5" class="form-control" id="cycle"
                   value="<?= esc($cycle) ?>" aria-label="Cycle">
            <span class="input-group-text">s</span>
          </div>
        </td>
      </tr>
    </tbody>
  </table>
</div>
