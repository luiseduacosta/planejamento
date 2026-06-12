#!/usr/bin/env php
<?php
/**
 * Create First Users Script
 * Creates admin and editor users for testing
 * 
 * Run: php create_first_users.php
 */

// Bootstrap CakePHP
require_once '/home/luis/html/Planejamento5/vendor/autoload.php';

use Cake\Orm\TableRegistry;
use Cake\Auth\DefaultPasswordHasher;

echo "=== Create First Users ===\n\n";

try {
    // Initialize CakePHP
    require_once '/home/luis/html/Planejamento5/config/bootstrap.php';
    
    // Get Users table
    $users = TableRegistry::getTableLocator()->get('Users');
    
    echo "Creating Admin User...\n";
    
    // Check if admin already exists
    $existingAdmin = $users->find()
        ->where(['username' => 'admin'])
        ->first();
    
    if ($existingAdmin) {
        echo "⚠️  Admin user already exists! Skipping...\n\n";
    } else {
        // Create admin user
        $admin = $users->newEntity([
            'username' => 'admin',
            'password' => (new DefaultPasswordHasher())->hash('admin123'),
            'role' => 'admin',
            'email' => 'admin@ess.ufrj.br'
        ]);
        
        if ($users->save($admin)) {
            echo "✅ Admin user created successfully!\n";
            echo "   Username: admin\n";
            echo "   Password: admin123\n";
            echo "   Role: admin\n\n";
        } else {
            echo "❌ Failed to create admin user!\n";
            echo "Errors: " . print_r($admin->getErrors(), true) . "\n";
            exit(1);
        }
    }
    
    echo "Creating Editor User...\n";
    
    // Check if editor already exists
    $existingEditor = $users->find()
        ->where(['username' => 'editor'])
        ->first();
    
    if ($existingEditor) {
        echo "⚠️  Editor user already exists! Skipping...\n\n";
    } else {
        // Create editor user
        $editor = $users->newEntity([
            'username' => 'editor',
            'password' => (new DefaultPasswordHasher())->hash('editor123'),
            'role' => 'editor',
            'email' => 'editor@ess.ufrj.br'
        ]);
        
        if ($users->save($editor)) {
            echo "✅ Editor user created successfully!\n";
            echo "   Username: editor\n";
            echo "   Password: editor123\n";
            echo "   Role: editor\n\n";
        } else {
            echo "❌ Failed to create editor user!\n";
            echo "Errors: " . print_r($editor->getErrors(), true) . "\n";
            exit(1);
        }
    }
    
    echo "=== Summary ===\n";
    echo "Total users in database: " . $users->find()->count() . "\n\n";
    echo "You can now login at: http://localhost:8765/login\n";
    echo "Use the credentials above to test!\n\n";
    
} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "\nMake sure:\n";
    echo "1. Database is configured in config/app_local.php\n";
    echo "2. Users table migration has been run\n";
    echo "3. You're in the correct directory\n";
    exit(1);
}
