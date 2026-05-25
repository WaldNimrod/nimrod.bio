(function () {
  function parseWorlds(url) {
    return (url.searchParams.get('world') || '').split(',').filter(Boolean);
  }

  document.querySelectorAll('.filter-chip[data-world]').forEach(function (chip) {
    chip.addEventListener('click', function () {
      var world = chip.getAttribute('data-world');
      var url = new URL(window.location.href);
      var current = parseWorlds(url);
      var next = current.indexOf(world) >= 0 ? current.filter(function (x) { return x !== world; }) : current.concat([world]);
      if (next.length) {
        url.searchParams.set('world', next.join(','));
      } else {
        url.searchParams.delete('world');
      }
      window.location.href = url.toString();
    });
  });

  document.querySelectorAll('.view-toggle-btn[data-view]').forEach(function (btn) {
    btn.addEventListener('click', function () {
      var view = btn.getAttribute('data-view');
      var url = new URL(window.location.href);
      if (view === 'flow') {
        url.searchParams.delete('view');
      } else {
        url.searchParams.set('view', view);
      }
      window.location.href = url.toString();
    });
  });
})();
