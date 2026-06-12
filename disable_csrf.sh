#!/bin/bash

echo "=== Disabling CSRF Middleware for Development ==="
echo ""

FILE="/home/luis/html/Planejamento5/src/Application.php"

# Create a backup
cp "$FILE" "${FILE}.backup"
echo "✅ Backup created: ${FILE}.backup"

# Comment out CSRF middleware
sed -i '103,105s/^/\/\/ /' "$FILE"

echo "✅ CSRF middleware commented out"
echo ""
echo "Clearing cache..."
cd /home/luis/html/Planejamento5
bin/cake cache clear_all

echo ""
echo "✅ Done! Now restart your server:"
echo "   bin/cake server"
echo ""
echo "Then try login again at: http://localhost:8765/login"
echo ""
