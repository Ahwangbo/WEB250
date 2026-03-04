<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Storm Data Analyzer</title>
    <style>
        body { 
        color: blue; 
        background-color: aqua; 
        font-family: sans-serif; 
        margin: 20px; 
        text-align: center;
    }
        table { 
        border-collapse: collapse; 
        width: 100%; 
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
        /* Color coding for categories */
        .cat-Tropical_Storm { background-color: #add8e6; } /* Light Blue */
        .cat-1 { background-color: #ffeb9c; } /* Light Yellow */
        .cat-2 { background-color: #ffc; }    /* Yellow */
        .cat-3 { background-color: #ffaa4c; } /* Orange */
        .cat-4 { background-color: #ff7f50; } /* Coral */
        .cat-5 { background-color: #ff4500; } /* OrangeRed */
        .cat-Tropical_Depression { background-color: #e0e0e0; } /* Light Gray */
    </style>
</head>
<body>

    <h2>Upload Storm Data CSV</h2>

    <form action="storm_tracker.php" method="post" enctype="multipart/form-data">
        Select CSV file to upload:
        <input type="file" name="stormFile" id="stormFile" accept=".csv">
        <input type="submit" value="Upload and Analyze" name="submit">
    </form>

    <?php
    // --- Configuration and Functions ---

    /**
     * Converts kilometers per hour (kph) to miles per hour (mph).
     * The conversion factor is approximately 0.621371.
     * @param float $kph Wind speed in kph.
     * @return float Wind speed in mph.
     */
    function kphToMph($kph) {
        return $kph * 0.621371;
    }

    /**
     * Determines the Saffir-Simpson category based on wind speed in kph.
     * Scale information from the [NOAA Hurricane Center](https://www.nhc.noaa.gov/aboutsshws.php).
     * @param float $kph Wind speed in kph.
     * @return string The category or classification.
     */
    function getSaffirSimpsonCategory($kph) {
        if ($kph >= 252) {
            return '5'; // 252 km/h or higher
        } elseif ($kph >= 209) {
            return '4'; // 209-251 km/h
        } elseif ($kph >= 178) {
            return '3'; // 178-208 km/h
        } elseif ($kph >= 154) {
            return '2'; // 154-177 km/h
        } elseif ($kph >= 119) {
            return '1'; // 119-153 km/h
        } elseif ($kph >= 63) {
            return 'Tropical Storm'; // 63-117 km/h
        } else {
            return 'Tropical Depression'; // 0-62 kph
        }
    }

    // --- Main Logic ---

    if (isset($_POST['submit']) && isset($_FILES['stormFile'])) {
        $file = $_FILES['stormFile']['tmp_name'];

        if (($handle = fopen($file, "r")) !== FALSE) {
            $storms = [];
            $header = fgetcsv($handle); // Skip header row

            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                // Assuming CSV columns are Date, Storm Name, Max Sustained Winds (kph)
                if (count($data) >= 3) {
                    $kph = (float)$data[2];
                    $storms[] = [
                        'date' => $data[0],
                        'name' => $data[1],
                        'kph' => $kph,
                        'mph' => round(kphToMph($kph), 2),
                        'category' => getSaffirSimpsonCategory($kph)
                    ];
                }
            }
            fclose($handle);

            // Sort data by wind intensity in decreasing order (highest wind first)
            usort($storms, function ($a, $b) {
                return $b['kph'] <=> $a['kph'];
            });

            // Display the data
            echo "<h3>Analyzed Storm Data (Sorted by Intensity)</h3>";
            echo "<table>";
            echo "<thead><tr><th>Date</th><th>Storm Name</th><th>Max Winds (kph)</th><th>Max Winds (mph)</th><th>Category</th></tr></thead>";
            echo "<tbody>";

            foreach ($storms as $storm) {
                // Create a CSS class name from the category (e.g., 'Tropical Storm' becomes 'cat-Tropical_Storm')
                $categoryClass = 'cat-' . str_replace(' ', '_', $storm['category']);
                echo "<tr class='{$categoryClass}'>";
                echo "<td>" . htmlspecialchars($storm['date']) . "</td>";
                echo "<td>" . htmlspecialchars($storm['name']) . "</td>";
                echo "<td>" . htmlspecialchars($storm['kph']) . "</td>";
                echo "<td>" . htmlspecialchars($storm['mph']) . "</td>";
                echo "<td>" . htmlspecialchars($storm['category']) . "</td>";
                echo "</tr>";
            }

            echo "</tbody>";
            echo "</table>";

        } else {
            echo "<p style='color: red;'>Error opening the uploaded file.</p>";
        }
    } elseif (isset($_POST['submit'])) {
        echo "<p style='color: red;'>File upload failed. Please try again.</p>";
    }
    ?>

</body>
</html>