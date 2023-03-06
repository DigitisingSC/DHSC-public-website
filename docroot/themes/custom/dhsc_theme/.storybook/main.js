const path = require('path')

module.exports = {
  stories: ['../stories/**/*.stories.@(js|mdx)'],
	addons: [
		"@storybook/addon-links",
		"@storybook/addon-essentials",
		"@storybook/addon-docs",
		"@storybook/addon-viewport",
		"storybook-addon-sass-postcss",
	],
	staticDirs: ['../stories/assets'],
	webpackFinal: async (config, { configType }) => {
		config.module.rules.push({
			test: /\.twig$/,
			use: "twigjs-loader",
		});

		// config.module.rules = config.module.rules.filter((rule) => {
		// 	console.log('INDEX: ', rule.test, rule.test.toString() === '/\\.css$/')
		// 	if(rule.test.toString() == '/\\.(css|scss|sass)$/i') {
		// 		console.log("RULE", rule.use)
		// 	}

		// 	return rule;
		// })

		config.module.rules = config.module.rules.filter((rule) => rule.test.toString() !== '/\\.css$/')

		config.module.rules.push({
			test: /\.scss$/,
			use: [
				{
					loader: "sass-loader",
					options: {
						sassOptions: {
							quietDeps: true,
						},
					},
				},
			],
		})

		return config;
	}
};
