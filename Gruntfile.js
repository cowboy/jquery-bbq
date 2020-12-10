module.exports = function(grunt) {
  grunt.initConfig({
    pkg: grunt.file.readJSON('package.json'),
    uglify: {
      options: {output: {comments: /^!/}},
      build: {files: {'jquery.ba-bbq.min.js': 'jquery.ba-bbq.js'}},
    }
  });
  grunt.loadNpmTasks('grunt-contrib-uglify');
  grunt.registerTask('default', ['uglify']);
};
