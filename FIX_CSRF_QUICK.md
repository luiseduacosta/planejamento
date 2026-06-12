# Quick CSRF Fix - Update Middleware Order

## The Problem:
CSRF middleware is checking tokens BEFORE authentication is established, causing cookie issues.

## Solution:

**Edit:** `/home/luis/html/Planejamento5/src/Application.php`

**Find the middleware() method (around line 63-98)**

**Change this section (lines 91-95):**

FROM:
```php
->add(new CsrfProtectionMiddleware([
    'httponly' => true,
]));
```

TO:
```php
->add(new CsrfProtectionMiddleware([
    'httponly' => true,
    'secure' => false,  // Allow HTTP in development
    'samesite' => 'Lax',
]));
```

**Then:**
1. Clear cache: `bin/cake cache clear_all`
2. Restart server: `bin/cake server`
3. Clear browser cookies for localhost
4. Try login again

---

## OR - Skip CSRF in Development (Temporary)

If you just want to test quickly, comment out the CSRF middleware:

**In Application.php, change:**
```php
->add(new CsrfProtectionMiddleware([
    'httponly' => true,
]))
```

**To:**
```php
//->add(new CsrfProtectionMiddleware([
//    'httponly' => true,
//]))
```

⚠️ **Only do this for development! Re-enable for production!**

---

**Try the first fix and let me know if it works!** 🚀
