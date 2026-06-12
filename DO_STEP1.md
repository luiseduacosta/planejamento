# Step 1: Update Application.php ✅

## What to Do:

**Copy the prepared file to replace your current Application.php**

### Command:

```bash
cp /home/luis/html/planejamento/STEP1_Application.php /home/luis/html/Planejamento5/src/Application.php
```

Or manually:
1. Open `/home/luis/html/planejamento/STEP1_Application.php`
2. Copy ALL the content
3. Open `/home/luis/html/Planejamento5/src/Application.php`
4. Replace ALL content with the copied content
5. Save

---

## What This Does:

✅ Adds Authentication & Authorization service providers  
✅ Configures login/logout redirects  
✅ Sets up Password authentication  
✅ Enables Session and Form authenticators  
✅ Adds authentication middleware to the pipeline  
✅ Adds authorization middleware to the pipeline  
✅ Configures ORM-based policy resolution  

---

## Verification:

After copying, verify the file has these key parts:

1. **Class declaration** should include interfaces:
```php
class Application extends BaseApplication implements 
    AuthenticationServiceProviderInterface, 
    AuthorizationServiceProviderInterface
```

2. **Middleware queue** should have these at the end:
```php
->add(new AuthenticationMiddleware($this))
->add(new \Authorization\Middleware\AuthorizationMiddleware($this));
```

3. **Two new methods** at the bottom:
   - `getAuthenticationService()`
   - `getAuthorizationService()`

---

## Next Step:

After completing this step, run:

```bash
cd /home/luis/html/Planejamento5
bin/cake server
```

Visit: http://localhost:8765

If you see the default CakePHP page without errors, **Step 1 is successful!** ✅

Then let me know, and we'll proceed to **Step 2: Update AppController.php**
