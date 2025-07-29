# Altalayi Ticket System - Shortcode Guide

## Available Shortcodes

The Altalayi Ticket System provides three powerful shortcodes that can be used in any WordPress page or post, including Elementor pages. This gives you complete control over the layout and design with multilingual support.

### 1. Ticket Creation Form - `[altalayi_ticket_form]`

**Purpose:** Displays a form for customers to submit new tire complaints.

**Basic Usage:**
```
[altalayi_ticket_form]
```

**Advanced Usage with Parameters:**
```
[altalayi_ticket_form title="Submit Your Tire Complaint" show_title="true" container_class="my-custom-class"]
```

**Parameters:**
- `title` - Custom title for the form (default: "Submit New Ticket")
- `show_title` - Whether to display the title (default: "true")
- `container_class` - Additional CSS class for styling (default: "altalayi-shortcode-container")
- `success_message` - Custom success message after form submission

**Example for Elementor:**
1. Add a "Shortcode" widget to your page
2. Enter: `[altalayi_ticket_form title="Report Your Tire Issue" show_title="true"]`

---

### 2. Ticket Login Form - `[altalayi_ticket_login]`

**Purpose:** Displays a login form for customers to access their existing tickets.

**Basic Usage:**
```
[altalayi_ticket_login]
```

**Advanced Usage with Parameters:**
```
[altalayi_ticket_login title="Access Your Ticket" show_title="true" redirect_after_login="same_page"]
```

**Parameters:**
- `title` - Custom title for the login form (default: "Access Your Ticket")
- `show_title` - Whether to display the title (default: "true")
- `container_class` - Additional CSS class for styling (default: "altalayi-shortcode-container")
- `redirect_after_login` - Where to redirect after login (default: "same_page")

**Example for Elementor:**
1. Add a "Shortcode" widget to your page
2. Enter: `[altalayi_ticket_login title="Login to Your Ticket"]`

---

### 3. Ticket Details View - `[altalayi_ticket_view]`

**Purpose:** Displays detailed information about a specific ticket, including updates and the ability to add responses.

**Basic Usage:**
```
[altalayi_ticket_view]
```

**Advanced Usage with Parameters:**
```
[altalayi_ticket_view ticket_number="TKT-12345" phone="1234567890" auto_detect="true" show_title="true"]
```

**Parameters:**
- `ticket_number` - Specific ticket number to display (optional if auto_detect is enabled)
- `phone` - Phone number associated with the ticket (optional if auto_detect is enabled)
- `auto_detect` - Automatically detect ticket from session or URL parameters (default: "true")
- `show_title` - Whether to display the ticket title (default: "true")
- `container_class` - Additional CSS class for styling (default: "altalayi-shortcode-container")

**Auto-Detection Features:**
- Reads ticket info from user session after login
- Can read from URL parameters: `?ticket=TKT-12345&phone=1234567890`
- Falls back to showing login prompt if no ticket is found

**Example for Elementor:**
1. Add a "Shortcode" widget to your page
2. Enter: `[altalayi_ticket_view auto_detect="true"]`

---

## Complete Workflow Setup

### Method 1: Three Separate Pages (Recommended)

**Page 1: "Submit Ticket" (e.g., /submit-ticket/)**
```
[altalayi_ticket_form title="Submit Your Tire Complaint"]
```

**Page 2: "My Ticket" (e.g., /my-ticket/)**
```
[altalayi_ticket_login title="Access Your Ticket"]
[altalayi_ticket_view auto_detect="true" show_title="false"]
```

**Page 3: "Track Ticket" (e.g., /track-ticket/)**
```
[altalayi_ticket_view auto_detect="true"]
```

### Method 2: All-in-One Page

**Single Page with Dynamic Content:**
```
[altalayi_ticket_form]

<hr>

[altalayi_ticket_login]

<hr>

[altalayi_ticket_view auto_detect="true"]
```

---

## Styling and Customization

### CSS Classes for Custom Styling

All shortcodes use the following CSS structure:
```css
.altalayi-shortcode-container {
    /* Main container for each shortcode */
}

.altalayi-shortcode-title {
    /* Title styling */
}

.altalayi-ticket-content {
    /* Content area styling */
}
```

### Custom CSS Examples

**To change the container background:**
```css
.altalayi-shortcode-container {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}
```

**To customize the title:**
```css
.altalayi-shortcode-title {
    color: #2c3e50;
    font-size: 2rem;
    text-align: left;
    border-bottom: none;
}
```

### Elementor Integration Tips

1. **Add Custom CSS Class:**
   - In Elementor, select the Shortcode widget
   - Go to Advanced > CSS Classes
   - Add your custom class name

2. **Use Elementor's Style Controls:**
   - Background colors and borders can be controlled through Elementor's Style tab
   - Typography can be controlled through Elementor's Style > Typography

3. **Responsive Design:**
   - Use Elementor's responsive settings to adjust shortcode appearance on different devices

---

## Advanced Features

### URL Parameters Support

The ticket view shortcode can read parameters from the URL:
- `?ticket=TKT-12345&phone=1234567890` - Direct ticket access
- This is useful for email links sent to customers

### Session Management

- After successful login via `[altalayi_ticket_login]`, the ticket info is stored in session
- `[altalayi_ticket_view]` with `auto_detect="true"` will automatically show the logged-in ticket
- Sessions persist across page visits until browser is closed

### AJAX Integration

All shortcodes support AJAX for:
- Form submissions without page reload
- Real-time form validation
- Dynamic content updates

---

## Migration from Fixed Pages

If you were previously using the fixed URLs (`/new-ticket`, `/ticket-login`), you can now:

1. **Create new Elementor pages** with the shortcodes
2. **Set up redirects** from old URLs to new pages (optional)
3. **Update any existing links** to point to your new pages

**Example redirect in .htaccess:**
```
RewriteRule ^new-ticket/?$ /submit-ticket/ [R=301,L]
RewriteRule ^ticket-login/?$ /my-ticket/ [R=301,L]
```

---

## Troubleshooting

### Styles Not Loading
- Ensure the shortcode is properly formatted with square brackets
- Check that you're using the correct shortcode names
- Clear any caching plugins

### Form Not Submitting
- Verify WordPress AJAX is working on your site
- Check browser console for JavaScript errors
- Ensure nonce validation is working

### Ticket Not Displaying
- Verify the ticket number and phone combination is correct
- Check if session is working properly on your site
- Enable `auto_detect="true"` for automatic detection

---

## Support

For additional support or customization requests, please refer to the plugin documentation or contact support.
