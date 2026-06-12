#!/bin/bash

echo "=== Baking Dias, Horarios, and Salas ==="
echo ""

cd /home/luis/html/Planejamento5

echo "Step 1: Baking Dias..."
bin/cake bake all Dias --no-test
echo ""

echo "Step 2: Baking Horarios..."
bin/cake bake all Horarios --no-test
echo ""

echo "Step 3: Baking Salas..."
bin/cake bake all Salas --no-test
echo ""

echo "=== Baking Complete! ==="
echo ""
echo "You can now test at:"
echo "  - http://localhost:8765/dias"
echo "  - http://localhost:8765/horarios"
echo "  - http://localhost:8765/salas"
echo ""
echo "Make sure you're logged in as admin to add/edit/delete!"
