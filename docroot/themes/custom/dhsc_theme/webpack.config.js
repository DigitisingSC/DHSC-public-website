const path = require('path')
const glob = require("glob")
const MiniCssExtractPlugin = require("mini-css-extract-plugin")
const CopyPlugin = require("copy-webpack-plugin")
const mode = process.env.NODE_ENV === 'production' ? 'production' : 'development'

module.exports = {
  mode: mode,
  entry: {
    'localgov': glob.sync('./src/localgov/**/*.css'),
    'overrides': [
        './src/overrides/js/overrides.js',
        './src/overrides/scss/overrides.scss',
    ],
    'custom': [
      glob.sync('./stories/**/*.scss').toString(),
      glob.sync('./stories/**/*.js', { "ignore": './stories/**/*.stories.js'}).toString()
    ],
  },
  output: {
    filename: 'js/[name].js',
    path: path.resolve(__dirname, './dist'),
    clean: true,
  },
  plugins: [
    new MiniCssExtractPlugin({
      filename: 'css/[name].css',
    }),
    new CopyPlugin({
      patterns: [
        { from: "./stories/assets", to: "../assets" },
      ],
    }),
  ],
  module: {
    rules: [
      {
        test: /\.(s[ac]|c)ss$/i,
        exclude: [
          /node_modules/,
        ],
        use: [
          MiniCssExtractPlugin.loader,
          'css-loader',
          'resolve-url-loader',
          'postcss-loader',
          'sass-loader'
        ],
      },
      {
        test: /\.js$/,
        exclude: [
          /node_modules/,
          /\.stories.(js|jsx)$/,
        ],
        use: {
          loader: 'babel-loader',
        }
      },
      {
        test: /\.twig$/,
        use: {
          loader: 'twigjs-loader',
        }
      }
    ]
  },
  devtool: 'source-map'
}
