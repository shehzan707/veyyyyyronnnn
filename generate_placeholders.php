<?php
/**
 * Generate Placeholder Images for Bulk Import Testing
 * Run this script: php generate_placeholders.php
 */

$images = [
    'ralph_lauren_polo.jpg' => 'Ralph Lauren Polo',
    'ralph_lauren_oxford.jpg' => 'Oxford Shirt',
    'ralph_lauren_sweater.jpg' => 'Sweater',
    'ralph_lauren_chino.jpg' => 'Chino Pants',
    'ralph_lauren_vneck.jpg' => 'V-Neck Shirt',
    'ralph_lauren_jeans.jpg' => 'Denim Jeans',
    'ralph_lauren_windbreaker.jpg' => 'Windbreaker',
    'ralph_lauren_hoodie.jpg' => 'Fleece Hoodie',
    'ralph_lauren_cargo.jpg' => 'Cargo Shorts',
    'ralph_lauren_thermal.jpg' => 'Thermal Shirt',
];

$bulkDir = __DIR__ . '/public/uploads/bulk';

// Create directory if it doesn't exist
if (!is_dir($bulkDir)) {
    mkdir($bulkDir, 0755, true);
}

foreach ($images as $filename => $label) {
    $filepath = $bulkDir . '/' . $filename;
    
    // Create a simple colored image with text
    $width = 400;
    $height = 500;
    
    // Create image
    $image = imagecreatetruecolor($width, $height);
    
    // Define colors
    $bgColor = imagecolorallocate($image, 52, 73, 94);      // Dark blue background
    $textColor = imagecolorallocate($image, 255, 255, 255); // White text
    $accentColor = imagecolorallocate($image, 52, 211, 153); // Green accent
    
    // Fill background
    imagefilledrectangle($image, 0, 0, $width, $height, $bgColor);
    
    // Draw accent bar
    imagefilledrectangle($image, 0, 0, $width, 60, $accentColor);
    
    // Add text
    $fontsize = 5;
    $text = $label;
    $textBox = imagettfbbox($fontsize, 0, __DIR__ . '/public/fonts/arial.ttf', $text);
    
    // Fallback to imagestring if TTF not available
    imagestring($image, 3, 50, 250, $label, $textColor);
    imagestring($image, 2, 80, 300, 'Ralph Lauren Men', $textColor);
    imagestring($image, 2, 120, 350, 'Sample Product', $textColor);
    imagestring($image, 1, 150, 400, 'Bulk Import Test', $accentColor);
    
    // Save image
    imagejpeg($image, $filepath, 90);
    imagedestroy($image);
    
    echo "✅ Created: " . $filename . "\n";
}

echo "\n✅ All placeholder images created in public/uploads/bulk/\n";
echo "Ready to test bulk import!\n";
?>
