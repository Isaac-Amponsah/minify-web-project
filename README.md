## Minifier Script

This PHP script provides functions to minify HTML, CSS, and JavaScript files in a directory and its subdirectories. Additionally, it includes functionality to ignore files listed in a `.gitignore` file and outputs the minified files to a separate directory.

### How to Use

1. **Functions:**

   - `minifyHTML($input)`: Minifies HTML content.
   - `minifyJS($input)`: Minifies JavaScript content.
   - `minifyCSS($input)`: Minifies CSS content.
   - `minifyFile($filename, $outputDir)`: Minifies a file based on its extension and outputs it to the specified directory.
   - `minifyDirectory($sourceDir, $outputDir)`: Recursively scans a directory, minifies files, and outputs them to a separate directory.

2. **Usage Example:**

   ```php
   <?php
   // Specify source and output directories
   $sourceDir = "C:\wamp64\www\uncleebo\adminpanel";
   $outputDir = 'C:\wamp64\www\panel.min';

   // Create output directory if it doesn't exist
   if (!file_exists($outputDir)) {
       mkdir($outputDir, 0777, true);
   }

   // Minify files in the source directory and its subdirectories
   minifyDirectory($sourceDir, $outputDir);
   echo 'Minification completed.';
   ?>
  
### Requirements
   - PHP 5.3.0 or higher

### Notes
Ensure that the PHP script has appropriate permissions to read from and write to the specified directories.
This script does not modify the original files; it creates minified versions in a separate directory.
The .gitignore file in the source directory is used to ignore specific files during minification.

### License
This script is provided under the MIT License. Feel free to modify and distribute it according to your needs.