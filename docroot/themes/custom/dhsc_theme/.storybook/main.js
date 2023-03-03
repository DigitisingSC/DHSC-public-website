module.exports = {
  stories: ['../stories/**/*.stories.@(js|mdx|svg)'],
	addons: [
		"@storybook/addon-links",
		"@storybook/addon-essentials",
		"@storybook/addon-docs",
		"@storybook/addon-viewport",
	],
	staticDirs: ['../stories/assets'],
	webpackFinal: async (config, { configType }) => {
		config.module.rules.push({
			test: /\.twig$/,
			use: "twig-loader",
		});

		return config;
	}
};
