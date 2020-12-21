module.exports = {
  pages: {
    importer: {
      entry: 'src/importer.js'
    },
    maintenance: {
      entry: 'src/maintenance.js'
    },
    sitemap: {
      entry: 'src/sitemap.js'
    },
    'quick-edit': {
      entry: 'src/quick-edit.js'
    },
    'sitemap-front': {
      entry: 'src/sitemap-front.js'
    }
  },
  productionSourceMap: false,
  publicPath: process.env.NODE_ENV === 'production'
    ? '/wp-content/plugins/wg-redirections/dist/'
    : 'https://localhost:8085/',
  outputDir: './dist',
  configureWebpack: {
    devServer: {
      contentBase: '/wp-content/plugins/wg-redirections/dist/',
      allowedHosts: ['well-good.lndo.site'],
      headers: {
        'Access-Control-Allow-Origin': '*'
      },
      public: 'localhost:8085',
      https: true,
      port: 8085
    },
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
