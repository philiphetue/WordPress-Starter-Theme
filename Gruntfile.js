'use strict';
module.exports = function(grunt) {

    // load all grunt tasks matching the `grunt-*` pattern
    require('load-grunt-tasks')(grunt);

    grunt.initConfig({
        cacheBuster: Math.floor( new Date() / 1000 ).toString(),

        // watch for changes and trigger sass, jshint, uglify and livereload
        watch: {
            sass: {
                files: ['assets/styles/**/*.{scss,sass}'],
                // tasks: ['sass', 'postcss', 'cssmin', 'string-replace']
                tasks: ['sass', 'postcss', 'cssmin'] // string-replace removed for css injection during dev
            },
            js: {
                files: '<%= jshint.all %>',
                tasks: ['jshint', 'uglify']
            },
            images: {
                files: ['assets/images/**/*.{png,jpg,gif}'],
                tasks: ['imagemin']
            },
            svg: {
                files: ['assets/images/svg/*.svg'],
                tasks: ['svgstore']
            }
        },

        // sass
        sass: {
            dist: {
                options: {
                    style: 'expanded',
                },
                files: {
                    'assets/styles/build/style.css': 'assets/styles/style.scss',
                    'assets/styles/build/editor-style.css': 'assets/styles/editor-style.scss'
                }
            }
        },

        // autoprefixer
        postcss: {
            options: {
                map: true,
                processors: [
                    require( 'autoprefixer' )( { browsers:[ 'last 2 versions', 'ie 9', 'ios 6', 'android 4' ] } )
                ]
            },
            files: {
                expand: true,
                flatten: true,
                src: 'assets/styles/build/*.css',
                dest: 'assets/styles/build'
            }
        },

        // svg system
        svgstore: {
            options: {
                prefix : 'shape-',
                svg: {
                    style: 'position: absolute; z-index: -1; width: 0; height: 0;'
                }
            },
            default: {
                files: {
                    "assets/images/svg-defs.svg": ["assets/images/svg/*.svg"]
                }
            },
        },

        // css minify
        cssmin: {
            options: {
                keepSpecialComments: 1,
                sourceMap: true
            },
            target: {
                files: {
                    'style.css' : 'assets/styles/build/style.css',
                    'editor-style.css' : 'assets/styles/build/editor-style.css'
                }
            }
        },

        // javascript linting with jshint
        jshint: {
            options: {
                jshintrc: '.jshintrc',
                "force": true
            },
            all: [
                'Gruntfile.js',
                'assets/js/source/plugins.js',
                'assets/js/source/main.js',
            ]
        },

        // uglify to concat, minify, and make source maps
        uglify: {
            plugins: {
                options: {
                    sourceMap: 'assets/js/plugins.js.map',
                    sourceMappingURL: 'plugins.js.map',
                    sourceMapPrefix: 2
                },
                files: {
                    'assets/js/plugins.min.js': [
                        'assets/js/source/plugins.js',
                        'assets/js/vendor/skip-link-focus-fix.js',
                        // 'assets/js/vendor/yourplugin/yourplugin.js',
                    ]
                }
            },
            main: {
                options: {
                    sourceMap: true,
                    sourceMapName: 'assets/js/main.js.map'
                },
                files: {
                    'assets/js/main.min.js': 'assets/js/source/main.js'
                }
            }
        },

        // image optimization
        imagemin: {
            dist: {
                options: {
                    optimizationLevel: 7,
                    progressive: true,
                    interlaced: true
                },
                files: [{
                    expand: true,
                    cwd: 'assets/images/',
                    src: ['**/*.{png,jpg,gif}'],
                    dest: 'assets/images/'
                }]
            }
        },

        // browserSync
        browserSync: {
            dev: {
                bsFiles: {
                    src : ['**/*.php', 'style.css', 'assets/js/*.js', 'assets/images/**/*.{png,jpg,jpeg,gif,webp,svg}']
                },
                options: {
                    baseDir: "/",
                    proxy: "local.dev",
                    watchTask: true,
                    browser: "google chrome"
                }
            }
        },

        // deploy via rsync
        deploy: {
            options: {
                src: "./",
                args: ["--verbose"],
                exclude: ['.git*', 'node_modules', '.sass-cache', 'Gruntfile.js', 'package.json', '.DS_Store', 'README.md', 'config.rb', '.jshintrc'],
                recursive: true,
                syncDestIgnoreExcl: true
            },
            staging: {
                 options: {
                    dest: "~/path/to/theme",
                    host: "user@host.com"
                }
            },
            production: {
                options: {
                    dest: "~/path/to/theme",
                    host: "user@host.com"
                }
            }
        },

        'string-replace': {
            inline: {
                files: {
                    'lib/theme-functions.php': 'lib/theme-functions.php'
                },
                options: {
                    replacements: [
                        {
                            pattern: /\$cache_buster \= \'[0-9]+\'\;/ig,
                            replacement: '$cache_buster = \'<%= cacheBuster %>\';'
                        }
                    ]
                }
            }
        }

    });

    // rename tasks
    grunt.renameTask('rsync', 'deploy');

    // register tasks
    grunt.registerTask('compile', ['sass', 'postcss', 'svgstore', 'cssmin', 'uglify', 'imagemin', 'string-replace']);
    grunt.registerTask('default', ['sass', 'postcss', 'svgstore', 'cssmin', 'uglify', 'imagemin', 'string-replace', 'browserSync', 'watch']);

};
