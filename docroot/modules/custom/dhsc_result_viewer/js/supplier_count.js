function supplierCounts() {
  return {
    counts: {}, total_counts: 0, selected: {},

    init() {
      this.updateSelected();
      this.fetchCounts();
      this.attachListeners();
    },

    // Scan DOM and collect selected answers
    updateSelected() {
      const selected = {};
      document.querySelectorAll('input[type="checkbox"]:checked').forEach(el => {
        selected[el.name] = true;
      });
      this.selected = selected;
    },

    // Attach change listeners to update and re-fetch counts
    attachListeners() {
      document.querySelectorAll('input[type="checkbox"]').forEach(el => {
        el.addEventListener('change', () => {
          this.updateSelected();
          this.fetchCounts();
        });
      });
    },

    // Fetch the counts from the server
    fetchCounts() {
      const formState = {};
      document.querySelectorAll('input[type="checkbox"]').forEach(el => {
        formState[el.name] = el.checked;
      });

      fetch('/dhsc/supplier-counts', {
        method: 'POST', headers: {
          'Content-Type': 'application/json',
        }, body: JSON.stringify({form_state: formState}),
      })
        .then(res => res.json())
        .then(data => {
          this.counts = data.counts || {};
          this.total_counts = data.total_counts ?? 0;
        })
        .catch(err => {
          console.error('Supplier count fetch failed:', err);
          this.counts = {};
          this.total_counts = 0;
        });
    }
  };
}
