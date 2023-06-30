// Only run Drupal code if Drupal is defined.
if (typeof Drupal !== 'undefined') {
  Drupal.behaviors.back_link = {
    attach: function (context) {
      const back_link = context.querySelector('.m-back-link');
      back_link.addEventListener('click', function (event) {
        event.preventDefault();
        history.back();
      });
    }
  }
}
