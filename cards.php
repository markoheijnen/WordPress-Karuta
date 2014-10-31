<?php
ini_set( 'display_errors', 1 );

$cards = array();
$file = 'assets/2014.csv';

if ( ( $handle = fopen( $file, "r") ) !== FALSE ) {

	$data = fgetcsv( $handle, 0, "\r" );

	array_shift( $data );
	array_shift( $data );

	foreach ( $data as $line ) {

		$no_comma = str_replace( ',', '', $line );

		if ( '' == $no_comma ) {
			continue;
		}

		$l = str_getcsv( $line, "," );

		$cards[] = array(
			array(
				'reading_card' => $l[0],
				'getting_card' => $l[1],
				'meta' => array(
					'authors'  => $l[2],
					'category' => $l[3],
				),
			),
		);

	}

	fclose($handle);
}

$GLOBALS['cards'] = $cards;

echo '<pre>';
var_dump( $GLOBALS );
echo '</pre>';

exit;

$GLOBALS['cards'] = array(
	array(
		'reading_card' => __(),
		'playing_card' => __(),
		'meta' => array(
			'plugin_slug'    => '',
			'code_reference' => '',
		),
	),
);
