<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

function rupiah($num)
{
    if (!empty($num) && $num != 0) {
        $format_rupiah = "Rp" . number_format($num, 2, ',', '.');
    } else {
        $format_rupiah = 0;
    }
    return $format_rupiah;
}

function get_alphabet_list()
{
    // list letter alphabet
    $letter = range('A', 'Z'); //alphabets, index 0 - 25
    $letters = $letter;

    foreach ($letter as $i => $let) {
        foreach ($letter as $l) {
            $letters[] = $let . $l; // it will make 'AA' until 'ZZ'
        }
    }

    return $letters;
}

function parseToFloat($input)
{
    // Remove commas from the input
    $inputWithoutCommas = str_replace(',', '', $input);

    // Convert the string to a float
    $floatValue = floatval($inputWithoutCommas);

    return $floatValue;
}

function formatIndonesianDateTime($timestamp = null)
{
    if ($timestamp === null) {
        $timestamp = time();
    }

    // English day and month names
    $english_days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
    $english_months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];

    // Indonesian translations
    $indonesian_days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
    $indonesian_months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

    // Get date components
    $day = date('l', $timestamp);
    $date = date('d', $timestamp);
    $month = date('F', $timestamp);
    $year = date('Y', $timestamp);
    $time = date('H:i:s', $timestamp);

    // Translate day and month
    $indonesian_day = $indonesian_days[array_search($day, $english_days)];
    $indonesian_month = $indonesian_months[array_search($month, $english_months)];

    return $indonesian_day . ', ' . $date . ' ' . $indonesian_month . ' ' . $year . ' | ' . $time;
}
