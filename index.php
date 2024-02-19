<?php
function minifyHTML($input) {
  // Remove comments
  if($input) $input = preg_replace('/<!--(.|\s)*?-->/', '', $input);

  // Remove whitespace
  if($input) $input = str_replace(array("\r\n", "\r", "\n", "\t"), '', $input);
  if($input) $input = str_replace('  ', ' ', $input);

  return $input;
}

function minifyJS($input) {
  // Remove comments
  if($input) $input = preg_replace('/\/\/.*$/m', '', $input);
  if($input) $input = preg_replace('/\/\*(?:[^*]|\*(?!\/))*\*\//m', '', $input);

  // Remove whitespace
  if($input) $input = str_replace(array("\r\n", "\r", "\n", "\t"), '', $input);
  if($input) $input = str_replace('  ', ' ', $input);

  return $input;
}

function minifyCSS($input) {
  // Remove comments
  if($input) $input = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $input);

  // Remove whitespace
  if($input) $input = str_replace(array("\r\n", "\r", "\n", "\t"), '', $input);
  if($input) $input = str_replace('  ', ' ', $input);

  return $input;
}

function minifyFile($filename, $outputDir) {
    $fileContents = file_get_contents($filename);
    $extension = pathinfo($filename, PATHINFO_EXTENSION);
    $minifiedContent = '';

    switch ($extension) {
      case 'html':
      case 'htm':
        $minifiedContent = minifyHTML($fileContents);
      break;
      case 'js':
        $minifiedContent = minifyJS($fileContents);
        break;
      case 'css':
        $minifiedContent = minifyCSS($fileContents);
        break;
      default:
        $minifiedContent = $fileContents;
    }

    file_put_contents($outputDir, $minifiedContent);
}

function minifyDirectory($sourceDir, $outputDir) {

  if(!is_dir($sourceDir)) throw new Exception('Source directory does not exist.');

  if(strpos($outputDir, $sourceDir) === 0) throw new Exception('Output directory must not be nested in the source directory.');

  if($outputDir === $sourceDir) throw new Exception('Output directory and source directory must not be the same.');

  // create ignore list from .gitignore file if it exists
  // otherwise, create empty ignore list
  // $ignoreList = array();
  $ignoreList = array();
  $ignoreFile = $sourceDir . '/.gitignore';

  // read gitignore file
  if (file_exists($ignoreFile)) {
    $ignoreList = file($ignoreFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
  }

  $files = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator($sourceDir, FilesystemIterator::SKIP_DOTS),
    RecursiveIteratorIterator::SELF_FIRST
  );


  foreach ($files as $file) {
    $filename = $file->getPathname();
    $relativePath = str_replace($sourceDir, '', $filename);

    // Check if file should be ignored
    $ignore = false;
    foreach ($ignoreList as $pattern) {
      if (fnmatch($pattern, $relativePath)) {
        $ignore = true;
        break;
      }
    }

    if ($ignore) continue;

    // Output file or directory to the corresponding location in the output directory
    $outputPath = $outputDir . $relativePath;

    if ($file->isFile()) {
      // var_dump($outputDir);
      minifyFile($filename, $outputPath);
    } 
    elseif ($file->isDir()) {
      if (!file_exists($outputPath)) {
        mkdir($outputPath, 0777, true);
      }
    }
  }
}

// function minifyDirectory($sourceDir, $outputDir) {

//   if(!is_dir($sourceDir)) throw new Exception('Source directory does not exist.');

//   if(strpos($outputDir, $sourceDir) === 0) throw new Exception('Output directory must not be nested in the source directory.');

//   if($outputDir === $sourceDir) throw new Exception('Output directory and source directory must not be the same.');


//   $files = new RecursiveIteratorIterator(
//     new RecursiveDirectoryIterator($sourceDir, FilesystemIterator::SKIP_DOTS),
//     RecursiveIteratorIterator::SELF_FIRST
//   );


//   foreach ($files as $file) {

//     if ($file->isFile()) var_dump($file);
//     if ($file->isDir()) var_dump($file);

//   }
// }




// Example usage
$sourceDir = "C:\wamp64\www\uncleebo\adminpanel";
$outputDir = 'C:\wamp64\www\panel.min';

if (!file_exists($outputDir)) {
    mkdir($outputDir, 0777, true);
}

minifyDirectory($sourceDir, $outputDir);
echo 'Minification completed.';
