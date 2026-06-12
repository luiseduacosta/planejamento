# Fix CSRF Cookie Error

## Quick Fix - Update AppController

The CSRF cookie error happens because the form needs a CSRF token. The login form should already have it automatically added by CakePHP's Form helper, but let's verify the setup.

### **Solution: Clear Browser Cookies & Cache**

1. **Clear your browser cookies** for localhost
2. **Clear browser cache**
3. **Restart the server:**

```bash
cd /home/luis/html/Planejamento5
bin/cake cache clear_all
```

4. **Try again:** Visit http://localhost:8765/login

---

## Alternative Fix: Disable CSRF Temporarily (Development Only)

If the error persists, you can temporarily disable CSRF protection for development:

**Edit:** `/home/luis/html/Planejamento5/config/app_local.php`

Add this at the top (after the opening `<?php` tag):

```php
// Disable CSRF for development
Configure::write('CSRF', false);
```

**Then edit:** `/home/luis/html/Planejamento5/src/Application.php`

Find the CSRF middleware section (around line 91-95) and wrap it:

```php
// Cross Site Request Forgery (CSRF) Protection Middleware
if (Configure::read('debug')) {
    // In development, use relaxed CSRF settings
    ->add(new CsrfProtectionMiddleware([
        'httponly' => true,
        'secure' => false,  // Allow on HTTP (not HTTPS)
        'samesite' => 'Lax',
    ]));
} else {
    // In production, strict CSRF
    ->add(new CsrfProtectionMiddleware([
        'httponly' => true,
    ]));
}
```

---

## Best Solution: Make Sure Form Has CSRF Token

The login form should automatically include CSRF token. Let's verify the form is correct:

**Check:** `/home/luis/html/Planejamento5/templates/Users/login.php`

The form should start with:
```php
<?= $this->Form->create(null, ['class' => 'needs-validation']) ?>
```

This automatically adds CSRF token when FormProtection component is loaded.

---

## Try This First:

1. **Clear all cookies** for localhost in your browser
2. **Clear CakePHP cache:**
   ```bash
   cd /home/luis/html/Planejamento5
   bin/cake cache clear_all
   ```
3. **Restart server:**
   ```bash
   bin/cake server
   ```
4. **Try login again**

---

If it still doesn't work, try **Option 2** below.
