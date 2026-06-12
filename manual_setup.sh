#!/bin/bash

echo "=== Manual Setup for Dias, Horarios, Salas ==="
echo ""

cd /home/luis/html/Planejamento5

# Create directories
echo "Creating directories..."
mkdir -p src/Model/Table
mkdir -p src/Model/Entity
mkdir -p src/Controller
mkdir -p templates/Dias
mkdir -p templates/Horarios
mkdir -p templates/Salas

echo "✅ Directories created"
echo ""
echo "Now copy the files from /home/luis/html/planejamento:"
echo ""
echo "# Dias"
echo "cp /home/luis/html/planejamento/BAKE_*.php /home/luis/html/Planejamento5/src/Model/Table/"
echo ""
echo "Then we'll bake the controllers and templates individually."
