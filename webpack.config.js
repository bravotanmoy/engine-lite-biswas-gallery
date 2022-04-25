const path = require('path');
const autoprefixer = require('autoprefixer');
const cssnano = require('cssnano');
const {WebpackManifestPlugin} = require('webpack-manifest-plugin');
const {CleanWebpackPlugin} = require('clean-webpack-plugin');
const CopyPlugin = require("copy-webpack-plugin");
const TerserPlugin = require("terser-webpack-plugin");
const MiniCssExtractPlugin = require('mini-css-extract-plugin');

const isProduction = process.argv[process.argv.indexOf('--mode') + 1] === 'production';

const jsRule = {
    test: /\.js$/,
    exclude: /node_modules/,
    use: [],
};

const postCssOptions = isProduction ?{
    postcssOptions: {
        plugins: [
            autoprefixer(),
            cssnano()
        ]
    }
}:{
    sourceMap: true,
    postcssOptions: {
        plugins: [
            autoprefixer(),
        ]
    }
};

const frontendScssRule = {
    test: /\.scss$/,
    exclude: /node_modules/,
    use: [
        {
            loader: MiniCssExtractPlugin.loader,
            options: {
                publicPath: 'css/',
            },
        },
        {
            loader: 'css-loader',
            options: {
                url: false,
                importLoaders: 2,
                sourceMap: true
            }
        },
        {loader: 'postcss-loader', options: postCssOptions},
        {
            loader: "sass-loader",
            options: {
                sourceMap: true
            },
        },
    ]
};

const scssRule = {
    test: /\.scss$/,
    exclude: /node_modules/,
    use: [
        {
            loader: 'file-loader',
            options: {outputPath: 'css/', name: '[name].[contenthash].css'}
        },
        {loader: 'postcss-loader', options: postCssOptions},
        {
            loader: "sass-loader",
            options: {},
        },
    ]
};

const optimizationParams = isProduction ?{
    minimize: true,
    minimizer: [
        new TerserPlugin({
            parallel: 4,
        }),
    ],
}:{};

module.exports = [
    {
        name: 'frontend',
        devtool: 'source-map',
        entry: {
            frontend: [
                __dirname + '/resources/js/ajax_submit.js',
                __dirname + '/resources/js/frontend/frontend.js',
                __dirname + '/resources/js/frontend/filters.js',
                __dirname + '/resources/scss/frontend.scss',
            ]
        },
        output: {
            path: path.resolve(__dirname, 'public/frontend'),
            filename: 'js/[name].[contenthash].js',
        },
        module: {
            rules: [
                jsRule,
                frontendScssRule
            ]
        },
        plugins: [
            new CleanWebpackPlugin(),
            new WebpackManifestPlugin({
                publicPath: 'public/frontend/'
            }),
            new MiniCssExtractPlugin({
                filename: 'css/[name].[contenthash].css',
            }),
        ],
        optimization: optimizationParams,
    },
    {
        name: 'vendors',
        entry: {
            frontend: [
                __dirname + '/resources/js/vendors/frontend.js',
                __dirname + '/resources/scss/vendors/frontend.scss'
            ],
            price_filter: {
                import: [
                    __dirname + '/resources/js/vendors/price_filter.js',
                    __dirname + '/resources/scss/vendors/price_filter.scss'
                ],
                dependOn: 'frontend'
            }
        },
        output: {
            path: path.resolve(__dirname, 'public/vendors'),
            filename: 'js/[name].[contenthash].js',
        },
        module: {
            rules: [
                jsRule,
                scssRule
            ]
        },
        plugins: [
            new CleanWebpackPlugin(),
            new WebpackManifestPlugin({
                publicPath: 'public/vendors/'
            }),
            new CopyPlugin({
                patterns: [
                    {from: "node_modules/@fancyapps/fancybox/dist/jquery.fancybox.min.css", to: "css/fancybox.min.css"},
                    {
                        from: "node_modules/jquery-ui-timepicker-addon/dist/jquery-ui-timepicker-addon.min.css",
                        to: "css/jquery-theme/timepicker.css"
                    },
                ],
                options: {
                    concurrency: 100,
                },
            }),
        ],
        optimization: optimizationParams,
    },
    {
        name: 'fonts',
        entry: {},
        output: {
            path: path.resolve(__dirname, 'public'),
        },
        plugins: [
            new CopyPlugin({
                patterns: [
                    {from: "resources/fonts", to: "fonts"},
                ],
                options: {
                    concurrency: 100,
                },
            }),
        ],
    }
];