<?php
/**
 * Database Background Process
 *
 * @package Astra
 * @since 2.0.0
 */

if ( class_exists( 'WP_Background_Process' ) ) :

	/**
	 * Database Background Process
	 *
	 * @since 2.0.0
	 */
	class WP_Background_Process_Astra_Theme extends WP_Background_Process {

		/**
		 * Database Process
		 *
		 * @var string
		 */
		protected $action = 'astra_theme_db_migration';

		/**
		 * Task
		 *
		 * Override this method to perform any actions required on each
		 * queue item. Return the modified item for further processing
		 * in the next pass through. Or, return false to remove the
		 * item from the queue.
		 *
		 * @since 2.0.0
		 *
		 * @param object $process Queue item object.
		 * @return mixed
		 */
		protected function task( $process ) {

			do_action( 'astra_batch_process_task'. '-' .$process , $process );

			error_log( sprintf( 'process: %s' , $process ) );

			if( function_exists( $process ) ) {
				call_user_func( $process );
			} else if( 'update_db_version' === $process ) {
				Astra_Theme_Background_Updater::update_db_version();
			}

			return false;
		}

		/**
		 * Complete
		 *
		 * Override if applicable, but ensure that the below actions are
		 * performed, or, call parent::complete().
		 *
		 * @since 2.0.0
		 */
		protected function complete() {

			error_log( 'Batch Process Completed!' );

			do_action( 'astra_database_migration_complete' );

			parent::complete();

		}

	}

endif;
