<?php

if (!function_exists('number_format_id')) {
    /**
     * Format angka locale id-ID (ribuan ".", desimal ","), setara number_format(..., $decimals, ',', '.').
     */
    function number_format_id(float|int|string|null $number, int $decimals = 0): string
    {
        if ($number === null || $number === '') {
            return number_format(0.0, $decimals, ',', '.');
        }

        return number_format((float) $number, $decimals, ',', '.');
    }
}
