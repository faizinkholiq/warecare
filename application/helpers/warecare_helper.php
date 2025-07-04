<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

function rupiah($num){
    if (!empty($num) && $num != 0){
        $format_rupiah = "Rp" . number_format($num,2,',','.');
    }else{
        $format_rupiah = 0;
    }
    return $format_rupiah;
}

function get_alphabet_list()
{
	// list letter alphabet
	$letter = range('A', 'Z'); //alphabets, index 0 - 25
	$letters = $letter;

	foreach($letter as $i => $let) {
		foreach($letter as $l) {
			$letters[] = $let . $l; // it will make 'AA' until 'ZZ'
		}
	}

	return $letters;
}

function parseToFloat($input) {
    // Remove commas from the input
    $inputWithoutCommas = str_replace(',', '', $input);

    // Convert the string to a float
    $floatValue = floatval($inputWithoutCommas);

    return $floatValue;
}