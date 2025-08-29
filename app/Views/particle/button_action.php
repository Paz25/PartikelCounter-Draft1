<?php helper('url'); ?>

<div class="border rounded p-3 h-100 d-grid gap-2">
  <div class="d-flex align-items-center justify-content-between mb-1">
    <strong class="me-2">Coil Status:</strong>
    <span id="statusBadge" class="badge bg-secondary">Checking…</span>
  </div>

  <button id="btnStart" class="btn btn-outline-primary btn-sm">
    <span class="label">Start Measurement</span>
  </button>

  <button id="btnStop" class="btn btn-outline-danger btn-sm">
    <span class="label">Stop</span>
  </button>

  <a href="<?= site_url('/') ?>" class="btn btn-outline-info btn-sm">Back</a>

  <div id="msg" class="small text-muted mt-2"></div>
</div>

<script>
const URL_ON     = '<?= site_url('api/coil/on') ?>';
const URL_OFF    = '<?= site_url('api/coil/off') ?>';
const URL_STATUS = '<?= site_url('api/coil/status') ?>';

const $btnStart = document.getElementById('btnStart');
const $btnStop  = document.getElementById('btnStop');
const $msg      = document.getElementById('msg');
const $badge    = document.getElementById('statusBadge');

function setBadge(state) {
  if (state === true) { $badge.className = 'badge bg-success';  $badge.textContent = 'ON'; }
  else if (state === false) { $badge.className = 'badge bg-danger'; $badge.textContent = 'OFF'; }
  else { $badge.className = 'badge bg-secondary'; $badge.textContent = 'N/A'; }
}
function say(text, ok=true) {
  $msg.textContent = text;
  $msg.className = 'small ' + (ok ? 'text-success' : 'text-danger');
}
function setLoading(btn, loading) {
  btn.disabled = loading;
  btn.classList.toggle('disabled', loading);
  if (loading) {
    btn.dataset.old = btn.innerHTML;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>' + btn.querySelector('.label').textContent;
  } else if (btn.dataset.old) {
    btn.innerHTML = btn.dataset.old;
    delete btn.dataset.old;
  }
}

async function post(url) {
  const res = await fetch(url, {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: '{}' // tanpa CSRF, body bisa kosong
  });
  const json = await res.json().catch(() => ({}));
  if (!res.ok || json.ok === false) throw new Error(json.error || ('HTTP ' + res.status));
  return json;
}
async function get(url) {
  const res = await fetch(url);
  const json = await res.json().catch(() => ({}));
  if (!res.ok || json.ok === false) throw new Error(json.error || ('HTTP ' + res.status));
  return json;
}

$btnStart.addEventListener('click', async () => {
  setLoading($btnStart, true); say('Turning ON…');
  try {
    const j = await post(URL_ON);
    say('Status: ' + (j.status || 'ON'));
    setBadge(true);
  } catch (e) {
    say('Failed to turn ON: ' + e.message, false);
  } finally {
    setLoading($btnStart, false);
  }
});
$btnStop.addEventListener('click', async () => {
  setLoading($btnStop, true); say('Turning OFF…');
  try {
    const j = await post(URL_OFF);
    say('Status: ' + (j.status || 'OFF'));
    setBadge(false);
  } catch (e) {
    say('Failed to turn OFF: ' + e.message, false);
  } finally {
    setLoading($btnStop, false);
  }
});

// cek status saat halaman dibuka
(async () => {
  try {
    const j = await get(URL_STATUS);
    setBadge(j.status);
    say('Current: ' + (j.status === true ? 'ON' : j.status === false ? 'OFF' : 'N/A'));
  } catch {
    setBadge(null);
    say('Cannot get status.', false);
  }
})();
</script>
