const path = require('path');

const LEGACY_REGEXP = /^(\w+)::/;

/**
 * Transforms legacy namespace::template/path to @namespoace/template/path
 */
class LegacyNsResolverPlugin {
  apply(resolver) {
    const target = resolver.ensureHook('resolve');
    resolver
      .getHook('resolve')
      .tapAsync('LegacyNsResolverPlugin', (request, resolveContext, callback) => {
        const requestPath = request.request;
        if (!requestPath.match(LEGACY_REGEXP)) {
          callback();
          return;
        }

        const newRequest = {
          ...request,
          request: requestPath.replace(LEGACY_REGEXP, '@$1/'),
        };

        resolver.doResolve(target, newRequest, null, resolveContext, callback);
      });
  }
}

module.exports = {
  stories: ['../stories/**/*.stories.@(js|mdx)'],
	addons: [
		"@storybook/addon-links",
		"@storybook/addon-essentials",
		"@storybook/addon-docs",
		"@storybook/addon-viewport",
		'@storybook/addon-a11y',
		"storybook-addon-sass-postcss",
	],
	staticDirs: ['../stories/assets'],
	webpackFinal: async (config, { configType }) => {
		config.module.rules.push({
			test: /\.twig$/,
			use: "twigjs-loader",
		});

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


		// Twig namespaces
		config.resolve = {
			alias: {
				'@assets': path.resolve(__dirname, '../' ,'stories/assets'),
			},
			plugins: [new LegacyNsResolverPlugin()],
		};

		return config;
	}
};
