module.exports = function ( grunt ) {
  //replace the js reference variable in index.php
  grunt.registerMultiTask('copyIndex', 'replace js', function () {
    var dir = this.data.dir;
    grunt.file.copy('src/index.php', dir + '/index.php', {
      process: function ( contents, path ) {
        if ( dir === 'build') {
          return grunt.template.process( contents, {
            data: {
              compiledJs: '<script src="vendor/requirejs/require.js" data-main="src/app/main.js" ></script>',
              compliedCss: '<link rel="stylesheet" href="assets/all.css">'
            }
          });
        }
        return grunt.template.process( contents, {
          data: {
            compiledJs: '<script src="ams-min.js"></script>',
            compliedCss: '<link rel="stylesheet" href="assets/all.css">'
          }
        });
      }
    });
  });
}


