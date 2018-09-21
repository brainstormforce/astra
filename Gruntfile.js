module.exports = function (grunt) {
    'use strict';
    // Project configuration
    var autoprefixer    = require('autoprefixer');
    var flexibility     = require('postcss-flexibility');
    const sass = require('node-sass');

    var pkgInfo = grunt.file.readJSON('package.json');

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
                                    '!font-awesome.css',
                                    '!astra-fonts.css',
                                ],
                            dest: 'assets/css/unminified',
                            ext: '-rtl.css'

                        },
                        {
                            expand: true,
                            cwd: 'assets/css/unminified/compatibility',
                            src: [
                                    '*.css',
                                    '!*-rtl.css',
                                    '!font-awesome.css',
                                    '!astra-fonts.css',
                                ],
                            dest: 'assets/css/unminified/compatibility',
                            ext: '-rtl.css'
                        },
                        {
                            expand: true,
                            cwd: 'assets/css/unminified/compatibility/woocommerce',
                            src: [
                                    '*.css',
                                    '!*-rtl.css',
                                    '!font-awesome.css',
                                    '!astra-fonts.css',
                                ],
                            dest: 'assets/css/unminified/compatibility/woocommerce',
                            ext: '-rtl.css'
                        },
                        {
                            expand: true,
                            cwd: 'inc/assets/css',
                            src: [
                                    '*.css',
                                    '!*-rtl.css',
                                ],
                            dest: 'inc/assets/css',
                            ext: '-rtl.css'
                        },
                    ]
              	}
            },

            sass: {
                options: {
                    implementation: sass,
                    sourcemap: 'none',
                    outputStyle: 'expanded',
                    linefeed: 'lf',
                },
                dist: {
                    files: [

                        /*{
                        'style.css': 'sass/style.scss'
                        },*/

                        /* Link Pointer Style */
                        {
                            'assets/css/unminified/link-pointer-style.css': 'sass/site/navigation/link-pointer-style.scss',
                            'assets/css/unminified/menu-animation.css': 'sass/site/navigation/menu-animation.scss',
                        },

                        /* Editor Style */
                        {
                            'assets/css/unminified/editor-style.css': 'sass/editor-style.scss',
                            'inc/customizer/custom-controls/responsive/responsive.css': 'inc/customizer/custom-controls/responsive/responsive.scss',
                            'inc/customizer/custom-controls/divider/divider.css': 'inc/customizer/custom-controls/divider/divider.scss',
                            'inc/customizer/custom-controls/heading/heading.css': 'inc/customizer/custom-controls/heading/heading.scss',
                            'inc/customizer/custom-controls/description/description.css': 'inc/customizer/custom-controls/description/description.scss',
                            'inc/customizer/custom-controls/radio-image/radio-image.css': 'inc/customizer/custom-controls/radio-image/radio-image.scss',
                            'inc/customizer/custom-controls/slider/slider.css': 'inc/customizer/custom-controls/slider/slider.scss',
                            'inc/customizer/custom-controls/sortable/sortable.css': 'inc/customizer/custom-controls/sortable/sortable.scss',
                            'inc/customizer/custom-controls/spacing/spacing.css': 'inc/customizer/custom-controls/spacing/spacing.scss',
                            'inc/customizer/custom-controls/responsive-spacing/responsive-spacing.css': 'inc/customizer/custom-controls/responsive-spacing/responsive-spacing.scss',
                            'inc/customizer/custom-controls/background/background.css': 'inc/customizer/custom-controls/background/background.scss',
                            'inc/customizer/custom-controls/border/border.css': 'inc/customizer/custom-controls/border/border.scss',
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
                            cwd: 'sass/site/compatibility/',
                            src: ['**.scss'],
                            dest: 'assets/css/unminified/compatibility',
                            ext: '.css'
                        },
                        {
                            expand: true,
                            cwd: 'sass/site/compatibility/woocommerce',
                            src: ['**.scss'],
                            dest: 'assets/css/unminified/compatibility/woocommerce',
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
                        'assets/css/unminified/style.css',
                        'assets/css/unminified/*.css',
                        'assets/css/unminified/compatibility/*.css'
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
		                    	'assets/js/minified/navigation.min.js',
                                'assets/js/minified/skip-link-focus-fix.min.js',
                                'assets/js/minified/custom-events-polyfill.js'
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

	                    // Generating RTL files from '/unminified/compatibility/' into '/minified/compatibility/'
	                    // NOTE: Not possible to generate bulk .min-rtl.css files from '.min.css'
                    	{
                    		src: 'assets/css/unminified/compatibility/bne-flyout-rtl.css',
	                        dest: 'assets/css/minified/compatibility/bne-flyout.min-rtl.css',
	                    },
                    	{
                    		src: 'assets/css/unminified/compatibility/contact-form-7-rtl.css',
	                        dest: 'assets/css/minified/compatibility/contact-form-7.min-rtl.css',
	                    },
                    	{
                    		src: 'assets/css/unminified/compatibility/gravity-forms-rtl.css',
	                        dest: 'assets/css/minified/compatibility/gravity-forms.min-rtl.css',
	                    },
                    	{
                            src: 'assets/css/unminified/compatibility/lifterlms-rtl.css',
                            dest: 'assets/css/minified/compatibility/lifterlms.min-rtl.css',
                        },
                        {
                    		src: 'assets/css/unminified/compatibility/learndash-rtl.css',
	                        dest: 'assets/css/minified/compatibility/learndash.min-rtl.css',
	                    },
                    	{
                    		src: 'assets/css/unminified/compatibility/site-origin-rtl.css',
	                        dest: 'assets/css/minified/compatibility/site-origin.min-rtl.css',
	                    },
                    	{
                    		src: 'assets/css/unminified/compatibility/woocommerce/woocommerce-rtl.css',
	                        dest: 'assets/css/minified/compatibility/woocommerce/woocommerce.min-rtl.css',
	                    },
                        {
                            src: 'assets/css/unminified/compatibility/woocommerce/woocommerce-layout-rtl.css',
                            dest: 'assets/css/minified/compatibility/woocommerce/woocommerce-layout.min-rtl.css',
                        },
                        {
                            src: 'assets/css/unminified/compatibility/woocommerce/woocommerce-smallscreen-rtl.css',
                            dest: 'assets/css/minified/compatibility/woocommerce/woocommerce-smallscreen.min-rtl.css',
                        },
                        {
                            src: 'assets/css/unminified/compatibility/divi-builder-rtl.css',
                            dest: 'assets/css/minified/compatibility/divi-builder.min-rtl.css',
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
                        '!*.sh',
                        '!*.map',
                        '!Gruntfile.js',
                        '!package.json',
                        '!.gitignore',
                        '!phpunit.xml',
                        '!README.md',
                        '!sass/**',
                        '!codesniffer.ruleset.xml',
                        '!vendor/**',
                        '!composer.json',
                        '!composer.lock',
                        '!package-lock.json',
                        '!phpcs.xml.dist',
                    ],
                    dest: 'astra/'
                }
            },

            compress: {
                main: {
                    options: {
                        archive: 'astra-' + pkgInfo.version + '.zip',
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
                zip: ["*.zip"]

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
                        'assets/js/unminified/navigation.js',
                        'assets/js/unminified/skip-link-focus-fix.js',
                    ],
                    dest: 'assets/js/unminified/style.js',
                }
            },

            bumpup: {
                options: {
                    updateProps: {
                        pkg: 'package.json'
                    }
                },
                file: 'package.json'
            },

            replace: {
                theme_main: {
                    src: ['style.css'],
                    overwrite: true,
                    replacements: [
                        {
                            from: /Version: \bv?(?:0|[1-9]\d*)\.(?:0|[1-9]\d*)\.(?:0|[1-9]\d*)(?:-[\da-z-A-Z-]+(?:\.[\da-z-A-Z-]+)*)?(?:\+[\da-z-A-Z-]+(?:\.[\da-z-A-Z-]+)*)?\b/g,
                            to: 'Version: <%= pkg.version %>'
                        }
                    ]
                },

                theme_const: {
                    src: ['functions.php'],
                    overwrite: true,
                    replacements: [
                        {
                            from: /ASTRA_THEME_VERSION', '.*?'/g,
                            to: 'ASTRA_THEME_VERSION\', \'<%= pkg.version %>\''
                        }
                    ]
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
    grunt.loadNpmTasks('grunt-bumpup');
    grunt.loadNpmTasks('grunt-text-replace');

    // rtlcss, you will still need to install ruby and sass on your system manually to run this
    grunt.registerTask('rtl', ['rtlcss']);

    // SASS compile
    grunt.registerTask('scss', ['sass']);

    // Style
    grunt.registerTask('style', ['scss', 'postcss:style', 'rtl']);

    // min all
    grunt.registerTask('minify', ['style', 'uglify:js', 'cssmin:css', 'concat']);

    // Update google Fonts
    grunt.registerTask('google-fonts', function () {
        var done = this.async();
        var request = require('request');
        var fs = require('fs');

        request('https://www.googleapis.com/webfonts/v1/webfonts?key=AIzaSyDu1nDK2o4FpxhrIlNXyPNckVW5YP9HRu8', function (error, response, body) {

            if (response && response.statusCode == 200) {

                var fonts = JSON.parse(body).items.map(function (font) {
                    return {
                        [font.family] : {
                            'variants' : font.variants,
                            'category' : font.category
                        }
                    };
                })

                fs.writeFile('assets/fonts/google-fonts.json', JSON.stringify(fonts, undefined, 4), function (err) {
                    if (! err ) {
                        console.log("Google Fonts Updated!");
                    }
                });
            }

        });

    });

    // Grunt release - Create installable package of the local files
    grunt.registerTask('release', ['clean:zip', 'copy:main', 'compress:main', 'clean:main']);

    // Bump Version - `grunt version-bump --ver=<version-number>`
    grunt.registerTask('version-bump', function (ver) {

        var newVersion = grunt.option('ver');

        if (newVersion) {
            newVersion = newVersion ? newVersion : 'patch';

            grunt.task.run('bumpup:' + newVersion);
            grunt.task.run('replace');
        }
    });

    // i18n
    grunt.registerTask('i18n', ['addtextdomain', 'makepot']);

    grunt.util.linefeed = '\n';
};

