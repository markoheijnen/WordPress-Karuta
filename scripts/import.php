<?php
ini_set( 'display_errors', 1 );
header('content-type: text/html; charset=utf-8');

new Card_Importer;

class Card_Importer {

	public function __construct() {
		$file  = '../assets/2014.csv';
		$cards = $this->read_csv( $file );
		$this->print_array_to_file( '../card-decks/default.php', $cards );

		echo '<pre>';
		var_dump( $cards );
		echo '</pre>';
	}

	public function read_csv( $file ) {
		$cards = array();

		if ( ( $handle = fopen( $file, "r") ) !== FALSE ) {
			// Ignore first two lines. They are labels
			fgetcsv( $handle, 0, ',' );

			while ( ( $line = fgetcsv( $handle, 0, ',' ) ) !== false ) {
				if ( ! $line[1] || ! $line[2] ) {
					continue;
				}

				$cards[] = array(
					'reading_card' => mb_convert_encoding( $line[1], 'UTF-8', array( 'EUC-JP', 'SHIFT-JIS', 'AUTO' ) ),
					'getting_card' => $line[2],
					'meta' => array(
						'category' => $line[4],
					),
				);
			}

			fclose( $handle );
		}

		return $cards;
	}

	public function print_array_to_file( $file, $array ) {
		$result = var_export( $array, true );
		$result = preg_replace( '/^  |\G  /m', "\t", $result ); // Tabs for indentation
		$result = preg_replace( '(\d+\s=>)', "", $result ); // No numeric arrays
		$result = "<?php" . PHP_EOL . '$cards = ' . $result . ';'; // Make it trully a readable PHP file
		$result = preg_replace_callback( "/'(.*?)' => '(.*?)',/", array( $this, 'add_translatable_function' ), $result );

		return (bool) file_put_contents( $file, $result );
	}


	public function add_translatable_function( $matches ) {
		// Only translate given array keys
		if ( ! in_array( $matches[1], array( 'reading_card', 'getting_card' ) ) )  {
			return $matches[0];
		}

		return "'" . $matches[1] . "' => __( '" . $matches[2] . "', 'karuta' ),";
	}

}
