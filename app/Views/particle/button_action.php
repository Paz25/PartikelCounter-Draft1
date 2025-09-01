<?php helper('url'); ?>

<div class="border rounded p-3 h-100 d-grid gap-2">
    <div class="d-flex flex-column gap-1 mb-2">
        <div class="d-flex align-items-center justify-content-between mb-1">
            <strong class="me-2">Coil Status:</strong>
            <span id="statusBadge" class="badge bg-secondary">Checking…</span>
        </div>
        <div class="d-flex align-items-center justify-content-between mb-1 gap-2">
            <button id="btnOn" class="btn btn-outline-primary btn-sm flex-grow-1">
                <span class="label">Turn On Device</span>
            </button>
            <button id="btnOff" class="btn btn-outline-danger btn-sm flex-grow-1">
                <span class="label">Turn Off Device</span>
            </button>
        </div>

        <div id="msg" class="small text-muted mt-2"></div>
    </div>
    <div class="d-flex flex-column gap-1 mb-2">
        <button id="btnStart" class="btn btn-outline-success btn-sm">
            <span class="label">Start Collecting Data</span>
        </button>
        <button id="btnStop" class="btn btn-outline-danger btn-sm">
            <span class="label">Stop Collecting Data</span>
        </button>
        <div class="d-flex align-items-center justify-content-between mb-1">
            <strong class="me-2">Data Collection:</strong>
            <span id="collectStatus" class="badge bg-secondary">Checking…</span>
        </div>

    </div>
    <a href="<?= site_url('/') ?>" class="btn btn-outline-info btn-sm">Back</a>

</div>

<script>
    const URL_COIL_ON = '<?= site_url('api/coil/on') ?>';
    const URL_COIL_OFF = '<?= site_url('api/coil/off') ?>';
    const URL_STATUS = '<?= site_url('api/coil/status') ?>';

    const URL_START = '<?= site_url('api/partikel/start') ?>';
    const URL_STOP = '<?= site_url('api/partikel/stop') ?>';

    const $btnOn = document.getElementById('btnOn');
    const $btnOff = document.getElementById('btnOff');
    const $btnStart = document.getElementById('btnStart');
    const $btnStop = document.getElementById('btnStop');
    const $msg = document.getElementById('msg');
    const $badge = document.getElementById('statusBadge');
    const $collectBadge = document.getElementById('collectStatus');

    function setBadge(state) {
        if (state === true) { $badge.className = 'badge bg-success'; $badge.textContent = 'ON'; }
        else if (state === false) { $badge.className = 'badge bg-danger'; $badge.textContent = 'OFF'; }
        else { $badge.className = 'badge bg-secondary'; $badge.textContent = 'N/A'; }
    }
    function say(text, ok = true) {
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
            body: '{}'
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

    $btnOn.addEventListener('click', async () => {
        setLoading($btnOn, true); say('Turning ON…');
        try {
            const j = await post(URL_COIL_ON);
            say('Status: ' + (j.status || 'ON'));
            setBadge(true);
        } catch (e) {
            say('Failed to turn ON: ' + e.message, false);
        } finally {
            setLoading($btnOn, false);
        }
    });
    $btnOff.addEventListener('click', async () => {
        setLoading($btnOff, true); say('Turning OFF…');
        try {
            const j = await post(URL_COIL_OFF);
            say('Status: ' + (j.status || 'OFF'));
            setBadge(false);
        } catch (e) {
            say('Failed to turn OFF: ' + e.message, false);
        } finally {
            setLoading($btnOff, false);
        }
    });

    async function updateCollectStatus() {
        try {
            const j = await get('<?= site_url('api/partikel/status') ?>');
            if (j.status) {
                $collectBadge.className = 'badge bg-success';
                $collectBadge.textContent = 'Collecting Data';
            } else {
                $collectBadge.className = 'badge bg-secondary';
                $collectBadge.textContent = 'Idle';
            }
        } catch {
            $collectBadge.className = 'badge bg-danger';
            $collectBadge.textContent = 'Error';
        }
    }

    updateCollectStatus();
    setInterval(updateCollectStatus, 5000);

    $btnStart.addEventListener('click', async () => {
        setLoading($btnStart, true); say('Starting data collection…');
        try {
            const j = await post('<?= site_url('api/partikel/start') ?>');
            say('Data collection started!');
            updateCollectStatus();
        } catch (e) {
            say('Failed to start: ' + e.message, false);
        } finally { setLoading($btnStart, false); }
    });

    $btnStop.addEventListener('click', async () => {
        console.log("btnStop clicked");
        setLoading($btnStop, true); say('Stopping data collection…');
        try {
            const j = await post('<?= site_url('api/partikel/stop') ?>');
            say('Data collection stopped!');
            updateCollectStatus();
        } catch (e) {
            say('Failed to stop: ' + e.message, false);
        } finally { setLoading($btnStop, false); }
    });



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