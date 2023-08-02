// Only run Drupal code if Drupal is defined.
if (typeof Drupal !== 'undefined') {
  Drupal.behaviors.active_filters_click = {
    attach: function (context) {
      const activeFilters = context.querySelectorAll('.m-active-filter-js');
      activeFilters.forEach((activeFilter) => {
        activeFilter.addEventListener('click', function (e) {
          e.preventDefault();
          if (activeFilter && activeFilter.dataset && activeFilter.dataset.target) {
            const filter = document.querySelector(`form#views-exposed-form-digital-skills-training-page-skills-search-page input[value="${activeFilter.dataset.target}"]`);
            if (filter) {
              filter.click();
            }
          }
        });
      });
    }
  }
}
