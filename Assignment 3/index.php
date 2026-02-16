<!DOCTYPE html>
<html>
<body>

<h2>Length Converter</h2>

<form method="post" action="">
    <input type="number" name="value" step="any" placeholder="Enter Value" required>
    <select name="from_unit">
        <option value="m">Meters</option>
        <option value="km">Kilometers</option>
        <option value="mile">Miles</option>
    </select>
    To
    <select name="to_unit">
        <option value="m">Meters</option>
        <option value="km">Kilometers</option>
        <option value="mile">Miles</option>
    </select>
    <input type="submit" name="convert" value="Convert">
</form>

<?php
if(isset($_POST['convert'])){
    $value = $_POST['value'];
    $from_unit = $_POST['from_unit'];
    $to_unit = $_POST['to_unit'];
    $result = 0;

    // Convert everything to meters (base unit) first
    switch($from_unit){
        case 'm': $meters = $value; break;
        case 'km': $meters = $value * 1000; break;
        case 'mile': $meters = $value * 1609.34; break;
        default: $meters = 0;
    }

    // Convert from meters to target unit
    switch($to_unit){
        case 'm': $result = $meters; break;
        case 'km': $result = $meters / 1000; break;
        case 'mile': $result = $meters / 1609.34; break;
        default: $result = 0;
    }

    echo "<h3>Result: " . $value . " " . $from_unit . " = " . round($result, 4) . " " . $to_unit . "</h3>";
}
?>

</body>
</html>