/**
 * This file/module contains all configuration for the build process.
 */
module.exports = {
  /**
   * The `build_dir` folder is where our projects are compiled during
   * development and the `compile_dir` folder is where our app resides once it's
   * completely built.
   */
  src_dir: 'src',
  build_dir: 'build',
  compile_dir: 'bin',
  vendor_dir: 'vendor',
  vendor_files: {
    js: [
      'vendor/angular/angular.min.js',
      'vendor/angular-ui-router/angular-ui-router.min.js',
      'vendor/angular-ui-bootstrap/ui-bootstrap-tpls.min.js',
      'vendor/angular-couch-potato/angular-couch-potato.js',
      'vendor/angular-loading-bar/loading-bar.min.js',
      'vendor/requirejs/require.js',
      'vendor/requirejs/domReady.js',
      'vendor/requirejs/r.js'
    ],
    css: [
      'vendor/angular-loading-bar/loading-bar.min.css',
      'vendor/bootstrap/css/bootstrap.min.css',
      'vendor/font-awesome/css/font-awesome.min.css'
    ],
    assets: [
      'vendor/bootstrap/fonts/*',
      'vendor/font-awesome/fonts/*'
    ]
  },

  /**
   * [TranspondRules description]
   * @type {Object}
   */
  //bird-静态服务器配置，可同时配置多个，域名需host到127.0.0.1
  Server: {
    // "80": {
    //     //静态文件根目录
    //     "basePath": "D:/workspace/hrlms"
    //     //忽略的静态文件请求，与此正则匹配的请求将直接走转发规则（可选配置）
    //     //,"ignoreRegExp":  /\/js\/urls\.js/g
    // },
    "8898": {
      "basePath" : "build"
    },
  }
  //bird-转发规则——静态服务器没有响应的或者忽略的请求将根据一下规则转发
  // TranspondRules: {
  //   "8585": {
  //       //目标服务器的ip和端口，域名也可，但注意不要被host了
  //       targetServer: {
  //           "host": "10.44.67.14",//rd app10
  //           "port": "8875"
  //           // "host": "10.44.87.31",//fe
  //           // "port": "8875"
  //           // "host": "10.48.54.15", //QA app10
  //           // "port": "8875"
  //       },
  //       //特殊请求转发，可选配置，内部的host、port和attachHeaders为可选参数
  //       regExpPath: {
  //          "js" : {
  //               "host": "127.0.0.1",
  //               "port": "8585",
  //               "path": "js"
  //           }
  //           // ,
  //           // "platform/rs/teamModule/courseList?teamId=1" : {
  //           //     host: "10.44.67.14"
  //           //     port : "8045",
  //           //     path : "platform/rs/teamModule/courseList?teamId=9"
  //           // }
  //       }
  //   },
  //   "ajaxOnly": false
  // }
}