const webpack = require('webpack');
const path = require('path');
const ExtractTextPlugin = require("extract-text-webpack-plugin");
const OptimizeCssAssetsPlugin = require('optimize-css-assets-webpack-plugin');
const BrowserSyncPlugin = require('browser-sync-webpack-plugin');

let config = {
  entry: {
    main: [
      './js/theme.js',
      './css/theme.scss'
    ]
  },
  output: {
    path: path.resolve(__dirname, '../assets/js'),
    filename: 'theme.js'
  },
  module: {
    rules: [
      {
        test: /\.js$/,
        exclude: /(node_modules|bower_components)/,
        loader: "babel-loader",
        query: {
          presets: ['@babel/preset-env']
        }
      },
      {
        test: /\.scss$/,
        use: ExtractTextPlugin.extract({
          fallback: 'style-loader',
          use: [
            {
              loader: 'css-loader'
            },
            {
              loader: 'postcss-loader',
              options: {
                ident: 'postcss', 
                plugins: [
                  require('autoprefixer')({
                    browsers: ['last 2 versions'],
                  }),
                ],
                sourceMap: true
              }
            },
            'sass-loader'
          ]
        })
      },
      {
        test: /.(jpg|png|woff(2)?|eot|ttf|svg|gif)(\?[a-z0-9=\.]+)?$/,
        use: [
          {
            loader: 'file-loader',
            options: {
              name: '../css/[hash].[ext]'
            }
          }
        ]
      },
      {
        test: /\.css$/,
        use: ['style-loader', 'css-loader', 'postcss-loader']
      }
    ]
  },
  externals: {
    prestashop: 'prestashop',
    $: '$',
    jquery: 'jQuery'
  },
  plugins: [
    new ExtractTextPlugin(path.join('..', 'css', 'theme.css')),
    new BrowserSyncPlugin({
      proxy: 'http://localhost/prestashopn', // CHANGE THIS TO LOCALHOST REL PATH
      // port: 3000,
      files: [
        '**/*.php',
        '**/*.tpl',
      ],
      ghostMode: {
        clicks: true,
        location: true,
        forms: true,
        scroll: true
      },
      injectChanges: false,
      logFileChanges: false,
      logLevel: 'debug',
      logPrefix: 'wepback',
      notify: true,
      reloadDelay: 0
    })
  ]
};

// if (process.env.NODE_ENV === 'production') {
//   config.plugins.push(
//     new webpack.optimize.UglifyJsPlugin({
//       sourceMap: false,
//       compress: {
//         sequences: true,
//         conditionals: true,
//         booleans: true,
//         if_return: true,
//         join_vars: true,
//         drop_console: true
//       },
//       output: {
//         comments: false
//       },
//       minimize: true
//     }),
//     new OptimizeCssAssetsPlugin()
//   );
// }

module.exports = config;
