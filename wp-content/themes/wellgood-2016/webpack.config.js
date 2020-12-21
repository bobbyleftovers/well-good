const webpack = require('webpack')
const path = require('path')
const MiniCssExtractPlugin = require('mini-css-extract-plugin')
const VueLoaderPlugin = require('vue-loader/lib/plugin')
const CriticalCss = require('./webpack.critical')
const WebpackOnBuildPlugin = require("on-build-webpack")
const tailwindcss = require('tailwindcss');
const { getArgs, iniFile, initialLogs, endLogs } = require('./webpack.helpers');
const { getBundles, allEntries } = require('./webpack.bundles');

process.args = getArgs();
const entries = getBundles(process);
initialLogs(entries);

var config = {
  mode: process.env.NODE_ENV === 'production' ? 'production' : 'development',
  devtool: process.env.NODE_ENV === 'production' ? false : 'eval',
  entry: entries,
  output: {
    path: path.join(__dirname, 'assets/'),
    filename: `[name]${process.env.NODE_ENV === "production" ? ".min" : ""}.js`,
    chunkFilename: '[name].js',
  },
  module: {
    rules: [
      {
        test: /\.vue$/,
        use: 'vue-loader'
      },
      {
        enforce: 'pre',
        test: /\.js$/,
        exclude: /node_modules/,
        loader: 'eslint-loader'
      },
      {
        test: /\.js$/,
        exclude: /node_modules/,
        use: {
          loader: 'babel-loader',
          options: {
            cacheDirectory: true
          }
        }
      },
      {
        test: /\/location-hub.js$/,
        exclude: /node_modules/,
        use: [
          'babel-loader',
          '@babel/preset-react'
        ]
      },
      {
        test: /\.scss$/,
        use: [
          process.env.NODE_ENV === 'production' ? MiniCssExtractPlugin.loader : 'vue-style-loader',
          'css-loader?importLoaders=1&minimize=1',
          {
            loader: 'postcss-loader',
            options: {
              sourceMap: true,
              ident: 'postcss',
              plugins: [
                tailwindcss('./tailwind.config.js'),
                require('autoprefixer'),
              ]
            }
          },
          {
            loader: 'sass-loader',
            options: {
              sourceMap: true,
              data: `
                @import "@scss/_variables.scss"; 
                @import "@scss/_mixins.scss";
                @import "@scss/_breakpoint.scss";
                @import '@scss/main-2020/_typography-extends.scss';
                `
            }
          }
        ]
      },
      {
        test: /\.css$/,
        use: [
          process.env.NODE_ENV === 'production' ? MiniCssExtractPlugin.loader : 'vue-style-loader',
          'css-loader?importLoaders=1&minimize=1',
          {
            loader: 'postcss-loader',
            options: {
              sourceMap: true,
              ident: 'postcss',
              plugins: [
                tailwindcss('./tailwind.config.js'),
                require('autoprefixer'),
              ]
            }
          }
        ]
      },
      {
        test: /\.(eot|ttf|woff|woff2)$/,
        use: [
          {
            loader: 'file-loader',
            options: {
              name: '[name].[ext]',
              outputPath: 'fonts/',
              emitFile: false
            }
          }
        ]
      },
      {
        test: /\.(png|jpe?g|gif|svg)$/,
        loader: 'file-loader?name=/img/[name].[ext]&emitFile=false'
      }
    ]
  },
  resolve: {
    alias: {
      'lib': path.resolve(__dirname, 'src/js/lib'),
      '@modules': path.resolve(__dirname, 'modules'),
      'assets': path.resolve(__dirname, 'assets'),
      '@src': path.resolve(__dirname, 'src'),
      '@scss': path.resolve(__dirname, 'src/scss'),
      '@js': path.resolve(__dirname, 'src/js'),
      'vue$': 'vue/dist/vue.esm.js'
    }
  },
  externals: {
    history: 'History',
    parselyCallbacks: 'parselyCallbacks'
  },
  plugins: [
    new VueLoaderPlugin(),
    new webpack.ProvidePlugin({
      $: 'jquery',
      jQuery: 'jquery',
      'window.jQuery': 'jquery',
      'window.$': 'jquery',
      Flickity: 'flickity',
      'window.Flickity': 'flickity',
      Cookies: 'js-cookie',
      'window.Cookies': 'js-cookie'
    }),
    new MiniCssExtractPlugin({
      filename: "[name].min.css"
    }),
    new WebpackOnBuildPlugin(function (stats) {

      //critical
      if (process.env.NODE_ENV === 'production' && process.args.CRITICAL) {
        CriticalCss(process, stats.hash)
      }

      //share vars with php
      var data = process.args;
      data.SCRIPTS_HASH = stats.hash;

      iniFile(data);

      if(process.env.NODE_ENV === 'production') endLogs();

    })
  ]
}

//build ini vars file on start dev
if(process.env.NODE_ENV == 'development'){
  process.args.PROXY_URL = 'https://localhost:3000'
  iniFile(process.args);
}

module.exports = config