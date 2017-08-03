<?php
/**
 * Helper class for font settings.
 *
 * @package     Astra
 * @author      Astra
 * @copyright   Copyright (c) 2017, Astra
 * @link        http://wpastra.com/
 * @since       Astra 1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * Font info class for System and Google fonts.
 */
if ( ! class_exists( 'Astra_Fonts_Data' ) ) :

	/**
	 * Fonts Data
	 */
	final class Astra_Fonts_Data {

		/**
		 * Localize Fonts
		 */
		static public function js() {
			$system = json_encode( Astra_Font_Families::$system );
			$google = json_encode( Astra_Font_Families::$google );

			return 'var AstFontFamilies = { system: ' . $system . ', google: ' . $google . ' };';
		}
	}

endif;

/**
 * Font info class for System and Google fonts.
 */
if ( ! class_exists( 'Astra_Font_Families' ) ) :

	/**
	 * Font info class for System and Google fonts.
	 */
	final class Astra_Font_Families {

		/**
		 * Array of System Fonts
		 *
		 * @var array
		 */
		static public $system = array(
			'Helvetica' => array(
				'fallback' => 'Verdana, Arial, sans-serif',
				'weights'  => array(
					'300',
					'400',
					'700',
				),
			),
			'Verdana' => array(
				'fallback' => 'Helvetica, Arial, sans-serif',
				'weights'  => array(
					'300',
					'400',
					'700',
				),
			),
			'Arial' => array(
				'fallback' => 'Helvetica, Verdana, sans-serif',
				'weights'  => array(
					'300',
					'400',
					'700',
				),
			),
			'Times' => array(
				'fallback' => 'Georgia, serif',
				'weights'  => array(
					'300',
					'400',
					'700',
				),
			),
			'Georgia' => array(
				'fallback' => 'Times, serif',
				'weights'  => array(
					'300',
					'400',
					'700',
				),
			),
			'Courier' => array(
				'fallback' => 'monospace',
				'weights'  => array(
					'300',
					'400',
					'700',
				),
			),
		);

		/**
		 * Array of Google Fonts
		 *
		 * @var array
		 */
		static public $google = array(
			'ABeeZee' => array(
				'400',
			),
			'Abel' => array(
				'400',
			),
			'Abril Fatface' => array(
				'400',
			),
			'Aclonica' => array(
				'400',
			),
			'Acme' => array(
				'400',
			),
			'Actor' => array(
				'400',
			),
			'Adamina' => array(
				'400',
			),
			'Advent Pro' => array(
				'100',
				'200',
				'300',
				'400',
				'500',
				'600',
				'700',
			),
			'Aguafina Script' => array(
				'400',
			),
			'Akronim' => array(
				'400',
			),
			'Aladin' => array(
				'400',
			),
			'Aldrich' => array(
				'400',
			),
			'Alef' => array(
				'400',
				'700',
			),
			'Alegreya' => array(
				'400',
				'700',
				'900',
			),
			'Alegreya SC' => array(
				'400',
				'700',
				'900',
			),
			'Alegreya Sans' => array(
				'100',
				'300',
				'400',
				'500',
				'700',
				'800',
				'900',
			),
			'Alegreya Sans SC' => array(
				'100',
				'300',
				'400',
				'500',
				'700',
				'800',
				'900',
			),
			'Alex Brush' => array(
				'400',
			),
			'Alfa Slab One' => array(
				'400',
			),
			'Alice' => array(
				'400',
			),
			'Alike' => array(
				'400',
			),
			'Alike Angular' => array(
				'400',
			),
			'Allan' => array(
				'400',
				'700',
			),
			'Allerta' => array(
				'400',
			),
			'Allerta Stencil' => array(
				'400',
			),
			'Allura' => array(
				'400',
			),
			'Almendra' => array(
				'400',
				'700',
			),
			'Almendra Display' => array(
				'400',
			),
			'Almendra SC' => array(
				'400',
			),
			'Amarante' => array(
				'400',
			),
			'Amaranth' => array(
				'400',
				'700',
			),
			'Amatic SC' => array(
				'400',
				'700',
			),
			'Amethysta' => array(
				'400',
			),
			'Amiri' => array(
				'400',
				'700',
			),
			'Amita' => array(
				'400',
				'700',
			),
			'Anaheim' => array(
				'400',
			),
			'Andada' => array(
				'400',
			),
			'Andika' => array(
				'400',
			),
			'Angkor' => array(
				'400',
			),
			'Annie Use Your Telescope' => array(
				'400',
			),
			'Anonymous Pro' => array(
				'400',
				'700',
			),
			'Antic' => array(
				'400',
			),
			'Antic Didone' => array(
				'400',
			),
			'Antic Slab' => array(
				'400',
			),
			'Anton' => array(
				'400',
			),
			'Arapey' => array(
				'400',
			),
			'Arbutus' => array(
				'400',
			),
			'Arbutus Slab' => array(
				'400',
			),
			'Architects Daughter' => array(
				'400',
			),
			'Archivo Black' => array(
				'400',
			),
			'Archivo Narrow' => array(
				'400',
				'700',
			),
			'Arimo' => array(
				'400',
				'700',
			),
			'Arizonia' => array(
				'400',
			),
			'Armata' => array(
				'400',
			),
			'Artifika' => array(
				'400',
			),
			'Arvo' => array(
				'400',
				'700',
			),
			'Arya' => array(
				'400',
				'700',
			),
			'Asap' => array(
				'400',
				'700',
			),
			'Asar' => array(
				'400',
			),
			'Asset' => array(
				'400',
			),
			'Astloch' => array(
				'400',
				'700',
			),
			'Asul' => array(
				'400',
				'700',
			),
			'Atomic Age' => array(
				'400',
			),
			'Aubrey' => array(
				'400',
			),
			'Audiowide' => array(
				'400',
			),
			'Autour One' => array(
				'400',
			),
			'Average' => array(
				'400',
			),
			'Average Sans' => array(
				'400',
			),
			'Averia Gruesa Libre' => array(
				'400',
			),
			'Averia Libre' => array(
				'300',
				'400',
				'700',
			),
			'Averia Sans Libre' => array(
				'300',
				'400',
				'700',
			),
			'Averia Serif Libre' => array(
				'300',
				'400',
				'700',
			),
			'Bad Script' => array(
				'400',
			),
			'Balthazar' => array(
				'400',
			),
			'Bangers' => array(
				'400',
			),
			'Basic' => array(
				'400',
			),
			'Battambang' => array(
				'400',
				'700',
			),
			'Baumans' => array(
				'400',
			),
			'Bayon' => array(
				'400',
			),
			'Belgrano' => array(
				'400',
			),
			'Belleza' => array(
				'400',
			),
			'BenchNine' => array(
				'300',
				'400',
				'700',
			),
			'Bentham' => array(
				'400',
			),
			'Berkshire Swash' => array(
				'400',
			),
			'Bevan' => array(
				'400',
			),
			'Bigelow Rules' => array(
				'400',
			),
			'Bigshot One' => array(
				'400',
			),
			'Bilbo' => array(
				'400',
			),
			'Bilbo Swash Caps' => array(
				'400',
			),
			'Biryani' => array(
				'200',
				'300',
				'400',
				'600',
				'700',
				'800',
				'900',
			),
			'Bitter' => array(
				'400',
				'700',
			),
			'Black Ops One' => array(
				'400',
			),
			'Bokor' => array(
				'400',
			),
			'Bonbon' => array(
				'400',
			),
			'Boogaloo' => array(
				'400',
			),
			'Bowlby One' => array(
				'400',
			),
			'Bowlby One SC' => array(
				'400',
			),
			'Brawler' => array(
				'400',
			),
			'Bree Serif' => array(
				'400',
			),
			'Bubblegum Sans' => array(
				'400',
			),
			'Bubbler One' => array(
				'400',
			),
			'Buda' => array(
				'300',
			),
			'Buenard' => array(
				'400',
				'700',
			),
			'Butcherman' => array(
				'400',
			),
			'Butterfly Kids' => array(
				'400',
			),
			'Cabin' => array(
				'400',
				'500',
				'600',
				'700',
			),
			'Cabin Condensed' => array(
				'400',
				'500',
				'600',
				'700',
			),
			'Cabin Sketch' => array(
				'400',
				'700',
			),
			'Caesar Dressing' => array(
				'400',
			),
			'Cagliostro' => array(
				'400',
			),
			'Calligraffitti' => array(
				'400',
			),
			'Cambay' => array(
				'400',
				'700',
			),
			'Cambo' => array(
				'400',
			),
			'Candal' => array(
				'400',
			),
			'Cantarell' => array(
				'400',
				'700',
			),
			'Cantata One' => array(
				'400',
			),
			'Cantora One' => array(
				'400',
			),
			'Capriola' => array(
				'400',
			),
			'Cardo' => array(
				'400',
				'700',
			),
			'Carme' => array(
				'400',
			),
			'Carrois Gothic' => array(
				'400',
			),
			'Carrois Gothic SC' => array(
				'400',
			),
			'Carter One' => array(
				'400',
			),
			'Catamaran' => array(
				'100',
				'200',
				'300',
				'400',
				'500',
				'600',
				'700',
				'800',
				'900',
			),
			'Caudex' => array(
				'400',
				'700',
			),
			'Caveat' => array(
				'400',
				'700',
			),
			'Caveat Brush' => array(
				'400',
			),
			'Cedarville Cursive' => array(
				'400',
			),
			'Ceviche One' => array(
				'400',
			),
			'Changa One' => array(
				'400',
			),
			'Chango' => array(
				'400',
			),
			'Chau Philomene One' => array(
				'400',
			),
			'Chela One' => array(
				'400',
			),
			'Chelsea Market' => array(
				'400',
			),
			'Chenla' => array(
				'400',
			),
			'Cherry Cream Soda' => array(
				'400',
			),
			'Cherry Swash' => array(
				'400',
				'700',
			),
			'Chewy' => array(
				'400',
			),
			'Chicle' => array(
				'400',
			),
			'Chivo' => array(
				'400',
				'900',
			),
			'Chonburi' => array(
				'400',
			),
			'Cinzel' => array(
				'400',
				'700',
				'900',
			),
			'Cinzel Decorative' => array(
				'400',
				'700',
				'900',
			),
			'Clicker Script' => array(
				'400',
			),
			'Coda' => array(
				'400',
				'800',
			),
			'Coda Caption' => array(
				'800',
			),
			'Codystar' => array(
				'300',
				'400',
			),
			'Combo' => array(
				'400',
			),
			'Comfortaa' => array(
				'300',
				'400',
				'700',
			),
			'Coming Soon' => array(
				'400',
			),
			'Concert One' => array(
				'400',
			),
			'Condiment' => array(
				'400',
			),
			'Content' => array(
				'400',
				'700',
			),
			'Contrail One' => array(
				'400',
			),
			'Convergence' => array(
				'400',
			),
			'Cookie' => array(
				'400',
			),
			'Copse' => array(
				'400',
			),
			'Corben' => array(
				'400',
				'700',
			),
			'Courgette' => array(
				'400',
			),
			'Cousine' => array(
				'400',
				'700',
			),
			'Coustard' => array(
				'400',
				'900',
			),
			'Covered By Your Grace' => array(
				'400',
			),
			'Crafty Girls' => array(
				'400',
			),
			'Creepster' => array(
				'400',
			),
			'Crete Round' => array(
				'400',
			),
			'Crimson Text' => array(
				'400',
				'600',
				'700',
			),
			'Croissant One' => array(
				'400',
			),
			'Crushed' => array(
				'400',
			),
			'Cuprum' => array(
				'400',
				'700',
			),
			'Cutive' => array(
				'400',
			),
			'Cutive Mono' => array(
				'400',
			),
			'Damion' => array(
				'400',
			),
			'Dancing Script' => array(
				'400',
				'700',
			),
			'Dangrek' => array(
				'400',
			),
			'Dawning of a New Day' => array(
				'400',
			),
			'Days One' => array(
				'400',
			),
			'Dekko' => array(
				'400',
			),
			'Delius' => array(
				'400',
			),
			'Delius Swash Caps' => array(
				'400',
			),
			'Delius Unicase' => array(
				'400',
				'700',
			),
			'Della Respira' => array(
				'400',
			),
			'Denk One' => array(
				'400',
			),
			'Devonshire' => array(
				'400',
			),
			'Dhurjati' => array(
				'400',
			),
			'Didact Gothic' => array(
				'400',
			),
			'Diplomata' => array(
				'400',
			),
			'Diplomata SC' => array(
				'400',
			),
			'Domine' => array(
				'400',
				'700',
			),
			'Donegal One' => array(
				'400',
			),
			'Doppio One' => array(
				'400',
			),
			'Dorsa' => array(
				'400',
			),
			'Dosis' => array(
				'200',
				'300',
				'400',
				'500',
				'600',
				'700',
				'800',
			),
			'Dr Sugiyama' => array(
				'400',
			),
			'Droid Sans' => array(
				'400',
				'700',
			),
			'Droid Sans Mono' => array(
				'400',
			),
			'Droid Serif' => array(
				'400',
				'700',
			),
			'Duru Sans' => array(
				'400',
			),
			'Dynalight' => array(
				'400',
			),
			'EB Garamond' => array(
				'400',
			),
			'Eagle Lake' => array(
				'400',
			),
			'Eater' => array(
				'400',
			),
			'Economica' => array(
				'400',
				'700',
			),
			'Eczar' => array(
				'400',
				'500',
				'600',
				'700',
				'800',
			),
			'Ek Mukta' => array(
				'200',
				'300',
				'400',
				'500',
				'600',
				'700',
				'800',
			),
			'Electrolize' => array(
				'400',
			),
			'Elsie' => array(
				'400',
				'900',
			),
			'Elsie Swash Caps' => array(
				'400',
				'900',
			),
			'Emblema One' => array(
				'400',
			),
			'Emilys Candy' => array(
				'400',
			),
			'Engagement' => array(
				'400',
			),
			'Englebert' => array(
				'400',
			),
			'Enriqueta' => array(
				'400',
				'700',
			),
			'Erica One' => array(
				'400',
			),
			'Esteban' => array(
				'400',
			),
			'Euphoria Script' => array(
				'400',
			),
			'Ewert' => array(
				'400',
			),
			'Exo' => array(
				'100',
				'200',
				'300',
				'400',
				'500',
				'600',
				'700',
				'800',
				'900',
			),
			'Exo 2' => array(
				'100',
				'200',
				'300',
				'400',
				'500',
				'600',
				'700',
				'800',
				'900',
			),
			'Expletus Sans' => array(
				'400',
				'500',
				'600',
				'700',
			),
			'Fanwood Text' => array(
				'400',
			),
			'Fascinate' => array(
				'400',
			),
			'Fascinate Inline' => array(
				'400',
			),
			'Faster One' => array(
				'400',
			),
			'Fasthand' => array(
				'400',
			),
			'Fauna One' => array(
				'400',
			),
			'Federant' => array(
				'400',
			),
			'Federo' => array(
				'400',
			),
			'Felipa' => array(
				'400',
			),
			'Fenix' => array(
				'400',
			),
			'Finger Paint' => array(
				'400',
			),
			'Fira Mono' => array(
				'400',
				'700',
			),
			'Fira Sans' => array(
				'300',
				'400',
				'500',
				'700',
			),
			'Fjalla One' => array(
				'400',
			),
			'Fjord One' => array(
				'400',
			),
			'Flamenco' => array(
				'300',
				'400',
			),
			'Flavors' => array(
				'400',
			),
			'Fondamento' => array(
				'400',
			),
			'Fontdiner Swanky' => array(
				'400',
			),
			'Forum' => array(
				'400',
			),
			'Francois One' => array(
				'400',
			),
			'Freckle Face' => array(
				'400',
			),
			'Fredericka the Great' => array(
				'400',
			),
			'Fredoka One' => array(
				'400',
			),
			'Freehand' => array(
				'400',
			),
			'Fresca' => array(
				'400',
			),
			'Frijole' => array(
				'400',
			),
			'Fruktur' => array(
				'400',
			),
			'Fugaz One' => array(
				'400',
			),
			'GFS Didot' => array(
				'400',
			),
			'GFS Neohellenic' => array(
				'400',
				'700',
			),
			'Gabriela' => array(
				'400',
			),
			'Gafata' => array(
				'400',
			),
			'Galdeano' => array(
				'400',
			),
			'Galindo' => array(
				'400',
			),
			'Gentium Basic' => array(
				'400',
				'700',
			),
			'Gentium Book Basic' => array(
				'400',
				'700',
			),
			'Geo' => array(
				'400',
			),
			'Geostar' => array(
				'400',
			),
			'Geostar Fill' => array(
				'400',
			),
			'Germania One' => array(
				'400',
			),
			'Gidugu' => array(
				'400',
			),
			'Gilda Display' => array(
				'400',
			),
			'Give You Glory' => array(
				'400',
			),
			'Glass Antiqua' => array(
				'400',
			),
			'Glegoo' => array(
				'400',
				'700',
			),
			'Gloria Hallelujah' => array(
				'400',
			),
			'Goblin One' => array(
				'400',
			),
			'Gochi Hand' => array(
				'400',
			),
			'Gorditas' => array(
				'400',
				'700',
			),
			'Goudy Bookletter 1911' => array(
				'400',
			),
			'Graduate' => array(
				'400',
			),
			'Grand Hotel' => array(
				'400',
			),
			'Gravitas One' => array(
				'400',
			),
			'Great Vibes' => array(
				'400',
			),
			'Griffy' => array(
				'400',
			),
			'Gruppo' => array(
				'400',
			),
			'Gudea' => array(
				'400',
				'700',
			),
			'Gurajada' => array(
				'400',
			),
			'Habibi' => array(
				'400',
			),
			'Halant' => array(
				'300',
				'400',
				'500',
				'600',
				'700',
			),
			'Hammersmith One' => array(
				'400',
			),
			'Hanalei' => array(
				'400',
			),
			'Hanalei Fill' => array(
				'400',
			),
			'Handlee' => array(
				'400',
			),
			'Hanuman' => array(
				'400',
				'700',
			),
			'Happy Monkey' => array(
				'400',
			),
			'Headland One' => array(
				'400',
			),
			'Henny Penny' => array(
				'400',
			),
			'Herr Von Muellerhoff' => array(
				'400',
			),
			'Hind' => array(
				'300',
				'400',
				'500',
				'600',
				'700',
			),
			'Hind Siliguri' => array(
				'300',
				'400',
				'500',
				'600',
				'700',
			),
			'Hind Vadodara' => array(
				'300',
				'400',
				'500',
				'600',
				'700',
			),
			'Holtwood One SC' => array(
				'400',
			),
			'Homemade Apple' => array(
				'400',
			),
			'Homenaje' => array(
				'400',
			),
			'IM Fell DW Pica' => array(
				'400',
			),
			'IM Fell DW Pica SC' => array(
				'400',
			),
			'IM Fell Double Pica' => array(
				'400',
			),
			'IM Fell Double Pica SC' => array(
				'400',
			),
			'IM Fell English' => array(
				'400',
			),
			'IM Fell English SC' => array(
				'400',
			),
			'IM Fell French Canon' => array(
				'400',
			),
			'IM Fell French Canon SC' => array(
				'400',
			),
			'IM Fell Great Primer' => array(
				'400',
			),
			'IM Fell Great Primer SC' => array(
				'400',
			),
			'Iceberg' => array(
				'400',
			),
			'Iceland' => array(
				'400',
			),
			'Imprima' => array(
				'400',
			),
			'Inconsolata' => array(
				'400',
				'700',
			),
			'Inder' => array(
				'400',
			),
			'Indie Flower' => array(
				'400',
			),
			'Inika' => array(
				'400',
				'700',
			),
			'Inknut Antiqua' => array(
				'300',
				'400',
				'500',
				'600',
				'700',
				'800',
				'900',
			),
			'Irish Grover' => array(
				'400',
			),
			'Istok Web' => array(
				'400',
				'700',
			),
			'Italiana' => array(
				'400',
			),
			'Italianno' => array(
				'400',
			),
			'Itim' => array(
				'400',
			),
			'Jacques Francois' => array(
				'400',
			),
			'Jacques Francois Shadow' => array(
				'400',
			),
			'Jaldi' => array(
				'400',
				'700',
			),
			'Jim Nightshade' => array(
				'400',
			),
			'Jockey One' => array(
				'400',
			),
			'Jolly Lodger' => array(
				'400',
			),
			'Josefin Sans' => array(
				'100',
				'300',
				'400',
				'600',
				'700',
			),
			'Josefin Slab' => array(
				'100',
				'300',
				'400',
				'600',
				'700',
			),
			'Joti One' => array(
				'400',
			),
			'Judson' => array(
				'400',
				'700',
			),
			'Julee' => array(
				'400',
			),
			'Julius Sans One' => array(
				'400',
			),
			'Junge' => array(
				'400',
			),
			'Jura' => array(
				'300',
				'400',
				'500',
				'600',
			),
			'Just Another Hand' => array(
				'400',
			),
			'Just Me Again Down Here' => array(
				'400',
			),
			'Kadwa' => array(
				'400',
				'700',
			),
			'Kalam' => array(
				'300',
				'400',
				'700',
			),
			'Kameron' => array(
				'400',
				'700',
			),
			'Kanit' => array(
				'100',
				'200',
				'300',
				'400',
				'500',
				'600',
				'700',
				'800',
				'900',
			),
			'Kantumruy' => array(
				'300',
				'400',
				'700',
			),
			'Karla' => array(
				'400',
				'700',
			),
			'Karma' => array(
				'300',
				'400',
				'500',
				'600',
				'700',
			),
			'Kaushan Script' => array(
				'400',
			),
			'Kavoon' => array(
				'400',
			),
			'Kdam Thmor' => array(
				'400',
			),
			'Keania One' => array(
				'400',
			),
			'Kelly Slab' => array(
				'400',
			),
			'Kenia' => array(
				'400',
			),
			'Khand' => array(
				'300',
				'400',
				'500',
				'600',
				'700',
			),
			'Khmer' => array(
				'400',
			),
			'Khula' => array(
				'300',
				'400',
				'600',
				'700',
				'800',
			),
			'Kite One' => array(
				'400',
			),
			'Knewave' => array(
				'400',
			),
			'Kotta One' => array(
				'400',
			),
			'Koulen' => array(
				'400',
			),
			'Kranky' => array(
				'400',
			),
			'Kreon' => array(
				'300',
				'400',
				'700',
			),
			'Kristi' => array(
				'400',
			),
			'Krona One' => array(
				'400',
			),
			'Kurale' => array(
				'400',
			),
			'La Belle Aurore' => array(
				'400',
			),
			'Laila' => array(
				'300',
				'400',
				'500',
				'600',
				'700',
			),
			'Lakki Reddy' => array(
				'400',
			),
			'Lancelot' => array(
				'400',
			),
			'Lateef' => array(
				'400',
			),
			'Lato' => array(
				'100',
				'300',
				'400',
				'700',
				'900',
			),
			'League Script' => array(
				'400',
			),
			'Leckerli One' => array(
				'400',
			),
			'Ledger' => array(
				'400',
			),
			'Lekton' => array(
				'400',
				'700',
			),
			'Lemon' => array(
				'400',
			),
			'Libre Baskerville' => array(
				'400',
				'700',
			),
			'Life Savers' => array(
				'400',
				'700',
			),
			'Lilita One' => array(
				'400',
			),
			'Lily Script One' => array(
				'400',
			),
			'Limelight' => array(
				'400',
			),
			'Linden Hill' => array(
				'400',
			),
			'Lobster' => array(
				'400',
			),
			'Lobster Two' => array(
				'400',
				'700',
			),
			'Londrina Outline' => array(
				'400',
			),
			'Londrina Shadow' => array(
				'400',
			),
			'Londrina Sketch' => array(
				'400',
			),
			'Londrina Solid' => array(
				'400',
			),
			'Lora' => array(
				'400',
				'700',
			),
			'Love Ya Like A Sister' => array(
				'400',
			),
			'Loved by the King' => array(
				'400',
			),
			'Lovers Quarrel' => array(
				'400',
			),
			'Luckiest Guy' => array(
				'400',
			),
			'Lusitana' => array(
				'400',
				'700',
			),
			'Lustria' => array(
				'400',
			),
			'Macondo' => array(
				'400',
			),
			'Macondo Swash Caps' => array(
				'400',
			),
			'Magra' => array(
				'400',
				'700',
			),
			'Maiden Orange' => array(
				'400',
			),
			'Mako' => array(
				'400',
			),
			'Mallanna' => array(
				'400',
			),
			'Mandali' => array(
				'400',
			),
			'Marcellus' => array(
				'400',
			),
			'Marcellus SC' => array(
				'400',
			),
			'Marck Script' => array(
				'400',
			),
			'Margarine' => array(
				'400',
			),
			'Marko One' => array(
				'400',
			),
			'Marmelad' => array(
				'400',
			),
			'Martel' => array(
				'200',
				'300',
				'400',
				'600',
				'700',
				'800',
				'900',
			),
			'Martel Sans' => array(
				'200',
				'300',
				'400',
				'600',
				'700',
				'800',
				'900',
			),
			'Marvel' => array(
				'400',
				'700',
			),
			'Mate' => array(
				'400',
			),
			'Mate SC' => array(
				'400',
			),
			'Maven Pro' => array(
				'400',
				'500',
				'700',
				'900',
			),
			'McLaren' => array(
				'400',
			),
			'Meddon' => array(
				'400',
			),
			'MedievalSharp' => array(
				'400',
			),
			'Medula One' => array(
				'400',
			),
			'Megrim' => array(
				'400',
			),
			'Meie Script' => array(
				'400',
			),
			'Merienda' => array(
				'400',
				'700',
			),
			'Merienda One' => array(
				'400',
			),
			'Merriweather' => array(
				'300',
				'400',
				'700',
				'900',
			),
			'Merriweather Sans' => array(
				'300',
				'400',
				'700',
				'800',
			),
			'Metal' => array(
				'400',
			),
			'Metal Mania' => array(
				'400',
			),
			'Metamorphous' => array(
				'400',
			),
			'Metrophobic' => array(
				'400',
			),
			'Michroma' => array(
				'400',
			),
			'Milonga' => array(
				'400',
			),
			'Miltonian' => array(
				'400',
			),
			'Miltonian Tattoo' => array(
				'400',
			),
			'Miniver' => array(
				'400',
			),
			'Miss Fajardose' => array(
				'400',
			),
			'Modak' => array(
				'400',
			),
			'Modern Antiqua' => array(
				'400',
			),
			'Molengo' => array(
				'400',
			),
			'Monda' => array(
				'400',
				'700',
			),
			'Monofett' => array(
				'400',
			),
			'Monoton' => array(
				'400',
			),
			'Monsieur La Doulaise' => array(
				'400',
			),
			'Montaga' => array(
				'400',
			),
			'Montez' => array(
				'400',
			),
			'Montserrat' => array(
				'400',
				'700',
			),
			'Montserrat Alternates' => array(
				'400',
				'700',
			),
			'Montserrat Subrayada' => array(
				'400',
				'700',
			),
			'Moul' => array(
				'400',
			),
			'Moulpali' => array(
				'400',
			),
			'Mountains of Christmas' => array(
				'400',
				'700',
			),
			'Mouse Memoirs' => array(
				'400',
			),
			'Mr Bedfort' => array(
				'400',
			),
			'Mr Dafoe' => array(
				'400',
			),
			'Mr De Haviland' => array(
				'400',
			),
			'Mrs Saint Delafield' => array(
				'400',
			),
			'Mrs Sheppards' => array(
				'400',
			),
			'Muli' => array(
				'300',
				'400',
			),
			'Mystery Quest' => array(
				'400',
			),
			'NTR' => array(
				'400',
			),
			'Neucha' => array(
				'400',
			),
			'Neuton' => array(
				'200',
				'300',
				'400',
				'700',
				'800',
			),
			'New Rocker' => array(
				'400',
			),
			'News Cycle' => array(
				'400',
				'700',
			),
			'Niconne' => array(
				'400',
			),
			'Nixie One' => array(
				'400',
			),
			'Nobile' => array(
				'400',
				'700',
			),
			'Nokora' => array(
				'400',
				'700',
			),
			'Norican' => array(
				'400',
			),
			'Nosifer' => array(
				'400',
			),
			'Nothing You Could Do' => array(
				'400',
			),
			'Noticia Text' => array(
				'400',
				'700',
			),
			'Noto Sans' => array(
				'400',
				'700',
			),
			'Noto Serif' => array(
				'400',
				'700',
			),
			'Nova Cut' => array(
				'400',
			),
			'Nova Flat' => array(
				'400',
			),
			'Nova Mono' => array(
				'400',
			),
			'Nova Oval' => array(
				'400',
			),
			'Nova Round' => array(
				'400',
			),
			'Nova Script' => array(
				'400',
			),
			'Nova Slim' => array(
				'400',
			),
			'Nova Square' => array(
				'400',
			),
			'Numans' => array(
				'400',
			),
			'Nunito' => array(
				'300',
				'400',
				'700',
			),
			'Odor Mean Chey' => array(
				'400',
			),
			'Offside' => array(
				'400',
			),
			'Old Standard TT' => array(
				'400',
				'700',
			),
			'Oldenburg' => array(
				'400',
			),
			'Oleo Script' => array(
				'400',
				'700',
			),
			'Oleo Script Swash Caps' => array(
				'400',
				'700',
			),
			'Open Sans' => array(
				'300',
				'400',
				'600',
				'700',
				'800',
			),
			'Open Sans Condensed' => array(
				'300',
				'700',
			),
			'Oranienbaum' => array(
				'400',
			),
			'Orbitron' => array(
				'400',
				'500',
				'700',
				'900',
			),
			'Oregano' => array(
				'400',
			),
			'Orienta' => array(
				'400',
			),
			'Original Surfer' => array(
				'400',
			),
			'Oswald' => array(
				'300',
				'400',
				'700',
			),
			'Over the Rainbow' => array(
				'400',
			),
			'Overlock' => array(
				'400',
				'700',
				'900',
			),
			'Overlock SC' => array(
				'400',
			),
			'Ovo' => array(
				'400',
			),
			'Oxygen' => array(
				'300',
				'400',
				'700',
			),
			'Oxygen Mono' => array(
				'400',
			),
			'PT Mono' => array(
				'400',
			),
			'PT Sans' => array(
				'400',
				'700',
			),
			'PT Sans Caption' => array(
				'400',
				'700',
			),
			'PT Sans Narrow' => array(
				'400',
				'700',
			),
			'PT Serif' => array(
				'400',
				'700',
			),
			'PT Serif Caption' => array(
				'400',
			),
			'Pacifico' => array(
				'400',
			),
			'Palanquin' => array(
				'100',
				'200',
				'300',
				'400',
				'500',
				'600',
				'700',
			),
			'Palanquin Dark' => array(
				'400',
				'500',
				'600',
				'700',
			),
			'Paprika' => array(
				'400',
			),
			'Parisienne' => array(
				'400',
			),
			'Passero One' => array(
				'400',
			),
			'Passion One' => array(
				'400',
				'700',
				'900',
			),
			'Pathway Gothic One' => array(
				'400',
			),
			'Patrick Hand' => array(
				'400',
			),
			'Patrick Hand SC' => array(
				'400',
			),
			'Patua One' => array(
				'400',
			),
			'Paytone One' => array(
				'400',
			),
			'Peddana' => array(
				'400',
			),
			'Peralta' => array(
				'400',
			),
			'Permanent Marker' => array(
				'400',
			),
			'Petit Formal Script' => array(
				'400',
			),
			'Petrona' => array(
				'400',
			),
			'Philosopher' => array(
				'400',
				'700',
			),
			'Piedra' => array(
				'400',
			),
			'Pinyon Script' => array(
				'400',
			),
			'Pirata One' => array(
				'400',
			),
			'Plaster' => array(
				'400',
			),
			'Play' => array(
				'400',
				'700',
			),
			'Playball' => array(
				'400',
			),
			'Playfair Display' => array(
				'400',
				'700',
				'900',
			),
			'Playfair Display SC' => array(
				'400',
				'700',
				'900',
			),
			'Podkova' => array(
				'400',
				'700',
			),
			'Poiret One' => array(
				'400',
			),
			'Poller One' => array(
				'400',
			),
			'Poly' => array(
				'400',
			),
			'Pompiere' => array(
				'400',
			),
			'Pontano Sans' => array(
				'400',
			),
			'Poppins' => array(
				'300',
				'400',
				'500',
				'600',
				'700',
			),
			'Port Lligat Sans' => array(
				'400',
			),
			'Port Lligat Slab' => array(
				'400',
			),
			'Pragati Narrow' => array(
				'400',
				'700',
			),
			'Prata' => array(
				'400',
			),
			'Preahvihear' => array(
				'400',
			),
			'Press Start 2P' => array(
				'400',
			),
			'Princess Sofia' => array(
				'400',
			),
			'Prociono' => array(
				'400',
			),
			'Prosto One' => array(
				'400',
			),
			'Puritan' => array(
				'400',
				'700',
			),
			'Purple Purse' => array(
				'400',
			),
			'Quando' => array(
				'400',
			),
			'Quantico' => array(
				'400',
				'700',
			),
			'Quattrocento' => array(
				'400',
				'700',
			),
			'Quattrocento Sans' => array(
				'400',
				'700',
			),
			'Questrial' => array(
				'400',
			),
			'Quicksand' => array(
				'300',
				'400',
				'700',
			),
			'Quintessential' => array(
				'400',
			),
			'Qwigley' => array(
				'400',
			),
			'Racing Sans One' => array(
				'400',
			),
			'Radley' => array(
				'400',
			),
			'Rajdhani' => array(
				'300',
				'400',
				'500',
				'600',
				'700',
			),
			'Raleway' => array(
				'100',
				'200',
				'300',
				'400',
				'500',
				'600',
				'700',
				'800',
				'900',
			),
			'Raleway Dots' => array(
				'400',
			),
			'Ramabhadra' => array(
				'400',
			),
			'Ramaraja' => array(
				'400',
			),
			'Rambla' => array(
				'400',
				'700',
			),
			'Rammetto One' => array(
				'400',
			),
			'Ranchers' => array(
				'400',
			),
			'Rancho' => array(
				'400',
			),
			'Ranga' => array(
				'400',
				'700',
			),
			'Rationale' => array(
				'400',
			),
			'Ravi Prakash' => array(
				'400',
			),
			'Redressed' => array(
				'400',
			),
			'Reenie Beanie' => array(
				'400',
			),
			'Revalia' => array(
				'400',
			),
			'Rhodium Libre' => array(
				'400',
			),
			'Ribeye' => array(
				'400',
			),
			'Ribeye Marrow' => array(
				'400',
			),
			'Righteous' => array(
				'400',
			),
			'Risque' => array(
				'400',
			),
			'Roboto' => array(
				'100',
				'300',
				'400',
				'500',
				'700',
				'900',
			),
			'Roboto Condensed' => array(
				'300',
				'400',
				'700',
			),
			'Roboto Mono' => array(
				'100',
				'300',
				'400',
				'500',
				'700',
			),
			'Roboto Slab' => array(
				'100',
				'300',
				'400',
				'700',
			),
			'Rochester' => array(
				'400',
			),
			'Rock Salt' => array(
				'400',
			),
			'Rokkitt' => array(
				'400',
				'700',
			),
			'Romanesco' => array(
				'400',
			),
			'Ropa Sans' => array(
				'400',
			),
			'Rosario' => array(
				'400',
				'700',
			),
			'Rosarivo' => array(
				'400',
			),
			'Rouge Script' => array(
				'400',
			),
			'Rozha One' => array(
				'400',
			),
			'Rubik' => array(
				'300',
				'400',
				'500',
				'700',
				'900',
			),
			'Rubik Mono One' => array(
				'400',
			),
			'Rubik One' => array(
				'400',
			),
			'Ruda' => array(
				'400',
				'700',
				'900',
			),
			'Rufina' => array(
				'400',
				'700',
			),
			'Ruge Boogie' => array(
				'400',
			),
			'Ruluko' => array(
				'400',
			),
			'Rum Raisin' => array(
				'400',
			),
			'Ruslan Display' => array(
				'400',
			),
			'Russo One' => array(
				'400',
			),
			'Ruthie' => array(
				'400',
			),
			'Rye' => array(
				'400',
			),
			'Sacramento' => array(
				'400',
			),
			'Sahitya' => array(
				'400',
				'700',
			),
			'Sail' => array(
				'400',
			),
			'Salsa' => array(
				'400',
			),
			'Sanchez' => array(
				'400',
			),
			'Sancreek' => array(
				'400',
			),
			'Sansita One' => array(
				'400',
			),
			'Sarala' => array(
				'400',
				'700',
			),
			'Sarina' => array(
				'400',
			),
			'Sarpanch' => array(
				'400',
				'500',
				'600',
				'700',
				'800',
				'900',
			),
			'Satisfy' => array(
				'400',
			),
			'Scada' => array(
				'400',
				'700',
			),
			'Scheherazade' => array(
				'400',
				'700',
			),
			'Schoolbell' => array(
				'400',
			),
			'Seaweed Script' => array(
				'400',
			),
			'Sevillana' => array(
				'400',
			),
			'Seymour One' => array(
				'400',
			),
			'Shadows Into Light' => array(
				'400',
			),
			'Shadows Into Light Two' => array(
				'400',
			),
			'Shanti' => array(
				'400',
			),
			'Share' => array(
				'400',
				'700',
			),
			'Share Tech' => array(
				'400',
			),
			'Share Tech Mono' => array(
				'400',
			),
			'Shojumaru' => array(
				'400',
			),
			'Short Stack' => array(
				'400',
			),
			'Siemreap' => array(
				'400',
			),
			'Sigmar One' => array(
				'400',
			),
			'Signika' => array(
				'300',
				'400',
				'600',
				'700',
			),
			'Signika Negative' => array(
				'300',
				'400',
				'600',
				'700',
			),
			'Simonetta' => array(
				'400',
				'900',
			),
			'Sintony' => array(
				'400',
				'700',
			),
			'Sirin Stencil' => array(
				'400',
			),
			'Six Caps' => array(
				'400',
			),
			'Skranji' => array(
				'400',
				'700',
			),
			'Slabo 13px' => array(
				'400',
			),
			'Slabo 27px' => array(
				'400',
			),
			'Slackey' => array(
				'400',
			),
			'Smokum' => array(
				'400',
			),
			'Smythe' => array(
				'400',
			),
			'Sniglet' => array(
				'400',
				'800',
			),
			'Snippet' => array(
				'400',
			),
			'Snowburst One' => array(
				'400',
			),
			'Sofadi One' => array(
				'400',
			),
			'Sofia' => array(
				'400',
			),
			'Sonsie One' => array(
				'400',
			),
			'Sorts Mill Goudy' => array(
				'400',
			),
			'Source Code Pro' => array(
				'200',
				'300',
				'400',
				'500',
				'600',
				'700',
				'900',
			),
			'Source Sans Pro' => array(
				'200',
				'300',
				'400',
				'600',
				'700',
				'900',
			),
			'Source Serif Pro' => array(
				'400',
				'600',
				'700',
			),
			'Special Elite' => array(
				'400',
			),
			'Spicy Rice' => array(
				'400',
			),
			'Spinnaker' => array(
				'400',
			),
			'Spirax' => array(
				'400',
			),
			'Squada One' => array(
				'400',
			),
			'Sree Krushnadevaraya' => array(
				'400',
			),
			'Stalemate' => array(
				'400',
			),
			'Stalinist One' => array(
				'400',
			),
			'Stardos Stencil' => array(
				'400',
				'700',
			),
			'Stint Ultra Condensed' => array(
				'400',
			),
			'Stint Ultra Expanded' => array(
				'400',
			),
			'Stoke' => array(
				'300',
				'400',
			),
			'Strait' => array(
				'400',
			),
			'Sue Ellen Francisco' => array(
				'400',
			),
			'Sumana' => array(
				'400',
				'700',
			),
			'Sunshiney' => array(
				'400',
			),
			'Supermercado One' => array(
				'400',
			),
			'Sura' => array(
				'400',
				'700',
			),
			'Suranna' => array(
				'400',
			),
			'Suravaram' => array(
				'400',
			),
			'Suwannaphum' => array(
				'400',
			),
			'Swanky and Moo Moo' => array(
				'400',
			),
			'Syncopate' => array(
				'400',
				'700',
			),
			'Tangerine' => array(
				'400',
				'700',
			),
			'Taprom' => array(
				'400',
			),
			'Tauri' => array(
				'400',
			),
			'Teko' => array(
				'300',
				'400',
				'500',
				'600',
				'700',
			),
			'Telex' => array(
				'400',
			),
			'Tenali Ramakrishna' => array(
				'400',
			),
			'Tenor Sans' => array(
				'400',
			),
			'Text Me One' => array(
				'400',
			),
			'The Girl Next Door' => array(
				'400',
			),
			'Tienne' => array(
				'400',
				'700',
				'900',
			),
			'Tillana' => array(
				'400',
				'500',
				'600',
				'700',
				'800',
			),
			'Timmana' => array(
				'400',
			),
			'Tinos' => array(
				'400',
				'700',
			),
			'Titan One' => array(
				'400',
			),
			'Titillium Web' => array(
				'200',
				'300',
				'400',
				'600',
				'700',
				'900',
			),
			'Trade Winds' => array(
				'400',
			),
			'Trocchi' => array(
				'400',
			),
			'Trochut' => array(
				'400',
				'700',
			),
			'Trykker' => array(
				'400',
			),
			'Tulpen One' => array(
				'400',
			),
			'Ubuntu' => array(
				'300',
				'400',
				'500',
				'700',
			),
			'Ubuntu Condensed' => array(
				'400',
			),
			'Ubuntu Mono' => array(
				'400',
				'700',
			),
			'Ultra' => array(
				'400',
			),
			'Uncial Antiqua' => array(
				'400',
			),
			'Underdog' => array(
				'400',
			),
			'Unica One' => array(
				'400',
			),
			'UnifrakturCook' => array(
				'700',
			),
			'UnifrakturMaguntia' => array(
				'400',
			),
			'Unkempt' => array(
				'400',
				'700',
			),
			'Unlock' => array(
				'400',
			),
			'Unna' => array(
				'400',
			),
			'VT323' => array(
				'400',
			),
			'Vampiro One' => array(
				'400',
			),
			'Varela' => array(
				'400',
			),
			'Varela Round' => array(
				'400',
			),
			'Vast Shadow' => array(
				'400',
			),
			'Vesper Libre' => array(
				'400',
				'500',
				'700',
				'900',
			),
			'Vibur' => array(
				'400',
			),
			'Vidaloka' => array(
				'400',
			),
			'Viga' => array(
				'400',
			),
			'Voces' => array(
				'400',
			),
			'Volkhov' => array(
				'400',
				'700',
			),
			'Vollkorn' => array(
				'400',
				'700',
			),
			'Voltaire' => array(
				'400',
			),
			'Waiting for the Sunrise' => array(
				'400',
			),
			'Wallpoet' => array(
				'400',
			),
			'Walter Turncoat' => array(
				'400',
			),
			'Warnes' => array(
				'400',
			),
			'Wellfleet' => array(
				'400',
			),
			'Wendy One' => array(
				'400',
			),
			'Wire One' => array(
				'400',
			),
			'Work Sans' => array(
				'100',
				'200',
				'300',
				'400',
				'500',
				'600',
				'700',
				'800',
				'900',
			),
			'Yanone Kaffeesatz' => array(
				'200',
				'300',
				'400',
				'700',
			),
			'Yantramanav' => array(
				'100',
				'300',
				'400',
				'500',
				'700',
				'900',
			),
			'Yellowtail' => array(
				'400',
			),
			'Yeseva One' => array(
				'400',
			),
			'Yesteryear' => array(
				'400',
			),
			'Zeyada' => array(
				'400',
			),
		);

	}

endif;
