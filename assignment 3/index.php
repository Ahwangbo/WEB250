<?php 

// Converts a Fahrenheit temperature to Celsius using a GET request and
// converts a Celsius temperature to Fahrenheit using a POST request.
//
// References:
//  https://www.mathsisfun.com/temperature-conversion.html
//  https://en.wikibooks.org/wiki/PHP_Programming
//  https://www.tutorialspoint.com/php/php_get_post.htm

$fahrenheit = process_fahrenheit();
$celsius = process_celsius();

function process_fahrenheit() {
    if (isset($_GET["fahrenheit"]) && is_numeric($_GET["fahrenheit"])) {
        $fahrenheit = $_GET["fahrenheit"];
        $celsius = ($fahrenheit - 32) * 5 / 9;
        $result = $fahrenheit . "° Fahrenheit is " .
            $celsius . "° Celsius";
    }
    else {
        $result = "";
    }

    return $result;
}

function process_celsius() {
    if (isset($_POST["celsius"]) && is_numeric($_POST["celsius"])) {
        $celsius = $_POST["celsius"];
        $fahrenheit = $celsius * 9 / 5 + 32;
        $result = $celsius . "° Celsius is " .
            $fahrenheit . "° Fahrenheit";
    }
    else {
        $result = "";
    }

    return $result;
}

?>
