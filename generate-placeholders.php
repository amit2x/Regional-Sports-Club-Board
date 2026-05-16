<?php
// generate-placeholders.php (Run this once to create placeholder images)

// This script generates simple placeholder images for development

// Create logo-white.png (simple SVG converted to PNG)
$logoWhite = '<?xml version="1.0" encoding="UTF-8"?>
<svg xmlns="http://www.w3.org/2000/svg" width="200" height="60" viewBox="0 0 200 60">
  <rect width="200" height="60" fill="#667eea" rx="10"/>
  <text x="100" y="40" font-family="Arial" font-size="28" font-weight="bold" fill="white" text-anchor="middle">RSCB</text>
</svg>';

file_put_contents(public_path('images/logo-white.svg'), $logoWhite);

// Create sports-hero.png (using a placeholder SVG)
$heroImage = '<?xml version="1.0" encoding="UTF-8"?>
<svg xmlns="http://www.w3.org/2000/svg" width="600" height="400" viewBox="0 0 600 400">
  <rect width="600" height="400" fill="#667eea"/>
  <circle cx="300" cy="200" r="100" fill="rgba(255,255,255,0.2)"/>
  <text x="300" y="210" font-family="Arial" font-size="24" fill="white" text-anchor="middle">Sports Hero</text>
</svg>';

file_put_contents(public_path('images/sports-hero.svg'), $heroImage);

// Create pattern.png
$pattern = '<?xml version="1.0" encoding="UTF-8"?>
<svg xmlns="http://www.w3.org/2000/svg" width="100" height="100" viewBox="0 0 100 100">
  <rect width="100" height="100" fill="none"/>
  <circle cx="50" cy="50" r="2" fill="rgba(255,255,255,0.3)"/>
</svg>';

file_put_contents(public_path('images/pattern.svg'), $pattern);

echo "Placeholder images created!\n";
