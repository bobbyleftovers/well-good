module.exports = {
  productionSourceMap: false,
  publicPath: process.env.NODE_ENV === 'production'
    ? '/wp-content/plugins/wg-varnish/dist/'
    : 'https://localhost:8080/',
  outputDir: './dist',
  configureWebpack: {
    devServer: {
      contentBase: '/wp-content/plugins/wg-varnish/dist/',
      allowedHosts: ['well-good.lndo.site'],
      headers: {
        'Access-Control-Allow-Origin': '*'
      },
      public: 'localhost:8080',
      https: true,
      port: 8080
    },
    /* externals: {
            jquery: 'jQuery'
        }, */
    output: {
      filename: 'js/[name].js',
      chunkFilename: 'js/[name].js'
    }
  },
  css: {
    extract: {
      filename: 'css/[name].css',
      chunkFilename: 'css/[name].css'
    }
  }
}
