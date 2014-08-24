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
  grunt.task.loadTasks('grunt-tasks/copy-index');

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
      '<%= build_dir %>/*',
      '<%= compile_dir %>/*'
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
      buildCopyAssets: {
        files: [
          {
            src: [ 'assets/**'],
            dest: '<%= build_dir %>',
            cwd: './<%= src_dir %>',
            expand: true
          }
        ]
      },
      compileCopyApp: {
        files: [
          {
            src: [ '!*.spec.js', '**/*.js', '!**.js' ],
            dest: '<%= compile_dir %>',
            cwd: './<%= src_dir %>/app',
            expand: true
          }
       ]
      },
      compileCopyAssets: {
        files: [
          {
            src: [ 'assets/**'],
            dest: '<%= compile_dir %>',
            cwd: '<%= build_dir %>',
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
          '<%= src_dir %>/app/**/*.js', '<%= src_dir %>/**/*.tpl.html', '<%= src_dir %>/index.*', 'Gruntfile.js', '!<%= src_dir %>/**/*.spec.js'
        ],
        tasks: [ 'jshint', 'build' ]
      },
      spec: {
        options: {

        },
        files: [
          '<%= src_dir %>/**/*.spec.js'
        ],
        tasks: [ 'jshint' ]
      }
    },
    html2js: {
      options: {
        base: '<%= src_dir %>/app',
        module: 'app-templates'
      },
      main: {
        src: ['<%= src_dir %>/**/*.tpl.html'],
        dest: '<%= build_dir %>/src/app/app-templates.js'
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
        '<%= src_dir %>/app/**/*.js'
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
          baseUrl: "./<%= src_dir %>/app",
          mainConfigFile: "<%= src_dir %>/app/main.js",
          out: "<%= compile_dir %>/ams-min.js"

        }
      }
    },
    //copy index.php to build/bin and replace the js reference variable in it
    copyIndex: {
      compile: {
        dir: '<%= compile_dir %>'
      },
      build: {
        dir: '<%= build_dir %>'
      }

    },
    concat: {
      buildCss: {
        src: [ '<%= vendor_files.css %>', 'src/css/app.css'],
        dest: '<%= build_dir %>/assets/all.css'
      },
      compileJs: {
        src: [ 'vendor/requirejs/require.js', '<%= compile_dir %>/ams-min.js'],
        dest: '<%= compile_dir %>/ams-min.js'
      }
    },
    //Lint, compile and concat less/css
    recess: {
      build: {
        files: {
          '<%= build_dir %>/assets/all.css' : [ '<%= vendor_files.css %>', '<%= src_dir %>/css/app.css' ]
        },
        options: {
          compile: true,
          compress: false,
          noUnderscores: false,
          noIDs: false,
          zeroUnits: false
        }
      },
      compile: {
        files: {
          '<%= compile_dir %>/assets/all.css' : [ '<%= build_dir %>/assets/all.css' ]
        },
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
          root: '<%= build_dir %>',
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
  grunt.registerTask('lint', [ 'jshint' ]);
  grunt.registerTask('watchHint', [ 'watch:hint' ]);
  grunt.registerTask('copyBuild', [ 'copy:buildCopyApp', 'copy:buildVendor_js', 'copy:buildVendor_css', 'copy:buildVendor_assets', 'copy:buildCopyAssets', 'copyIndex:build' ]);
  grunt.registerTask('copyCompile', ['copy:compileCopyApp', 'copy:compileCopyAssets', 'copyIndex:compile' ]);
  grunt.registerTask('build', [ 'clean', 'copyBuild', 'html2js', 'concat:buildCss' ]);
  grunt.registerTask('compile', [ 'requirejs', 'concat:compileJs', 'copyCompile' ]);
  grunt.registerTask('default', [ 'clean', 'build', 'compile' ]);

};