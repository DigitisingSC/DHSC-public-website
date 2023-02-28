module.exports = {
  stories: ['../stories/**/*.stories.@(js|mdx)'],
	addons: [
		"@storybook/addon-links",
		"@storybook/addon-essentials",
		"@storybook/addon-docs",
		"@storybook/addon-viewport",
		'@storybook/addon-a11y',
	],
	staticDirs: ['../stories/assets'],
	webpackFinal: async (config, { configType }) => {
		config.module.rules.push({
			test: /\.twig$/,
			use: "twigjs-loader",
		});

		return config;
	}
};
