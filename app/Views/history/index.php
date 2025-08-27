<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="container">
    <h4 class="mb-3">History Data</h4>

    <div class="d-flex gap-2 mb-3 flex-wrap">
        <input type="date" id="dateFilter" class="form-control w-auto">
        <select id="statusFilter" class="form-select w-auto">
            <option value="All">All</option>
            <option value="Normal">Normal</option>
            <option value="Alarm">Alarm</option>
        </select>
        <button id="showBtn" class="btn btn-primary">Show</button>
        <button id="clearBtn" class="btn btn-danger">✕</button>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-striped table-sm">
            <thead class="table-secondary">
                <tr>
                    <th class="text-center">No</th>
                    <th class="text-center">Record Time</th>
                    <th class="text-center">Status</th>
                </tr>
            </thead>
            <tbody id="historyTableBody">
                <tr>
                    <td colspan="3" class="text-center">No data</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    const apiUrl = "/partikelcounterdata";
    const tableBody = document.getElementById("historyTableBody");
    const dateFilter = document.getElementById("dateFilter");
    const statusFilter = document.getElementById("statusFilter");
    const showBtn = document.getElementById("showBtn");
    const clearBtn = document.getElementById("clearBtn");

    async function fetchData() {
        try {
            const res = await fetch(apiUrl);
            const json = await res.json();
            return json.data || [];
        } catch (err) {
            console.error("Fetch error:", err);
            return [];
        }
    }

    function renderTable(data) {
        tableBody.innerHTML = "";
        if (data.length === 0) {
            tableBody.innerHTML = `<tr><td colspan="3" class="text-center">No data</td></tr>`;
            return;
        }

        data.forEach((row, index) => {
            const statusClass = row.Status === "Alarm" ? "status-alarm" : "status-normal";
            const tr = `
          <tr class="${statusClass}">
            <td class="text-center">${index + 1}</td>
            <td class="text-center">${row.waktu}</td>
            <td class="text-center">${row.Status}</td>
          </tr>
        `;
            tableBody.insertAdjacentHTML("beforeend", tr);
        });
    }

    async function applyFilter() {
        const allData = await fetchData();
        const selectedDate = dateFilter.value;
        const selectedStatus = statusFilter.value;

        let filtered = allData;

        if (selectedDate) {
            filtered = filtered.filter(d => d.RecordTime.startsWith(selectedDate));
        }

        if (selectedStatus !== "All") {
            filtered = filtered.filter(d => d.Status === selectedStatus);
        }

        renderTable(filtered);
    }

    // Event listeners
    showBtn.addEventListener("click", applyFilter);
    clearBtn.addEventListener("click", () => {
        dateFilter.value = "";
        statusFilter.value = "All";
        applyFilter();
    });

    // Load awal
    applyFilter();
</script>
<?= $this->endSection() ?>