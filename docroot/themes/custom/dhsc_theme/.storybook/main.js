import path from "path";

/** @type { import('@storybook/html-webpack5').StorybookConfig } */
const config = {
  stories: ["../stories/**/*.mdx", "../stories/**/*.stories.@(js|jsx|ts|tsx)"],
  addons: ["@storybook/addon-links", "@storybook/addon-essentials", "@storybook/addon-viewport", "@storybook/addon-controls", "@storybook/addon-a11y", "@storybook/addon-docs", "@storybook/addon-interactions", {
    name: '@storybook/addon-styling',
    options: {
      sass: {
        // Require your Sass preprocessor here
        implementation: require('sass')
      },
      postCss: true
    }
  }, '@storybook/addon-mdx-gfm'],
  framework: {
    name: "@storybook/html-webpack5",
    options: {}
  },
  docs: {
    autodocs: "tag"
  },
  webpackFinal: async (config, {
    configType
  }) => {
    config.resolve.alias = {
      '@assets': path.resolve(__dirname, '../', 'stories/assets'),
      '@base': path.resolve(__dirname, '../', 'stories/00-base'),
      '@atoms': path.resolve(__dirname, '../', 'stories/01-atoms'),
      '@molecules': path.resolve(__dirname, '../', 'stories/02-molecules'),
      '@organisms': path.resolve(__dirname, '../', 'stories/03-organisms'),
      '@templates': path.resolve(__dirname, '../', 'stories/04-templates'),
      '@pages': path.resolve(__dirname, '../', 'stories/05-pages')
    };
    config.module.rules.push({
      test: /\.twig$/,
      use: "twigjs-loader",
    });
    return config;
  }
};
export default config;
