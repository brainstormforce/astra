module.exports = function (grunt) {
    'use strict';
    // Project configuration
    var autoprefixer = require('autoprefixer');
    var flexibility = require('postcss-flexibility');

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
                        cwd: 'assets/css/unminified',	
                        src: [	
                            '*.css',	
                            '!*-rtl.css',	
                        ],	
                        dest: 'assets/css/unminified',	
                        ext: '-rtl.css'	
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
	            	'assets/css/unminified/**.css',
					'!assets/css/unminified/**-rtl.css'
	            ]
	        }
	    },

        cssmin: {
            options: {
                keepSpecialComments: 0
            },
            css: {
                files: [
                    {	
                        src: 'assets/css/unminified/style.css',	
                        dest: 'assets/css/minified/style.min.css',	
                    },	
                    {	
                        src: 'assets/css/unminified/style-rtl.css',	
                        dest: 'assets/css/minified/style.min-rtl.css',	
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
                dest: 'bsf-analytics/'
            }
        },

        compress: {
            main: {
                options: {
                    archive: 'bsf-analytics-' + pkgInfo.version + '.zip',
                    mode: 'zip'
                },
                files: [
                    {
                        src: [
                            './bsf-analytics/**'
                        ]

                    }
                ]
            }
        },

        clean: {
            main: ["bsf-analytics"],
            zip: ["*.zip"]

        },

        replace: {

            analytics_const: {
                src: ['class-bsf-analytics.php'],
                overwrite: true,
                replacements: [
                    {
                        from: /BSF_ANALYTICS_VERSION', '.*?'/g,
                        to: 'BSF_ANALYTICS_VERSION\', \'<%= pkg.version %>\''
                    }
                ]
            },

            analytics_function_comment: {
                src: [
                    '*.php',
                    '**/*.php',
                    '!node_modules/**',
                    '!php-tests/**',
                    '!bin/**',
                ],
                overwrite: true,
                replacements: [
                    {
                        from: 'x.x.x',
                        to: '<%=pkg.version %>'
                    }
                ]
            },
        },
    }
    );

    // Load grunt tasks
    grunt.loadNpmTasks('grunt-rtlcss');
    grunt.loadNpmTasks( 'grunt-postcss' );

    grunt.loadNpmTasks('grunt-contrib-cssmin');
    grunt.loadNpmTasks('grunt-contrib-copy');
    grunt.loadNpmTasks('grunt-contrib-compress');
    grunt.loadNpmTasks('grunt-contrib-clean');

    /* Version Bump Task */
	grunt.loadNpmTasks( 'grunt-bumpup' );
	grunt.loadNpmTasks( 'grunt-text-replace' );

    // rtlcss, you will still need to install ruby and sass on your system manually to run this
    grunt.registerTask('rtl', ['rtlcss']);

    // Style
    grunt.registerTask('style', ['rtl']);

    // min all
    grunt.registerTask('minify', ['style', 'cssmin:css']);

    // Grunt release - Create installable package of the local files
    grunt.registerTask('release', ['clean:zip', 'copy:main', 'compress:main', 'clean:main']);

    // Version Bump `grunt bump-version --ver=<version-number>`
    grunt.registerTask( 'bump-version', function() {

		var newVersion = grunt.option('ver');

		if ( newVersion ) {
			grunt.task.run( 'bumpup:' + newVersion );
			grunt.task.run( 'replace' );
		}
	} );
};
