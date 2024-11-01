<?php
// Database connection
require 'db.php'; // Ensure you have your DB connection here

$upload_folder = 'upload/';
$files_in_folder = scandir($upload_folder);

// Fetch all image names from your 'recipes' table (or any other table that stores image paths)
$db_images = [];
$result = mysqli_query($conn, "SELECT image FROM recipes");
while ($row = mysqli_fetch_assoc($result)) {
    $db_images[] = $row['image'];  // Collect image names from DB
}

// Loop through files in upload folder
foreach ($files_in_folder as $file) {
    if ($file !== '.' && $file !== '..') { // Ignore special directories
        // If the file is not found in the database array
        if (!in_array($file, $db_images)) {
            // Delete the file
            unlink($upload_folder . $file);
            echo "Deleted: " . $file . "<br>";
        }
    }
}

echo "Cleanup completed!";
?>
