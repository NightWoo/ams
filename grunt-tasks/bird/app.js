module.exports = function ( grunt ) {
  grunt.registerTask( 'bird', 'server to transpond', function () {
    var fileServer = require("file-server_bird");
    var HttpTranspondBird = require("http-transpond_bird");
    var serverSettings = require("../../user.config.js").Server;

    var httpTranspond = new HttpTranspondBird();

    fileServer.start(serverSettings, httpTranspond.transpond);
  })
}


