# Correct Copy Commands

## ✅ Run These Commands:

```bash
cd /home/luis/html/planejamento

# Create directories
mkdir -p /home/luis/html/Planejamento5/src/Model/Table
mkdir -p /home/luis/html/Planejamento5/src/Model/Entity
mkdir -p /home/luis/html/Planejamento5/src/Controller
mkdir -p /home/luis/html/Planejamento5/templates/Dias
mkdir -p /home/luis/html/Planejamento5/templates/Horarios
mkdir -p /home/luis/html/Planejamento5/templates/Salas

# Copy Dias files
cp MANUAL_DiasTable.php /home/luis/html/Planejamento5/src/Model/Table/DiasTable.php
cp MANUAL_Dia.php /home/luis/html/Planejamento5/src/Model/Entity/Dia.php
cp MANUAL_DiasController_FIXED.php /home/luis/html/Planejamento5/src/Controller/DiasController.php
cp MANUAL_Dias_index.php /home/luis/html/Planejamento5/templates/Dias/index.php
cp MANUAL_Dias_view.php /home/luis/html/Planejamento5/templates/Dias/view.php
cp MANUAL_Dias_add.php /home/luis/html/Planejamento5/templates/Dias/add.php
cp MANUAL_Dias_edit.php /home/luis/html/Planejamento5/templates/Dias/edit.php

# Copy Horarios files
cp MANUAL_HorariosTable.php /home/luis/html/Planejamento5/src/Model/Table/HorariosTable.php
cp MANUAL_Horario.php /home/luis/html/Planejamento5/src/Model/Entity/Horario.php
cp MANUAL_HorariosController.php /home/luis/html/Planejamento5/src/Controller/HorariosController.php
cp MANUAL_Horarios_index.php /home/luis/html/Planejamento5/templates/Horarios/index.php
cp MANUAL_Horarios_view.php /home/luis/html/Planejamento5/templates/Horarios/view.php
cp MANUAL_Horarios_add.php /home/luis/html/Planejamento5/templates/Horarios/add.php
cp MANUAL_Horarios_edit.php /home/luis/html/Planejamento5/templates/Horarios/edit.php

# Copy Salas files
cp MANUAL_SalasTable.php /home/luis/html/Planejamento5/src/Model/Table/SalasTable.php
cp MANUAL_Sala.php /home/luis/html/Planejamento5/src/Model/Entity/Sala.php
cp MANUAL_SalasController.php /home/luis/html/Planejamento5/src/Controller/SalasController.php
cp MANUAL_Salas_index.php /home/luis/html/Planejamento5/templates/Salas/index.php
cp MANUAL_Salas_view.php /home/luis/html/Planejamento5/templates/Salas/view.php
cp MANUAL_Salas_add.php /home/luis/html/Planejamento5/templates/Salas/add.php
cp MANUAL_Salas_edit.php /home/luis/html/Planejamento5/templates/Salas/edit.php

# Clear cache
cd /home/luis/html/Planejamento5
bin/cake cache clear_all

echo ""
echo "=== ALL FILES COPIED SUCCESSFULLY! ==="
echo ""
echo "Test at:"
echo "  http://localhost:8765/dias"
echo "  http://localhost:8765/horarios"
echo "  http://localhost:8765/salas"
```

---

## ⚠️ **Note:**

Don't use wildcards like `MANUAL_Dias_*.php` - they don't work properly!
Use explicit file names as shown above.
