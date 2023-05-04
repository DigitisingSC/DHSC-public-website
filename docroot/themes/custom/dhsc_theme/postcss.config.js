// postcss.config.js

const nested = require('postcss-nested');
const postcssImport = require('postcss-import');
const autoprefixer = require('autoprefixer');

module.exports = {
  plugins: [
    nested,
    postcssImport,
    autoprefixer,
  ],
};
