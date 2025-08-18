<?php
// Simple test file to verify PHP and container setup
echo "<h1>PHP Test Page</h1>";
echo "<p>PHP Version: " . phpversion() . "</p>";
echo "<p>Server Software: " . $_SERVER['SERVER_SOFTWARE'] . "</p>";
echo "<p>Document Root: " . $_SERVER['DOCUMENT_ROOT'] . "</p>";

// Test if we can connect to MySQL
try {
    $pdo = new PDO('mysql:host=mysql;dbname=app', 'root', 'root');
    echo "<p style='color: green;'>✅ MySQL Connection: SUCCESS</p>";
} catch (PDOException $e) {
    echo "<p style='color: red;'>❌ MySQL Connection: FAILED - " . $e->getMessage() . "</p>";
}

// Test if we can connect to Redis
try {
    $redis = new Redis();
    $redis->connect('redis', 6379);
    $redis->ping();
    echo "<p style='color: green;'>✅ Redis Connection: SUCCESS</p>";
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Redis Connection: FAILED - " . $e->getMessage() . "</p>";
}

echo "<p>Test completed at: " . date('Y-m-d H:i:s') . "</p>";
?>
