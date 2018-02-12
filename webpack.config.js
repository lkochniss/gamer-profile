const path = require('path');

const ExtractTextPlugin = require('extract-text-webpack-plugin');

const $ = require('jquery');
const jQuery = require('jquery');


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
        'js/bootstrap-3': path.resolve(
            __dirname, 'node_modules/startbootstrap-sb-admin-2/vendor/bootstrap/js/bootstrap.min.js'
        ),
        'css/bootstrap-3': path.resolve(
            __dirname, 'node_modules/startbootstrap-sb-admin-2/vendor/bootstrap/css/bootstrap.min.css'
        ),
        'js/sb-admin-2': path.resolve(
            __dirname, 'node_modules/startbootstrap-sb-admin-2/dist/js/sb-admin-2.min.js'
        ),
        'css/sb-admin-2': path.resolve(
            __dirname, 'node_modules/startbootstrap-sb-admin-2/dist/css/sb-admin-2.min.css'
        ),
        'js/metis-menu': path.resolve(
            __dirname, 'node_modules/startbootstrap-sb-admin-2/vendor/metisMenu/metisMenu.min.js'
        ),
        'css/metis-menu': path.resolve(
            __dirname, 'node_modules/startbootstrap-sb-admin-2/vendor/metisMenu/metisMenu.min.css'
        ),
        'js/morris': path.resolve(
            __dirname, 'node_modules/startbootstrap-sb-admin-2/vendor/morrisjs/morris.min.js'
        ),
        'css/morris': path.resolve(
            __dirname, 'node_modules/startbootstrap-sb-admin-2/vendor/morrisjs/morris.css'
        ),
        'js/raphael': path.resolve(
            __dirname, 'node_modules/startbootstrap-sb-admin-2/vendor/raphael/raphael.min.js'
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
        filename: '[name].js',
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
        new ExtractTextPlugin({
            filename: '[name].css',
        }),
    ],
    watch: true
};
