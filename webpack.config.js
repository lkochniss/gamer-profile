const path = require('path');

const ExtractTextPlugin = require('extract-text-webpack-plugin');

module.exports = {
    /* Entrypoints */
    entry: {
        'js/application': path.resolve(
            __dirname, 'assets/js/application.js'
        )
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
                use: [{
                    loader: 'style-loader', // inject CSS to page
                }, {
                    loader: 'css-loader', // translates CSS into CommonJS modules
                }, {
                    loader: 'postcss-loader', // Run post css actions
                    options: {
                        plugins: function () { // post css plugins, can be exported to postcss.config.js
                            return [
                                require('precss'),
                                require('autoprefixer')
                            ];
                        }
                    }
                }, {
                    loader: 'sass-loader' // compiles Sass to CSS
                }]
            }
        ]
    },
    plugins: [
        new ExtractTextPlugin({
            filename: '[name].min.css',
        }),
    ],
    watch: true
};
