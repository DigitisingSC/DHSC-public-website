/**
 * Helper to determine if webp format is supported.
 *
 * @returns {boolean}
 *   True if webp is supported, otherwise false.
 *
 * @see https://stackoverflow.com/a/27232658/1476330
 */
function support_format_webp() {
  const elem = document.createElement('canvas');
  if (!!(elem.getContext && elem.getContext('2d'))) {
    return elem.toDataURL('image/webp').indexOf('data:image/webp') === 0;
  }
  return false;
}

// Only run Drupal code if Drupal is defined.
if (typeof Drupal !== 'undefined') {
  Drupal.behaviors.video = {
    attach: function (context) {
      const video_embeds = context.querySelectorAll('.m-video__content');
      video_embeds.forEach(function (video_embed) {
        const button = video_embed.querySelector('button');

        // If webp isn't supported by the browser, swap it out a legacy format.
        if (!support_format_webp()) {
          const bg = button.getAttribute('data-bg-legacy');
          button.setAttribute('style', 'background-image: url("' + bg + '")');
        }

        // When a play button is clicked:
        // 1) Copy the data-src attribute to the src attribute
        // 2) Delete the data-src attribute
        // 3) Detach the button from the DOM
        button.addEventListener('click', function (event) {
          const iframe = video_embed.querySelector('iframe');
          const src = iframe.getAttribute('data-src');
          console.log(src);
          iframe.removeAttribute('data-src');
          iframe.setAttribute('src', src);
          this.parentNode.removeChild(this);
        });
      });
    }
  }
}
