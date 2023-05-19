const path = require('node:path');
const webpack = require('webpack');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const {ESBuildMinifyPlugin} = require('esbuild-loader');
const HtmlWebpackPlugin = require('html-webpack-plugin');

const config = (env, options) => {
    const isProduction = options.mode === 'production';
    const isServer = !!env['WEBPACK_SERVE'];

    const config = {
        mode: env?.mode ?? 'development',
        devtool: isProduction ? false : 'inline-source-map',
        resolve: {
            modules: ['node_modules'],
            extensions: ['.js', '.scss'],
        },
        entry: {
            app: [
                './js/app',
                './scss/app'
            ],
        },
        output: {
            path: isServer ? path.resolve(__dirname, '../') : path.resolve(__dirname, '../'),
            filename: `assets/js/[name].js`
        },
        module: {
            rules: [
                {
                    test: /\.jsx?$/,
                    loader: 'esbuild-loader'
                },
                {
                    test: /\.(sa|sc|c)ss$/,
                    use: [
                        MiniCssExtractPlugin.loader,
                        {
                            loader: 'css-loader',
                            options: {
                                sourceMap: true,
                                importLoaders: 2,
                                url: false,
                            }
                        },
                        {
                            loader: 'postcss-loader',
                            options: {
                                postcssOptions: {
                                    plugins: [
                                        [
                                            'autoprefixer'
                                        ],
                                    ],
                                },
                                sourceMap: true,
                            }
                        },
                        {
                            loader: 'sass-loader',
                            options: {
                                sassOptions: {
                                    outputStyle: 'expanded',
                                },
                                sourceMap: true
                            }
                        },
                    ]
                },

            ]
        },
        plugins: [
            new webpack.LoaderOptionsPlugin({
                minimize: true
            }),
            new MiniCssExtractPlugin({
                filename: 'assets/css/[name].css'
            }),
        ],
        optimization: {
            splitChunks: {
                cacheGroups: {
                    defaultVendors: {
                        chunks: 'initial',
                        name: 'vendors',
                        test: /[\\/]node_modules[\\/]/,
                        enforce: true,
                    },
                    default: {
                        minChunks: 2,
                        priority: -20,
                        reuseExistingChunk: true
                    }
                },
            }
        }
    };

    if (options['plugins']) {
        config.plugins = (config.plugins || []).concat(options['plugins']);
    }

    if (isProduction) {
        config.optimization.minimizer = [
            new ESBuildMinifyPlugin({
                target: 'es2016',
                css: true
            }),
        ];
    }

    return config;
};
module.exports = config;