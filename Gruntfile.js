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
                            'inc/customizer/custom-controls/responsive/responsive.css': 'inc/customizer/custom-controls/responsive/responsive.scss',
                            'inc/customizer/custom-controls/divider/divider.css': 'inc/customizer/custom-controls/divider/divider.scss',
                            'inc/customizer/custom-controls/radio-image/radio-image.css': 'inc/customizer/custom-controls/radio-image/radio-image.scss',
                            'inc/customizer/custom-controls/slider/slider.css': 'inc/customizer/custom-controls/slider/slider.scss',
                            'inc/customizer/custom-controls/sortable/sortable.css': 'inc/customizer/custom-controls/sortable/sortable.scss',
                            'inc/customizer/custom-controls/spacing/spacing.css': 'inc/customizer/custom-controls/spacing/spacing.scss',
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
                    files: [
                    	{ // all .js to min.js
	                        expand: true,
	                        src: [
	                            '**.js',
	                        ],
	                        dest: 'assets/js/minified',
	                        cwd: 'assets/js/unminified',
	                        ext: '.min.js'
	                    },
	                    {
		                    src: [
		                        'assets/js/minified/flexibility.min.js',
		                    	'assets/js/minified/navigation.min.js',
		                    	'assets/js/minified/skip-link-focus-fix.min.js',
		                    ],
		                    dest: 'assets/js/minified/style.min.js',
		                },
	               	]
                }
            },

            cssmin: {
                options: {
                    keepSpecialComments: 0
                },
                css: {
                    files: [

                    	// Generated '.min.css' files from '.css' files.
                    	// NOTE: Avoided '-rtl.css' files.
                    	{
	                        expand: true,
	                        src: [
	                            '**/*.css',
	                            '!**/*-rtl.css',
	                        ],
	                        dest: 'assets/css/minified',
	                        cwd: 'assets/css/unminified',
	                        ext: '.min.css'
	                    },

	                    // Generating RTL files from '/unminified/' into '/minified/'
                    	// NOTE: Not possible to generate bulk .min-rtl.css files from '.min.css'
                    	{
                    		src: 'assets/css/unminified/editor-style-rtl.css',
	                        dest: 'assets/css/minified/editor-style.min-rtl.css',
	                    },
                    	{
                    		src: 'assets/css/unminified/style-rtl.css',
	                        dest: 'assets/css/minified/style.min-rtl.css',
	                    },

	                    // Generating RTL files from '/unminified/site-compatible/' into '/minified/site-compatible/'
	                    // NOTE: Not possible to generate bulk .min-rtl.css files from '.min.css'
                    	{
                    		src: 'assets/css/unminified/site-compatible/bne-flyout-rtl.css',
	                        dest: 'assets/css/minified/site-compatible/bne-flyout.min-rtl.css',
	                    },
                    	{
                    		src: 'assets/css/unminified/site-compatible/contact-form-7-rtl.css',
	                        dest: 'assets/css/minified/site-compatible/contact-form-7.min-rtl.css',
	                    },
                    	{
                    		src: 'assets/css/unminified/site-compatible/gravity-forms-rtl.css',
	                        dest: 'assets/css/minified/site-compatible/gravity-forms.min-rtl.css',
	                    },
                    	{
                    		src: 'assets/css/unminified/site-compatible/lifter-lms-rtl.css',
	                        dest: 'assets/css/minified/site-compatible/lifter-lms.min-rtl.css',
	                    },
                    	{
                    		src: 'assets/css/unminified/site-compatible/site-origin-rtl.css',
	                        dest: 'assets/css/minified/site-compatible/site-origin.min-rtl.css',
	                    },
                    	{
                    		src: 'assets/css/unminified/site-compatible/woocommerce-rtl.css',
	                        dest: 'assets/css/minified/site-compatible/woocommerce.min-rtl.css',
	                    },
                    ]
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
            },

            concat: {
                options: {
                    separator: '\n'
                },
                dist: {
                    src: [
                        'assets/js/unminified/flexibility.js',
                        'assets/js/unminified/navigation.js',
                        'assets/js/unminified/skip-link-focus-fix.js',
                    ],
                    dest: 'assets/js/unminified/style.js',
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
    grunt.loadNpmTasks('grunt-contrib-concat');
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
    grunt.registerTask('minify', ['style', 'uglify:js', 'cssmin:css', 'concat']);

    // Grunt release - Create installable package of the local files
    grunt.registerTask('release', ['clean:zip', 'copy:main', 'compress:main', 'clean:main']);
    grunt.registerTask('org-release', ['clean:zip', 'copy:org', 'compress:org', 'clean:main']);

    // i18n
    grunt.registerTask('i18n', ['addtextdomain', 'makepot']);

    grunt.util.linefeed = '\n';
};
