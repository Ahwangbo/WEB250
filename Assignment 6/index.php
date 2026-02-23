<?php
// Function to convert kilometers per hour to miles per hour
function kphToMph($kph) {
    // Multiply kph by 0.621371 to get mph
    return $kph * 0.621371;
}

// Function to determine the Saffir-Simpson category based on wind speed in mph
function getSaffirSimpsonCategory($mph) {
    if ($mph >= 157) {
        return 5;
    } elseif ($mph >= 130) {
        return 4;
    } elseif ($mph >= 111) {
        return 3;
    } elseif ($mph >= 96) {
        return 2;
    } elseif ($mph >= 74) {
        return 1;
    } else {
        // Storms below 74 mph are not hurricanes on this scale
        return 'Tropical Storm or Depression';
    }
}

// Function to get CSS color based on category
function getCategoryColor($category) {
    switch ($category) {
        case 5: return '#FF0000'; // Red
        case 4: return '#FF8000'; // Orange
        case 3: return '#FFFF00'; // Yellow
        case 2: return '#00FF00'; // Green
        case 1: return '#0000FF'; // Blue
        default: return '#808080'; // Gray for non-hurricanes
    }
}

$stormData = [];
$message = '';

if (isset($_FILES['csv_file']) && $_FILES['csv_file']['error'] == UPLOAD_ERR_OK) {
    $fileTmpPath = $_FILES['csv_file']['tmp_name'];
    $fileMimeType = mime_content_type($fileTmpPath);
    $allowedMimes = ['text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'text/plain'];

    // Basic MIME type check
    if (in_array($fileMimeType, $allowedMimes)) {
        if (($handle = fopen($fileTmpPath, "r")) !== FALSE) {
            // Skip the header row
            fgetcsv($handle);

            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                // Assuming CSV columns are: Date, Storm Name, Max Sustained Winds (kph)
                if (count($data) == 3) {
                    $date = htmlspecialchars($data[0]);
                    $name = htmlspecialchars($data[1]);
                    $kph = (float)$data[2];
                    $mph = kphToMph($kph);
                    $category = getSaffirSimpsonCategory($mph);

                    $stormData[] = [
                        'date' => $date,
                        'name' => $name,
                        'kph' => round($kph, 2),
                        'mph' => round($mph, 2),
                        'category' => $category
                    ];
                }
            }
            fclose($handle);

            // Sort the array by 'kph' (intensity) in descending order
            array_multisort(array_column($stormData, 'kph'), SORT_DESC, $stormData);
            
            $message = "File successfully uploaded and processed. Data sorted by intensity.";

        } else {
            $message = "Error opening the uploaded file.";
        }
    } else {
        $message = "Invalid file type. Please upload a CSV file.";
    }
} elseif (isset($_POST['upload']) && $_FILES['csv_file']['error'] != UPLOAD_ERR_NO_FILE) {
    $message = "Error during file upload. Error code: " . $_FILES['csv_file']['error'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Storm Data Processor</title>
    <style>
        table {
            border-collapse: collapse;
            width: 50%;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .category-cell {
            font-weight: bold;
        }
    </style>
</head>
<body>

    <h1>Upload Storm Data CSV</h1>
    <?php if ($message): ?>
        <p><strong><?php echo $message; ?></strong></p>
    <?php endif; ?>

    <form action="storm_app.php" method="post" enctype="multipart/form-data">
        Select CSV file to upload:
        <input type="file" name="csv_file" id="csv_file" accept=".csv">
        <input type="submit" value="Upload and Process" name="upload">
    </form>

    <?php if (!empty($stormData)): ?>
        <h2>Storm Data (Sorted by Intensity, Descending)</h2>
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Storm Name</th>
                    <th>Max Winds (kph)</th>
                    <th>Max Winds (mph)</th>
                    <th>Saffir-Simpson Category</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($stormData as $storm): ?>
                    <tr>
                        <td><?php echo $storm['date']; ?></td>
                        <td><?php echo $storm['name']; ?></td>
                        <td><?php echo $storm['kph']; ?></td>
                        <td><?php echo $storm['mph']; ?></td>
                        <td class="category-cell" style="background-color: <?php echo getCategoryColor($storm['category']); ?>;">
                            <?php echo $storm['category']; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

</body>
</html>