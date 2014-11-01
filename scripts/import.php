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
			$data = fgetcsv( $handle, 0, "\r" );

			array_shift( $data );
			array_shift( $data );

			foreach ( $data as $line ) {
				$no_comma = str_replace( ',', '', $line );

				if ( ! $no_comma ) {
					continue;
				}

				$l = str_getcsv( $line, "," );

				$cards[] = array(
					'reading_card' => mb_convert_encoding( $l[0], 'UTF-8', array( 'EUC-JP', 'SHIFT-JIS', 'AUTO' ) ),
					'getting_card' => $l[1],
					'meta' => array(
						'category' => $l[3],
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

		return (bool) file_put_contents( $file, $result );
	}

}
