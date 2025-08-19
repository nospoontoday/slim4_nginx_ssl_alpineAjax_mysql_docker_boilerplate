<?php
/**
 * @file compile-scss.php
 * @purpose Compile SCSS files to CSS
 */

// Function to compile SCSS to CSS using a simple regex-based approach
function compileScss($scssContent) {
    // Remove comments
    $cssContent = preg_replace('/\/\*.*?\*\//s', '', $scssContent);
    
    // Remove SCSS variables
    $cssContent = preg_replace('/\$[a-zA-Z0-9-_]+:\s*[^;]+;/', '', $cssContent);
    
    // Replace SCSS variable references with actual values
    $variables = [];
    preg_match_all('/\$([a-zA-Z0-9-_]+):\s*([^;]+);/', $scssContent, $matches, PREG_SET_ORDER);
    foreach ($matches as $match) {
        $variables[$match[1]] = trim($match[2]);
    }
    
    // Replace variable references
    foreach ($variables as $name => $value) {
        $cssContent = str_replace('$' . $name, $value, $cssContent);
    }
    
    // Remove SCSS nesting (simplified approach)
    $cssContent = preg_replace('/&(__[a-zA-Z0-9-_]+)/', '.\\1', $cssContent);
    
    // Remove SCSS mixins and includes
    $cssContent = preg_replace('/@mixin\s+[a-zA-Z0-9-_]+\([^}]+\)/', '', $cssContent);
    $cssContent = preg_replace('/@include\s+[a-zA-Z0-9-_]+;/', '', $cssContent);
    
    // Remove empty lines
    $cssContent = preg_replace('/^\s*[\r\n]/m', '', $cssContent);
    
    return $cssContent;
}

// Compile sugar.scss
$sugarScss = file_get_contents('public/assets/sass/sugar.scss');
$sugarCss = compileScss($sugarScss);
file_put_contents('public/assets/css/sugar.css', $sugarCss);

// Compile landing.scss
$landingScss = file_get_contents('public/assets/sass/landing.scss');
$landingCss = compileScss($landingScss);
file_put_contents('public/assets/css/landing.css', $landingCss);

echo "SCSS files compiled successfully.\n";
?>