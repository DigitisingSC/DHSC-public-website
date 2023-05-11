const path = require('path')
const glob = require("glob")
const MiniCssExtractPlugin = require("mini-css-extract-plugin")
const CopyPlugin = require("copy-webpack-plugin")
const mode = process.env.NODE_ENV === 'production' ? 'production' : 'development'

module.exports = {
  mode: mode,
  entry: {
    'tailwind': [
        './src/scss/tailwind.scss',
    ],
    'components': [
      glob.sync('./stories/**/*.scss').toString(),
      glob.sync('./stories/**/*.js', { "ignore": './stories/**/*.stories.js'}).toString()
    ],
    'global': [
      './src/scss/global.scss',
    ]
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
          'sass-loader',
          'resolve-url-loader',
          'postcss-loader'
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
      },
      {
        test: /\.(woff(2)?|ttf|eot|svg)(\?v=\d+\.\d+\.\d+)?$/,
        use: [
          {
            loader: 'file-loader',
            options: {
              name: '[name].[ext]',
              outputPath: 'fonts'
            }
          }
        ]
      }
    ]
  },
  devtool: 'source-map'
}
