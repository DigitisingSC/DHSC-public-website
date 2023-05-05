const path = require('path');

module.exports = {
  core: {
    builder: "webpack5",
  },
  "stories": ["../stories/**/*.mdx", "../stories/**/*.stories.@(js|jsx|ts|tsx)"],
  "addons": [
    "@storybook/preset-scss",
    "@storybook/addon-a11y",
    "@storybook/addon-links",
    "@storybook/addon-styling",
    "@storybook/addon-essentials",
    "@storybook/addon-interactions",
    {
      name: '@storybook/addon-postcss',
      options: {
        cssLoaderOptions: {
          importLoaders: 1
        },
        postcssLoaderOptions: {
          implementation: require('postcss')
        }
      }
    }, "@storybook/addon-mdx-gfm"],
  staticDirs: ['../stories/assets'],
  "framework": {
    name: '@storybook/html-webpack5',
    options: {}
  },
  webpackFinal: async (config, {
    configType
  }) => {

    // Alias
    config.resolve.alias = {
      '@assets': path.resolve(__dirname, '../', 'stories/assets'),
      '@base': path.resolve(__dirname, '../', 'stories/00-base'),
      '@atoms': path.resolve(__dirname, '../', 'stories/01-atoms'),
      '@molecules': path.resolve(__dirname, '../', 'stories/02-molecules'),
      '@organisms': path.resolve(__dirname, '../', 'stories/03-organisms'),
      '@templates': path.resolve(__dirname, '../', 'stories/04-templates'),
    }

    config.module.rules.push({
      test: /\.twig$/,
      use: "twigjs-loader"
    });
    return config;
  },
  docs: {
    autodocs: true
  }
};
