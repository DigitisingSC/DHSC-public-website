// Only run Drupal code if Drupal is defined.
if (typeof Drupal !== 'undefined') {

  Drupal.behaviors.multi_step_webform_breadcrumbs = {
    attach: function (context) {
      // Find webform with class exists
      if (context.querySelector('form.m-webform__submission-form--hide-breadcrumbs')) {
        // If not hide breadcrumbs
        context.querySelector('.o-breadcrumb-region').style.display = 'none';
      }
    }
  }

  Drupal.behaviors.multi_step_webform_meta_title = {
    attach: function (context) {
      // Find replacement title for webform page
      const stepWrapper = context.querySelector('.webform-submission-form div.webform-step-use-page-title');
      // If exists, replace the title
      if (stepWrapper && stepWrapper.querySelector('h1')) {
        // Get current title
        const currentTitle = document.title.split('|');
        // Get page name
        if (currentTitle[1]) {
          currentTitle[0] = stepWrapper.querySelector('h1').textContent;
          // Replace with new current title with new one.
          document.title = currentTitle.join(' |');
        }
      }
    }
  }
}
