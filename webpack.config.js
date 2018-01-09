const path = require('path');

const ExtractTextPlugin = require('extract-text-webpack-plugin');

module.exports = {
    /* Entrypoints */
    entry: {
        'js/application': path.resolve(
            __dirname, 'assets/js/application.js'
        ),
        'css/application' : path.resolve(
          __dirname, 'assets/js/styles.js'
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
