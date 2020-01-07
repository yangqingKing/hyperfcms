module.exports = {
  publicPath: process.env.NODE_ENV === 'production' ? '/dzjadmin/' : '/',
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
        target: 'http://127.0.0.1:9501',
        changeOrigin: true,
        pathRewrite: {
          // '^/admin_api': 'admin_api'
        }
      }
    } 
  }

}
