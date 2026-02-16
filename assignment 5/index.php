<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Earthquake Data Viewer</title>
    <style>
        body { 
        color: blue;
		background-color: aqua;
        font-family: sans-serif; 
        padding: 20px; 
         text-align: center;
        }
        h2 {
  		text-align: center;
		}
        table { 
        border-collapse: collapse; 
        width: 100%; 
        margin-top: 20px; 
        }
        th, td {
        border: 1px solid lightcoral; 
        padding: 8px; 
        text-align: center; 
    }
        th { 
        background-color: lightpink; 
    }
        
        /* Magnitude Color Coding */
        .mag-micro { background-color: #e0f7fa; } /* < 2.0 */
        .mag-minor { background-color: #c8e6c9; } /* 2.0-3.9 */
        .mag-light { background-color: #fff9c4; } /* 4.0-4.9 */
        .mag-moderate { background-color: #ffe0b2; } /* 5.0-5.9 */
        .mag-strong { background-color: #ffccbc; } /* 6.0-6.9 */
        .mag-major { background-color: #ffab91; } /* 7.0-7.9 */
        .mag-great { background-color: #ef9a9a; } /* 8.0+ */
    </style>
</head>

<h2>Upload Earthquake CSV</h2>
<form action="" method="post" enctype="multipart/form-data">
    <input type="file" name="csv_file" accept=".csv" required>
    <input type="submit" name="submit" value="Upload and Process">
</form>

<?php
if (isset($_POST['submit']) && isset($_FILES['csv_file'])) {
    $file = $_FILES['csv_file']['tmp_name'];
    
    if (($handle = fopen($file, "r")) !== FALSE) {
        echo "<table><tr><th>Date</th><th>Name</th><th>Magnitude</th><th>Description</th></tr>";
        
        // Skip header if necessary
        // fgetcsv($handle); 

        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            $date = $data[0];
            $name = $data[1];
            $mag = floatval($data[2]);
            $class = '';
            $desc = '';

            // Richter scale range classification [9]
            if ($mag < 2.0) { $desc = "Micro"; $class = "micro"; }
            elseif ($mag < 4.0) { $desc = "Minor"; $class = "minor"; }
            elseif ($mag < 5.0) { $desc = "Light"; $class = "light"; }
            elseif ($mag < 6.0) { $desc = "Moderate"; $class = "moderate"; }
            elseif ($mag < 7.0) { $desc = "Strong"; $class = "strong"; }
            elseif ($mag < 8.0) { $desc = "Major"; $class = "major"; }
            else { $desc = "Great"; $class = "great"; }

            echo "<tr class='$class'><td>$date</td><td>$name</td><td>$mag</td><td>$desc</td></tr>";
        }
        fclose($handle);
        echo "</table>";
    }
}
?>
</body>
</html>