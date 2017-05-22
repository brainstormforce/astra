module.exports = function (grunt) {
    'use strict';
    // Project configuration
    var autoprefixer = require('autoprefixer');
    var flexibility = require('postcss-flexibility');

    grunt.initConfig({
            pkg: grunt.file.readJSON('package.json'),

            rtlcss: {
                options: {
                    // rtlcss options
                    config: {
                        preserveComments: true,
                        greedy: true
                    },
                    // generate source maps
                    map: false
                },
                dist: {
                    files: [
                         {
                            expand: true,
                            cwd: 'assets/css/unminified/',
                            src: [
                                    '*.css',
                                    '!*-rtl.css',
                                    '!customizer-controls.css',
                                    '!font-awesome.css',
                                    '!astra-fonts.css',
                                ],
                            dest: 'assets/css/unminified',
                            ext: '-rtl.css'

                        },
                        {
                            expand: true,
                            cwd: 'assets/css/unminified/site-compatible',
                            src: [
                                    '*.css',
                                    '!*-rtl.css',
                                    '!customizer-controls.css',
                                    '!font-awesome.css',
                                    '!astra-fonts.css',
                                ],
                            dest: 'assets/css/unminified/site-compatible',
                            ext: '-rtl.css'

                        },
                    ]
              	}
            },

            sass: {
                options: {
                    sourcemap: 'none',
                    outputStyle: 'expanded',
                    linefeed: 'lf',
                },
                dist: {
                    files: [

                        /*{
                        'style.css': 'sass/style.scss'
                        },*/

                        /* Editor Style */
                        {
                            'assets/css/unminified/editor-style.css': 'sass/editor-style.scss',
                            'inc/customizer/custom-controls/dimension/dimension.css': 'inc/customizer/custom-controls/dimension/dimension.scss',
                            'inc/customizer/custom-controls/responsive/responsive.css': 'inc/customizer/custom-controls/responsive/responsive.scss',
                            'inc/customizer/custom-controls/divider/divider.css': 'inc/customizer/custom-controls/divider/divider.scss',
                            'inc/customizer/custom-controls/radio-image/radio-image.css': 'inc/customizer/custom-controls/radio-image/radio-image.scss',
                            'inc/customizer/custom-controls/slider/slider.css': 'inc/customizer/custom-controls/slider/slider.scss',
                            'inc/customizer/custom-controls/sortable/sortable.css': 'inc/customizer/custom-controls/sortable/sortable.scss',
                            'inc/customizer/custom-controls/spacing/spacing.css': 'inc/customizer/custom-controls/spacing/spacing.scss',
                            'inc/customizer/custom-controls/toggle/toggle.css': 'inc/customizer/custom-controls/toggle/toggle.scss',
                        },

                        /* Common Style */
                        {
                            expand: true,
                            cwd: 'sass/',
                            src: ['style.scss'],
                            dest: 'assets/css/unminified',
                            ext: '.css'
                        },
                         /* Compatibility */
                        {
                            expand: true,
                            cwd: 'sass/site/site-compatible/',
                            src: ['**.scss'],
                            dest: 'assets/css/unminified/site-compatible',
                            ext: '.css'
                        },
                        /* Blog Layouts */
                        {
                            expand: true,
                            cwd: 'sass/site/blog/blog-layouts/blog-styles/',
                            src: ['**.scss'],
                            dest: 'assets/css/unminified',
                            ext: '.css'
                        },
                    ]
                }
            },

            postcss: {
                options: {
                    map: false,
                    processors: [
                        flexibility,
                        autoprefixer({
                            browsers: [
                                'Android >= 2.1',
                                'Chrome >= 21',
                                'Edge >= 12',
                                'Explorer >= 7',
                                'Firefox >= 17',
                                'Opera >= 12.1',
                                'Safari >= 6.0'
                            ],
                            cascade: false
                        })
                    ]
                },
                style: {
                    expand: true,
                    src: [
                        'assets/css/unminified/style.css'
                    ]
                }
            },

            uglify: {
                js: {
                    files: [{ // all .js to min.js
                        expand: true,
                        src: [
                            '**.js'
                        ],
                        dest: 'assets/js/minified',
                        cwd: 'assets/js/unminified',
                        ext: '.min.js'
                    }]
                }
            },

            cssmin: {
                options: {
                    keepSpecialComments: 0
                },
                css: {
                    files: [{ //.css to min.css
                        expand: true,
                        src: [
                            '**/*.css'
                        ],
                        dest: 'assets/css/minified',
                        cwd: 'assets/css/unminified',
                        ext: '.min.css'
                    }]
                }
            },

            copy: {
                main: {
                    options: {
                        mode: true
                    },
                    src: [
                        '**',
                        '!node_modules/**',
                        '!build/**',
                        '!css/sourcemap/**',
                        '!.git/**',
                        '!bin/**',
                        '!.gitlab-ci.yml',
                        '!bin/**',
                        '!tests/**',
                        '!phpunit.xml.dist',
                        '!phpcs.ruleset.xml',
                        '!*.sh',
                        '!*.map',
                        '!Gruntfile.js',
                        '!package.json',
                        '!.gitignore',
                        '!phpunit.xml',
                        '!wpml-config.xml',
                        '!README.md',
                        '!sass/**',
                        '!codesniffer.ruleset.xml',
                    ],
                    dest: 'astra/'
                },
                org: {
                    options: {
                        mode: true
                    },
                    src: [
                        '**',
                        // Admin directory only consists of graupi so this is being ignored.
                        '!admin/**',
                        '!class-brainstorm-update-astra-theme.php',
                        '!node_modules/**',
                        '!build/**',
                        '!css/sourcemap/**',
                        '!.git/**',
                        '!bin/**',
                        '!.gitlab-ci.yml',
                        '!bin/**',
                        '!tests/**',
                        '!phpunit.xml.dist',
                        '!phpcs.ruleset.xml',
                        '!*.sh',
                        '!*.map',
                        '!Gruntfile.js',
                        '!package.json',
                        '!.gitignore',
                        '!phpunit.xml',
                        '!wpml-config.xml',
                        '!README.md',
                        '!sass/**',
                        '!codesniffer.ruleset.xml',
                    ],
                    dest: 'astra/'
                }
            },

            compress: {
                main: {
                    options: {
                        archive: 'astra.zip',
                        mode: 'zip'
                    },
                    files: [
                        {
                            src: [
                                './astra/**'
                            ]

                        }
                    ]
                },
                org: {
                    options: {
                        archive: 'astra.zip',
                        mode: 'zip'
                    },
                    files: [
                        {
                            src: [
                                './astra/**'
                            ]

                        }
                    ]
                }
            },

            clean: {
                main: ["astra"],
                zip: ["astra.zip"]

            },

            makepot: {
                target: {
                    options: {
                        domainPath: '/',
                        potFilename: 'languages/astra.pot',
                        potHeaders: {
                            poedit: true,
                            'x-poedit-keywordslist': true
                        },
                        type: 'wp-theme',
                        updateTimestamp: true
                    }
                }
            },

            addtextdomain: {
                options: {
                    textdomain: 'astra',
                },
                target: {
                    files: {
                        src: [
                        	'*.php',
                        	'**/*.php',
                        	'!node_modules/**',
                        	'!php-tests/**',
                        	'!bin/**',
                        	'!admin/bsf-core/**'
                        ]
                    }
                }
            }

        }
    );

    // Load grunt tasks
    grunt.loadNpmTasks('grunt-rtlcss');
    grunt.loadNpmTasks('grunt-sass');
    grunt.loadNpmTasks('grunt-postcss');
    grunt.loadNpmTasks('grunt-contrib-cssmin');
    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-contrib-copy');
    grunt.loadNpmTasks('grunt-contrib-compress');
    grunt.loadNpmTasks('grunt-contrib-clean');
    grunt.loadNpmTasks('grunt-wp-i18n');

    // rtlcss, you will still need to install ruby and sass on your system manually to run this
    grunt.registerTask('rtl', ['rtlcss']);

    // SASS compile
    grunt.registerTask('scss', ['sass']);

    // Style
    grunt.registerTask('style', ['scss', 'postcss:style', 'rtl']);

    // min all
    grunt.registerTask('minify', ['style', 'uglify:js', 'cssmin:css']);

    // Grunt release - Create installable package of the local files
    grunt.registerTask('release', ['clean:zip', 'copy:main', 'compress:main', 'clean:main']);
    grunt.registerTask('org-release', ['clean:zip', 'copy:org', 'compress:org', 'clean:main']);

    // i18n
    grunt.registerTask('i18n', ['addtextdomain', 'makepot']);

    grunt.util.linefeed = '\n';
};
