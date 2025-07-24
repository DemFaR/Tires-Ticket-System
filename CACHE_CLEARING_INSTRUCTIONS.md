# WordPress Cache Clearing Instructions

## To fix the file duplication issue, please follow these steps:

### 1. Clear Browser Cache
- **Chrome/Edge**: Press `Ctrl+Shift+R` (Windows) or `Cmd+Shift+R` (Mac)
- **Firefox**: Press `Ctrl+F5` (Windows) or `Cmd+Shift+R` (Mac)
- **Safari**: Press `Cmd+Option+R`

### 2. Clear WordPress Cache (if any caching plugin is active)
- If you have **WP Rocket**: Go to WP Rocket → Clear Cache
- If you have **W3 Total Cache**: Go to Performance → Purge All Caches
- If you have **WP Super Cache**: Go to Settings → WP Super Cache → Delete Cache
- If you have **LiteSpeed Cache**: Go to LiteSpeed Cache → Toolbox → Purge → Purge All

### 3. WordPress Object Cache
Run this command in your WordPress root directory:
```bash
wp cache flush
```

### 4. Check Browser Developer Console
1. Open the page: http://localhost/wordpress/create-new-ticket/
2. Press F12 to open Developer Tools
3. Go to Console tab
4. Look for these messages when you select files:
   - "🚀 Altalayi Ticket System - File Upload v2.1 Loaded"
   - "📁 Processing X files for tire_images"
   - "📄 File 0: filename.jpg (image/jpeg)"
   - "✅ FileReader loaded for: filename.jpg index: 0"

### 5. Force Reload Template
If the above doesn't work, try deactivating and reactivating the plugin:
1. Go to WordPress Admin → Plugins
2. Deactivate "Altalayi Ticket System"
3. Activate it again

### 6. Verify Fix is Working
The debug console should show unique filenames for each file:
- ✅ CORRECT: File names should be different for each file
- ❌ INCORRECT: All files showing the same name

### 7. Alternative: Direct File Check
You can also verify the fix by checking the timestamp in the source code:
1. Right-click on the page → View Source
2. Search for "File Upload Fix v2.1"
3. You should see a timestamp like: "File Upload Fix v2.1 - 2025-07-24 19:08:32"

## Technical Details
The fix uses:
- `let` instead of `var` for proper block scoping
- Individual file variable capture
- Enhanced console logging
- Cache-busting timestamp

If the issue persists after trying all these steps, please check the browser console for any JavaScript errors.
