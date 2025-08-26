(function () {
  const manual = document.getElementById('triggerManual');
  const auto   = document.getElementById('triggerAuto');
  const cycle  = document.getElementById('cycle');

  if (manual && auto && cycle) {
    const sync = () => { cycle.disabled = manual.checked; };
    manual.addEventListener('change', sync);
    auto.addEventListener('change', sync);
    sync();
  }
})();
