# Step 3: Create Bootstrap 5 Layout ✅

## What to Do:

### **3.1: Create the layout file**

Create directory (if it doesn't exist):
```bash
mkdir -p /home/luis/html/Planejamento5/templates/layout
```

Copy the layout file:
```bash
cp /home/luis/html/planejamento/STEP3_layout_default.php /home/luis/html/Planejamento5/templates/layout/default.php
```

---

### **3.2: Create custom CSS**

Create directory (if it doesn't exist):
```bash
mkdir -p /home/luis/html/Planejamento5/webroot/css
```

Copy the CSS file:
```bash
cp /home/luis/html/planejamento/STEP3_custom.css /home/luis/html/Planejamento5/webroot/css/custom.css
```

---

## What This Does:

✅ **Responsive navbar** with navigation menu  
✅ **Bootstrap 5.3.3** via CDN (latest version)  
✅ **Bootstrap Icons** for modern iconography  
✅ **User menu** showing login status and role badge  
✅ **Flash messages** with Bootstrap alerts  
✅ **Footer** with ESS/UFRJ branding  
✅ **Custom CSS** with UFRJ color scheme (blue theme)  
✅ **Mobile-responsive** design  
✅ **Dropdown menus** for settings and user profile  

---

## Features Included:

### **Navbar:**
- 📚 Brand logo
- 📅 Planejamentos
- 👥 Docentes
- 📖 Disciplinas
- 🏢 Salas
- ⏰ Horários
- ⚙️ Configurações (dropdown)
- 👤 User menu with role badge (Admin/Editor)
- 🔐 Login button (when not logged in)

### **Color Scheme:**
- Primary: UFRJ Blue (#003366)
- Secondary: Light Blue (#0066cc)
- Accent: Orange (#ff6600)

### **Responsive Design:**
- Mobile hamburger menu
- Responsive tables
- Flexible grid system
- Touch-friendly buttons

---

## Verification:

After copying both files, restart your server:

```bash
cd /home/luis/html/Planejamento5
bin/cake server
```

Visit: **http://localhost:8765**

You should see:
- ✅ Blue navbar with menu items
- ✅ Bootstrap styling applied
- ✅ Icons next to menu items
- ✅ Footer at the bottom
- ✅ Responsive design (try resizing browser!)

---

## What's Next:

After completing Step 3, we'll proceed to:
- **Step 4: Create Users Table** (database for authentication)
- **Step 5: Create UsersController** (login/logout functionality)

Then you'll be able to **log in** to your application! 🎉
