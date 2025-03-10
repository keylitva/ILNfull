<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProfilePictureController extends Controller
{
    public function generate($name)
    {
        $letter = strtoupper(substr($name, 0, 1)); // Get the first letter
        $size = 42; // Image size (width and height) – you can adjust this if you want a different size
        $bgColor = [31, 41, 55]; // RGB for Coral background color
        $textColor = [255, 255, 255]; // RGB for White text color

        // Path to the TTF font file
        $fontPath = public_path('fonts/Anton-Regular.ttf'); // Change this to your font file

        // Create a blank image
        $image = imagecreatetruecolor($size, $size);

        // Allocate colors
        $bgColorAlloc = imagecolorallocate($image, $bgColor[0], $bgColor[1], $bgColor[2]);
        $textColorAlloc = imagecolorallocate($image, $textColor[0], $textColor[1], $textColor[2]);

        // Fill the image with the background color
        imagefill($image, 0, 0, $bgColorAlloc);

        // Set the font size and calculate the position of the text
        $fontSize = 18; // Font size for the TTF font

        // Calculate the bounding box of the letter
        $textBoundingBox = imagettfbbox($fontSize, 0, $fontPath, $letter);
        $textWidth = $textBoundingBox[2] - $textBoundingBox[0];
        $textHeight = $textBoundingBox[7] - $textBoundingBox[1];

        // Calculate X and Y position to center the text
        $x = ($size - $textWidth) / 2;
        $y = ($size + $textHeight) / 2;

        // Adjust Y to center text vertically more accurately
        $y = $y - $textHeight;

        // Set content type for the image
        header('Content-Type: image/png');

        // Add the text to the image using the TTF font
        imagettftext($image, $fontSize, 0, $x, $y, $textColorAlloc, $fontPath, $letter);

        // Output the image to the browser
        imagepng($image);

        // Clean up resources
        imagedestroy($image);
        exit; // Ensure no further output occurs after the image is sent
    }
}