module.exports = function ( grunt ) {
  // body...
  grunt.loadNpmTasks('grunt-contrib-copy');
  grunt.loadNpmTasks('grunt-contrib-clean');
  grunt.loadNpmTasks('grunt-contrib-watch');
  grunt.loadNpmTasks('grunt-contrib-jshint');
  grunt.loadNpmTasks('grunt-html2js');
  grunt.loadNpmTasks('grunt-karma');
  grunt.loadNpmTasks('grunt-contrib-requirejs');
  grunt.loadNpmTasks('grunt-contrib-concat');
  grunt.loadNpmTasks('grunt-recess');
  grunt.loadNpmTasks('grunt-http-server');
  grunt.task.loadTasks('grunt-tasks/bird');

  /**
   * Load in our build configuration file.
   */
  var userConfig = require( './user.config.js' );

  /**
   * This is the configuration object Grunt uses to give each plugin its
   * instructions.
   */
  var taskConfig = {
    /**
     * We read in our `package.json` file so we can access the package name and
     * version. It's already there, so we don't repeat ourselves here.
     */
    pkg: grunt.file.readJSON("package.json"),

    clean: [
      '<%= build_dir %>',
      '<%= bin_dir %>'
    ],
    copy: {
      buildCopyApp: {
        files: [
          {
            src: [ 'src/**'  , '!src/app/**/*.tpl.html', '!src/app/**/*.spec.js', '!src/app/app-templates.js'  ],
            dest: '<%= build_dir %>/',
            cwd: '.',
            expand: true
          }
       ]
      },
      buildVendor_js: {
        files: [
          {
            src: [ '<%= vendor_files.js %>' ],
            dest: '<%= build_dir %>',
            cwd: '.',
            expand: true
          }
        ]
      },
      buildVendor_css: {
        files: [
          {
            src: [ '<%= vendor_files.css %>' ],
            dest: '<%= build_dir %>',
            cwd: '.',
            expand: true
          }
        ]
      },
      buildVendor_assets: {
        files: [
          {
            src: [ '<%= vendor_files.assets %>' ],
            dest: '<%= build_dir %>',
            cwd: '.',
            expand: true
          }
        ]
      },
      // buildIndex: {
      //   files: [
      //     {
      //       src: ['src/index.php'],
      //       dest: '<%= build_dir %>',
      //       cwd: '.',
      //       expand: true,
      //       flatten: true
      //     }
      //   ]
      // },
      buildCopyAssets: {
        files: [
          {
            src: [ 'assets/**'],
            dest: '<%= build_dir %>',
            cwd: './src',
            expand: true
          }
        ]
      },
      compileCopyApp: {
        files: [
          {
            src: [ '!*.spec.js', '**/*.js', '!**.js' ],
            dest: 'bin/',
            cwd: './src/app',
            expand: true
          }
       ]
      },
      compileCopyAssets: {
        files: [
          {
            src: [ 'assets/**'],
            dest: 'bin',
            cwd: 'build',
            expand: true
          }
        ]
      }
    },
    watch: {
      hint: {
        options: {
          livereload: true
        },
        files: [
          'src/app/**/*.js', 'src/**/*.tpl.html', 'src/index.*', 'Gruntfile.js', '!src/**/*.spec.js'
        ],
        // tasks: [ 'clean', 'html2js', 'copy', 'jshint']
        tasks: ['jshint', 'build']
      },
      spec: {
        options: {

        },
        files: [
          'src/**/*.spec.js'
        ],
        tasks: [ 'jshint' ]
      }
    },
    html2js: {
      options: {
        base: 'src/app',
        module: 'app-templates'
      },
      main: {
        build: {
          src: ['src/**/*.tpl.html'],
          dest: '<%= build_dir %>/src/app/app-templates.js'
        },
        bin: {
          src: ['src/**/*.tpl.html'],
          dest: '<%= bin_dir %>/src/app/app-templates.js'
        }
      }
    },
    karma: {
      unit: {
        configFile: 'karma.conf.js'
      }
    },
    /**
     * `jshint` 定义了那些代码将会被执行代码检测（jslint），定义了代码检测的规则
     * 注意：这里定义的规则只允许在项目开始前修改，而且不推荐修改. 如需修改，修改options里的参数
     × options的配置参见 http://www.jshint.com/docs/
     * js代码规范参见：https://github.com/rwaldron/idiomatic.js/blob/master/readme.md

     ## why eqeqeq: http://javascriptweblog.wordpress.com/2011/02/07/truth-equality-and-javascript/
     */
    jshint: {
      src: [
        'src/app/**/*.js'
      ],
      // test: [
      //   '<%= app_files.jsunit %>'
      // ],
      gruntfile: [
        'Gruntfile.js'
      ],
      options: {
        curly: true,
        immed: true,   //
        noarg: true,   //禁止使用arguments.caller and arguments.callee
        sub: true, //允许使用 $scope['name'] ,而不仅仅$scope.name
        eqnull: true, //允许使用 == null
        eqeqeq: true,
        trailing: true
      },
      globals: {}
    },
    requirejs: {
      compile: {
        options: {
          name: 'main',
          baseUrl: "./src/app",
          mainConfigFile: "src/app/main.js",
          out: "bin/ams-min.js"

        }
      }
    },
    copyIndex: {
      compile: {
        dir: '<%= bin_dir %>'
      },
      build: {
        dir: '<%= build_dir %>'
      }

    },
    bootstrap: {
        build: {
          src: []
        }
    },
    concat: {
      compile: {
        src: [ 'vendor/requirejs/require.js', 'bin/ams-min.js'],
        dest: 'bin/ams-min.js'
      },
      buildCss: {
        src: [ '<%= vendor_files.css %>', 'src/**/*.css'],
        dest: 'build/assets/all.css'
      }
    },
    recess: {
      build: {
        src: ['<%= vendor_files.css %>', 'src/css/app.css' ],
        dest: 'build/assets/all.css',
        options: {
          compile: true,
          compress: false,
          noUnderscores: false,
          noIDs: false,
          zeroUnits: false
        }
      },
      compile: {
        src: [ '<%= recess.build.dest %>' ],
        dest: '<%= recess.build.dest %>',
        options: {
          compile: true,
          compress: true,
          noUnderscores: false,
          noIDs: false,
          zeroUnits: false
        }
      }
    },
    "http-server": {
        dev: {
          // the server root directory
          root: 'src',
          port: 8282,
          host: "127.0.0.1",

          cache: 0,
          showDir : true,
          autoIndex: true,
          defaultExt: "html",

          //wait or not for the process to finish
          runInBackground: false
        }
    }
  };
  grunt.initConfig( grunt.util._.extend( taskConfig, userConfig ) );
  grunt.registerTask('test', [ 'karma' ]);
  grunt.registerTask('default', ['clean', 'build', 'compile']);
  grunt.registerTask('lint', ['jshint']);
  grunt.registerTask('watchHint', ['watch:hint']);
  grunt.registerTask('copyBuild', ['copy:buildCopyApp', 'copy:buildVendor_js', 'copy:buildVendor_css', 'copy:buildVendor_assets', 'copy:buildCopyAssets', 'copyIndex:build']);
  grunt.registerTask('copyCompile', ['copy:compileCopyApp', 'copy:compileCopyAssets', 'copyIndex:compile'])
  grunt.registerTask('build', ['copyBuild', , 'html2js', 'concat:buildCss' ]);
  grunt.registerTask('compile', ['requirejs', 'concat:compile', 'copyCompile']);

  //replace the js reference variable in index.php
  grunt.registerMultiTask('copyIndex', 'replace js', function () {
    var dir = this.data.dir;
    grunt.file.copy('src/index.php', dir + '/index.php', {
      process: function ( contents, path ) {
        if ( dir === 'build') {
          return grunt.template.process( contents, {
            data: {
              compiledJs: '<script src="vendor/requirejs/require.js" data-main="src/app/main.js" ></script>'
            }
          });
        }
        return grunt.template.process( contents, {
          data: {
            compiledJs: '<script src="ams-min.js"></script>'
          }
        });
      }
    });
  });
  /*below for test*/
  // grunt.initConfig({
  //   log: {
  //     foo: [1, 2, 3],
  //     bar: 'hello world',
  //     baz: false
  //   }
  // });

  // grunt.registerMultiTask('log', 'Log stuff.', function() {
  //   grunt.log.writeln(this.target + ': ' + this.data);
  // });
};