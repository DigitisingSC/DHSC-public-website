const { resolve } = require('path');
const twigDrupal = require('twig-drupal-filters');

module.exports.setupTwig = function setupTwig(twig) {
  twig.cache();
  twigDrupal(twig);
  return twig;
};
