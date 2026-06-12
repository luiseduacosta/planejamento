#!/bin/bash

echo "Step 4: Creating Users Table Migration..."
echo ""

cd /home/luis/html/Planejamento5

echo "Creating migration file..."
./bin/cake bake migration CreateUsers \
    username:string[50] \
    password:string[255] \
    role:string[20] \
    email:string[100] \
    created \
    modified

echo ""
echo "✅ Migration created!"
echo ""
echo "Next: Run the migration with:"
echo "   cd /home/luis/html/Planejamento5"
echo "   ./bin/cake migrations migrate"
echo ""
