module.exports = function(grunt) {

  // 1. All configuration goes here
  grunt.initConfig({
    pkg: grunt.file.readJSON('package.json'),

    sass: {
        options: {
            sourceMap: true
        },
        dist: {
            files: {
                'public/css/calendar.css': 'public/sass/calendar.scss'
            }
        }
    },

    concat: {
      // 2. Configuration for concatinating files goes here.
      dist: {
        src: [
          'bower_components/jquery/dist/jquery.min.js',
          'bower_components/moment/min/moment.min.js',
          'bower_components/bootstrap-sass/assets/javascripts/bootstrap.min.js',
          'bower_components/datetimepicker/jquery.datetimepicker.js'
        ],
        dest: 'public/js/build/production.js'
      }
    },

    uglify: {
      build: {
        src: 'public/js/build/production.js',
        dest: 'public/js/build/production.min.js'
      }
    }

  });

  // 3. Where we tell Grunt we plan to use this plug-in.
  grunt.loadNpmTasks('grunt-contrib-concat');
  grunt.loadNpmTasks('grunt-contrib-uglify');
  grunt.loadNpmTasks('grunt-sass');

  // 4. Where we tell Grunt what to do when we type "grunt" into the terminal.
  grunt.registerTask('default', ['concat', 'uglify', 'sass']);

};
