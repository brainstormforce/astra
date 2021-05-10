<?php
/**
 * Astra Builder Admin Loader.
 *
 * @package astra-builder
 */

// No direct access, please.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Astra_Builder_Admin.
 */
final class Astra_Builder_Admin {

	/**
	 * Member Variable
	 *
	 * @var instance
	 */
	private static $instance = null;

	/**
	 *  Initiator
	 */
	public static function get_instance() {

		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'wp_ajax_ast-migrate-to-builder', array( $this, 'migrate_to_builder' ) );
		add_action( 'astra_welcome_page_content', array( $this, 'migrate_to_builder_box' ), 5 );
	}

	/**
	 * Migrate to New Header Builder
	 *
	 * @since 3.0.0
	 * @return void
	 */
	public function migrate_to_builder_box() {
		if ( Astra_Builder_Helper::is_new_user() ) {
			add_filter( 'astra_quick_settings', array( $this, 'update_customizer_header_footer_link' ) );
			return;
		}

		$status            = astra_get_option( 'is-header-footer-builder', false );
		$astra_theme_title = Astra_Admin_Settings::$page_title;

		$label = ( false !== $status ) ? __( 'Use Old Header/Footer', 'astra' ) : __( 'Use New Header/Footer Builder', 'astra' );

		?>
		<div class="postbox">
			<h2 class="hndle ast-normal-cursor ast-addon-heading ast-flex">
				<span>
					<?php
						printf(
							/* translators: %1$s: Theme name. */
							esc_html__( '%1$s Header/Footer Builder', 'astra' ),
							esc_html( $astra_theme_title )
						);
					?>
				</span>
			</h2>
			<div class="inside">
				<div>
					<p>
						<?php
							printf(
								/* translators: %1$s: Theme name. */
								esc_html__( '%1$s Header/Footer Builder is a new and powerful way to design header and footer for your website. With this, you can give a creative look to your header/footer with less effort.', 'astra' ),
								esc_html( $astra_theme_title )
							);
						?>
					</p>
					<p>
						<?php
							printf(
								/* translators: %1$s: Theme name. */
								esc_html__( 'Activating this feature will add advanced options to %1$s customizer where you can create awesome new designs.', 'astra' ),
								esc_html( $astra_theme_title )
							);
						?>
					</p>
					<p><?php esc_html_e( 'Note: The header/footer builder will replace the existing header/footer settings in the customizer. This might make your header/footer look a bit different. You can configure header/footer builder settings from customizer to give it a nice look. You can always come back here and switch to your old header/footer.', 'astra' ); ?></p>
					<div class="ast-actions-wrap" style="justify-content: space-between;display: flex;align-items: center;" >
						<a href="<?php echo esc_url( admin_url( '/customize.php' ) ); ?>" class="ast-go-to-customizer"><?php esc_html_e( 'Go to Customizer', 'astra' ); ?></a>
						<div class="ast-actions" style="display: inline-flex;">
							<button href="#" class="button button-primary ast-builder-migrate" style="margin-right:10px;" data-value="<?php echo ( $status ) ? 0 : 1; ?>"><?php echo esc_html( $label ); ?></button>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php
		if ( $status ) {
			add_filter( 'astra_quick_settings', array( $this, 'update_customizer_header_footer_link' ) );
		}
	}

	/**
	 * Update Customizer Header Footer quick links from options page.
	 *
	 * @since 3.0.0
	 * @param array $args default Header Footer quick links.
	 * @return array updated Header Footer quick links.
	 */
	public function update_customizer_header_footer_link( $args ) {
		if ( isset( $args['header']['quick_url'] ) ) {
			$args['header']['quick_url'] = admin_url( 'customize.php?autofocus[panel]=panel-header-builder-group' );
		}
		if ( isset( $args['footer']['quick_url'] ) ) {
			$args['footer']['quick_url'] = admin_url( 'customize.php?autofocus[panel]=panel-footer-builder-group' );
		}
		return $args;
	}

	/**
	 * Migrate to New Header Builder
	 */
	public function migrate_to_builder() {

		check_ajax_referer( 'astra-builder-module-nonce', 'nonce' );

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( __( 'You don\'t have the access', 'astra' ) );
		}

		$migrate        = isset( $_POST['value'] ) ? sanitize_key( $_POST['value'] ) : '';
		$migrate        = ( $migrate ) ? true : false;
		$migration_flag = astra_get_option( 'v3-option-migration', false );
		astra_update_option( 'is-header-footer-builder', $migrate );
		if ( $migrate && false === $migration_flag ) {
			astra_header_builder_migration();
		}
		wp_send_json_success();
	}

}

/**
 *  Prepare if class 'Astra_Builder_Admin' exist.
 *  Kicking this off by calling 'get_instance()' method
 */
Astra_Builder_Admin::get_instance();
