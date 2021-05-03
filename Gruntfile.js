'use strict';
module.exports = function(grunt) {

    // load all grunt tasks matching the `grunt-*` pattern
    require('load-grunt-tasks')(grunt);

    grunt.initConfig({
        cacheBuster: Math.floor( new Date() / 1000 ).toString(),
        config: grunt.file.readJSON( 'gruntconfig.json' ),

        // watch for changes and trigger sass, jshint, terser and livereload
        watch: {
            sass: {
                files: ['assets/styles/**/*.{scss,sass}'],
                // tasks: ['sass', 'postcss', 'cssmin', 'string-replace']
                tasks: ['sass', 'postcss', 'cssmin'] // string-replace removed for css injection during dev
            },
            js: {
                files: '<%= jshint.all %>',
                tasks: ['jshint', 'terser']
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
                    'assets/styles/build/editor-style.css': 'assets/styles/editor-style.scss',
                    'assets/styles/build/admin-style.css': 'assets/styles/admin-style.scss'
                }
            }
        },

        // autoprefixer
        postcss: {
            options: {
                map: {
                    inline: false,
                },
                processors: [
                    require( 'autoprefixer' )
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
                    'editor-style.css' : 'assets/styles/build/editor-style.css',
                    'admin-style.css' : 'assets/styles/build/admin-style.css'
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

        // terser to concat, minify, and make source maps
        terser: {
            plugins: {
                options: {
                    // sourceMap: 'assets/js/plugins.js.map',
                    // sourceMappingURL: 'plugins.js.map',
                    // sourceMapPrefix: 2
                    sourceMap: true,
                },
                files: {
                    'assets/js/plugins.min.js': [
                        'assets/js/source/plugins.js',
                        'assets/js/vendor/skip-link-focus-fix.js',
                        'assets/js/vendor/acf-google-maps-helpers.js',
                        'node_modules/waypoints/lib/noframework.waypoints.js'
                        // 'assets/js/vendor/yourplugin/yourplugin.js',
                    ]
                }
            },
            main: {
                options: {
                    sourceMap: true,
                    // sourceMapName: 'assets/js/main.js.map'
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
                    proxy: "<%= config.localUrl %>",
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
                exclude: ['.git*', 'node_modules', '.sass-cache', 'Gruntfile.js', 'package.json', '.DS_Store', 'README.md', 'config.rb', '.jshintrc', 'package-lock.json', 'gruntconfig.json', 'gruntconfig.json.example', 'assets/styles', 'assets/js/source', 'assets/js/vendor' ],
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
    grunt.registerTask('compile', ['sass', 'postcss', 'svgstore', 'cssmin', 'terser', 'imagemin', 'string-replace']);
    grunt.registerTask('default', ['sass', 'postcss', 'svgstore', 'cssmin', 'terser', 'imagemin', 'string-replace', 'browserSync', 'watch']);

};
