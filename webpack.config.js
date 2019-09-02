const path = require('path');
const WebpackAssetsManifest = require('webpack-assets-manifest');
const webpack = require('webpack');

const ExtractTextPlugin = require('extract-text-webpack-plugin');

module.exports = {
  mode: 'development',
  optimization: {
    minimize: false,
  },
  /* Entrypoints */
  entry: {
    'js/application': path.resolve(__dirname, 'assets/js/application.js'),
    'css/application': path.resolve(__dirname, 'assets/js/styles.js'),
    'css/dark-theme': path.resolve(__dirname, 'assets/js/dark-theme.js'),
  },
  /* Export class constructor as entrypoint */
  output: {
    path: path.resolve(__dirname, 'public/'),
    pathinfo: true,
    filename: '[name]-[chunkhash].js',
    chunkFilename: '[id]-[chunkhash].js',
    libraryTarget: 'window',
  },
  module: {
    rules: [
      {
        test: /\.js$/,
        use: 'babel-loader',
        exclude: path.resolve('node_modules'),
      },
      {
        test: /\.scss$/,
        use: ExtractTextPlugin.extract({
          fallback: 'style-loader',
          use: ['css-loader', 'sass-loader'],
        }),
      },
      {
        test: /\.css$/,
        use: ExtractTextPlugin.extract({
          fallback: 'style-loader',
          use: 'css-loader',
        }),
      },
      {
        test: /\.(otf|eot|svg|ttf|woff)/,
        loader: 'url-loader?limit=8192',
      },
      {
        test: /\.(png|jpg|jpeg|gif|ico)$/,
        use: [
          {
            loader: 'file-loader',
            options: {
              name: '[name].[hash].[ext]',
              outputPath: 'img/',
              publicPath: '../img',
            },
          },
        ],
      },
    ],
  },
  plugins: [
    new WebpackAssetsManifest({}),
    new ExtractTextPlugin({
      filename: '[name]-[chunkhash].css',
    }),
    new webpack.ProvidePlugin({
      $: 'jquery',
      jQuery: 'jquery',
      d3: 'd3',
    }),
  ]
};
