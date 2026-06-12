#!/usr/bin/env php
<?php
/**
 * Fix Security Salt Script
 * Run this from the terminal to update your app_local.php
 */

$configFile = '/home/luis/html/Planejamento5/config/app_local.php';

// Generate random salt
$salt = bin2hex(random_bytes(32));

// Read file
$content = file_get_contents($configFile);

// Replace __SALT__ with actual salt
$content = str_replace('__SALT__', $salt, $content);

// Write back
file_put_contents($configFile, $content);

echo "✅ Security salt updated successfully!\n";
echo "New salt: {$salt}\n";
echo "File: {$configFile}\n";
