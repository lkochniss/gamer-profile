const path = require('path');
const WebpackAssetsManifest = require('webpack-assets-manifest');
const webpack = require('webpack');

const ExtractTextPlugin = require('extract-text-webpack-plugin');

module.exports = {
    /* Entrypoints */
    entry: {
        'js/application': path.resolve(
            __dirname, 'assets/js/application.js'
        ),
        'css/application' : path.resolve(
          __dirname, 'assets/js/styles.js'
        ),
        'js/bootstrap-4': path.resolve(
            __dirname, 'node_modules/bootstrap/dist/js/bootstrap.js'
        ),
        'css/bootstrap-4': path.resolve(
            __dirname, 'node_modules/bootstrap/dist/css/bootstrap.css'
        ),
        'js/jquery': path.resolve(
            __dirname, 'node_modules/jquery/dist/jquery.js'
        ),
        'js/select2': path.resolve(
            __dirname, 'node_modules/select2/dist/js/select2.js'
        ),
        'css/select2': path.resolve(
            __dirname, 'node_modules/select2/dist/css/select2.css'
        ),
    },
    /* Export class constructor as entrypoint */
    output: {
        path: path.resolve(__dirname, 'public/'),
        pathinfo: true,
        filename: '[name]-[chunkhash].js',
        chunkFilename: '[id]-[chunkhash].js',
        libraryTarget: 'window'
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
                    use: ['css-loader', 'sass-loader']
                }),
            },
            {
                test: /\.css$/,
                use: ExtractTextPlugin.extract({
                    fallback: 'style-loader',
                    use: 'css-loader'
                })
            },
            {
                test: /\.(otf|eot|svg|ttf|woff)/,
                loader: 'url-loader?limit=8192'
            }
        ]
    },
    plugins: [
        new WebpackAssetsManifest({}),
        new ExtractTextPlugin({
            filename: '[name]-[chunkhash].css'
        }),
        new webpack.ProvidePlugin({
            $: 'jquery',
            jQuery: 'jquery'
        })
    ],
    watch: true
};
