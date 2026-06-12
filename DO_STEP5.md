# Step 5: Create UsersController (Login/Logout) 🔐

## What to Do:

### **5.1: Create UsersController**

```bash
# Create directories
mkdir -p /home/luis/html/Planejamento5/src/Controller
mkdir -p /home/luis/html/Planejamento5/templates/Users

# Copy controller
cp /home/luis/html/planejamento/STEP5_UsersController.php /home/luis/html/Planejamento5/src/Controller/UsersController.php
```

---

### **5.2: Create Login View**

```bash
# Copy login template
cp /home/luis/html/planejamento/STEP5_login.php /home/luis/html/Planejamento5/templates/Users/login.php
```

---

### **5.3: Add Routes**

Edit `/home/luis/html/Planejamento5/config/routes.php`

Add these lines **before** the closing `});`:

```php
// Authentication routes
$builder->connect('/login', ['controller' => 'Users', 'action' => 'login']);
$builder->connect('/logout', ['controller' => 'Users', 'action' => 'logout']);
$builder->connect('/profile', ['controller' => 'Users', 'action' => 'profile']);
```

---

## What This Creates:

✅ **UsersController** with 3 actions:
- `login()` - Handle user authentication
- `logout()` - Log out user
- `profile()` - View user profile

✅ **Login page** with:
- Bootstrap 5 styled form
- Username & password fields
- Demo credentials display
- Responsive design

✅ **Routes** for easy access:
- `/login` → Login page
- `/logout` → Logout action
- `/profile` → User profile

---

## How It Works:

1. **User visits** `/login`
2. **Enters** username and password
3. **Authentication component** validates credentials
4. **If valid:** Redirects to Planejamentos/listar
5. **If invalid:** Shows error message
6. **Logout:** Clears session and redirects to home

---

## Next Step:

After completing Step 5, we'll:
- **Step 6: Create first admin user** (add user to database)
- **Step 7: Test login!** 🎉

Then you can **log in** to your application!
