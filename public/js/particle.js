document.addEventListener("DOMContentLoaded", function () {
  const apiUrl = "/partikelcounterbuffer"; // endpoint API
  const isoSelect = document.getElementById("isoClass");
  const tableBody = document.querySelector("#particleTableBody");

  // Batas ISO
  const isoLimits = {
    1: { 0.3: 0, 0.5: 0, 1.0: 0, 2.5: 0, 5.0: 0, 10: 0 },
    2: { 0.3: 10, 0.5: 4, 1.0: 0, 2.5: 0, 5.0: 0, 10: 0 },
    3: { 0.3: 102, 0.5: 35, 1.0: 8, 2.5: 0, 5.0: 0, 10: 0 },
    4: { 0.3: 1020, 0.5: 352, 1.0: 83, 2.5: 0, 5.0: 0, 10: 0 },
    5: { 0.3: 10200, 0.5: 3520, 1.0: 832, 2.5: 0, 5.0: 29, 10: 0 },
    6: { 0.3: 0, 0.5: 35200, 1.0: 8320, 2.5: 0, 5.0: 293, 10: 0 },
    7: { 0.3: 0, 0.5: 352000, 1.0: 83200, 2.5: 0, 5.0: 2930, 10: 0 },
    8: { 0.3: 0, 0.5: 3520000, 1.0: 832000, 2.5: 0, 5.0: 29300, 10: 0 },
    9: { 0.3: 0, 0.5: 35200000, 1.0: 8320000, 2.5: 0, 5.0: 293000, 10: 0 },
  };

  const sizeKeyMap = {
    0.3: "Value03",
    0.5: "Value05",
    1.0: "Value10",
    2.5: "Value25",
    5.0: "Value50",
    10: "Value100",
  };

  async function fetchData() {
    try {
      const res = await fetch(apiUrl);
      const json = await res.json();
      const data = json.data;

      renderTable(data);
    } catch (err) {
      console.error("Fetch error:", err);
    }
  }

  function renderTable(data) {
    const iso = parseInt(isoSelect.value);
    const allowedSizes = Object.keys(isoLimits[iso] || {})
      .map(parseFloat)
      .sort((a, b) => a - b)
      .map(String);

    tableBody.innerHTML = "";

    allowedSizes.forEach((size) => {
      const valueKey = sizeKeyMap[size];
      const value = data[valueKey] ?? "-";
      const limit = isoLimits[iso][size];

      const row = `
        <tr>
          <td class="text-center fw-semibold">&ge; ${size} µm</td>
          <td class="text-center">${value}</td>
          <td>
            <input type="number" class="form-control form-control-sm text-end"
                   value="${limit}">
          </td>
        </tr>`;
      tableBody.insertAdjacentHTML("beforeend", row);
    });
  }

  isoSelect.addEventListener("change", fetchData);
  fetchData();
});
