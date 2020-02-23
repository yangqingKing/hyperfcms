module.exports = {
  publicPath: process.env.NODE_ENV === 'production' ?  process.env.VUE_APP_BASE_URL : '/',
  configureWebpack: {
    resolve: {
      alias: {
        '@public': '@/../public'
      }
    }
  },
  css: {
    loaderOptions: {

    }
  },

  devServer: {
    proxy: {
      '/admin_api': {
        target: 'http://localhost:9501',
        // target: 'http://demo.hyperfcms.com',
        changeOrigin: true,
        pathRewrite: {
          // '^/admin_api': 'admin_api'
        }
      }
    } 
  }

}
