# Altalayi Tire Ticket System

**Version:** 1.3.0  
**Author:** Mohamed Ashraf  
**License:** GPL v2 or later  
**WordPress Version:** 5.0+  
**PHP Version:** 7.4+

## Description

Professional ticket system specifically designed for tire warranty and customer complaints management for Altalayi company. This comprehensive plugin provides a complete customer support solution with advanced email notifications, role-based access control, full Arabic localization, Polylang integration, and seamless multilingual URL routing.

## Features

### ✨ Core Features
- **Ticket Management System** - Complete lifecycle management for customer complaints
- **Customer Portal** - Easy-to-use frontend interface for customers
- **Admin Dashboard** - Comprehensive backend management for support staff
- **File Upload Support** - Allow customers and staff to attach files to tickets
- **Status Tracking** - Real-time ticket status updates with automatic notifications
- **Assignment System** - Assign tickets to specific support staff members
- **Full Internationalization** - Complete Arabic localization with Loco Translate support

### 🌐 Internationalization & Localization
- **Arabic Language Support** - Complete translation of all frontend and backend interfaces
- **Polylang Integration** - Seamless integration with Polylang for multilingual websites
- **Language-Aware URL Routing** - Automatic URL routing to Arabic/English page versions
- **Loco Translate Compatibility** - Professional translation template (POT file)
- **RTL Support** - Right-to-left text direction support for Arabic
- **Bilingual Interface** - Seamless switching between English and Arabic
- **Localized Email Templates** - Arabic email notifications for customers
- **Cultural Adaptation** - Date formats and number displays adapted for Arabic regions

### 📧 Advanced Email Notifications
- **Admin Notifications** - Configurable admin email for new ticket alerts
- **Role-Based Notifications** - Send notifications to users with specific WordPress roles
- **Additional Email Recipients** - Add custom email addresses for notifications
- **Notification Types Control** - Granular control over notification triggers:
  - New ticket submissions
  - Status changes
  - Customer responses
  - Staff assignments
- **HTML Email Templates** - Professional, responsive email designs
- **Notification Settings** - Enable/disable specific notification types

### 🎨 Frontend Integration
- **Shortcode Support** - Easy integration with any page or post
- **Elementor Widgets** - Native Elementor integration with custom widgets
- **Theme Compatibility** - Automatic theme compatibility styles
- **Custom Pages** - Dedicated pages for ticket creation, login, and viewing
- **Responsive Design** - Mobile-friendly interface

### 👥 User Management
- **Custom User Roles** - Ticket Employee role with specific permissions
- **Access Control** - Secure ticket access using ticket number and phone verification
- **Assignment Features** - Assign tickets to specific staff members
- **User Activity Tracking** - Track user interactions and responses

### ⚙️ Configuration Options
- **Company Branding** - Customize company name, email, and contact information
- **Page Settings** - Configure frontend pages for different ticket actions
- **File Upload Control** - Configure allowed file types and size limits
- **Display Options** - Control tickets per page and interface elements

## Installation

1. **Upload the Plugin**
   ```
   Upload the plugin files to /wp-content/plugins/altalayi-ticket-system/
   ```

2. **Activate the Plugin**
   ```
   Go to WordPress Admin → Plugins → Activate "Altalayi Tire Ticket System"
   ```

3. **Configure Settings**
   ```
   Navigate to WordPress Admin → Altalayi Tickets → Settings
   Configure your company information and notification preferences
   ```

4. **Create Frontend Pages** (Optional)
   ```
   Create pages for ticket creation, login, and viewing
   Add shortcodes or use Elementor widgets
   ```

## Quick Setup Guide

### 1. Basic Configuration
- Go to **Altalayi Tickets → Settings**
- Fill in your **Company Information**
- Set up **Email Notifications**
- Configure **Frontend Pages**

### 2. Email Notifications Setup
- Enable **Email Notifications**
- Set **Admin Email** for receiving notifications
- Select **User Roles** that should receive notifications
- Choose which **Notification Types** to enable
- Add any **Additional Email Recipients**

### 3. Frontend Integration
Choose one of these methods:

#### Option A: Shortcodes
Add these shortcodes to your pages:
```
[altalayi_ticket_form]        - Ticket creation form
[altalayi_ticket_login]       - Ticket access login
[altalayi_ticket_view]        - Ticket viewing interface
```

#### Option B: Elementor Widgets
If using Elementor:
1. Edit your page with Elementor
2. Search for "Altalayi" widgets
3. Drag and drop the desired widget

#### Option C: Custom Pages
1. Go to **Settings → Frontend Pages**
2. Select or create pages for each function
3. The plugin will automatically handle the content

## Shortcodes

### Ticket Creation Form
```
[altalayi_ticket_form]
```
Displays the ticket submission form for customers.

### Ticket Login
```
[altalayi_ticket_login]
```
Displays the login form for customers to access their tickets.

### Ticket View
```
[altalayi_ticket_view]
[altalayi_ticket_view ticket_number="ALT-2024-001" phone="123456789"]
[altalayi_ticket_view auto_detect="true"]
```
Displays ticket details. Can auto-detect from URL parameters.

## Email Notification System

### Configuration
The plugin provides a sophisticated email notification system:

1. **Admin Email**: Primary email for administrative notifications
2. **Role-Based Notifications**: Send to users with specific WordPress roles
3. **Additional Recipients**: Custom email addresses for notifications

### Notification Types
- **New Ticket**: When customers submit new tickets
- **Status Updates**: When ticket status changes
- **Customer Responses**: When customers add responses
- **Staff Assignment**: When tickets are assigned to staff

### Email Templates
Professional HTML email templates included:
- Ticket creation confirmations
- Status update notifications
- Assignment notifications
- Customer response alerts

## User Roles and Permissions

### Built-in WordPress Roles
- **Administrator**: Full access to all features
- **Editor/Author**: Can be configured to receive notifications

### Custom Role
- **Ticket Employee**: Specialized role for support staff
  - Can view and manage tickets
  - Can respond to customers
  - Can update ticket status
  - Can be assigned tickets

## Database Structure

The plugin creates the following tables:
- `wp_altalayi_tickets` - Main ticket data
- `wp_altalayi_ticket_updates` - Ticket history and responses
- `wp_altalayi_ticket_attachments` - File attachments
- `wp_altalayi_ticket_statuses` - Ticket status definitions

## File Upload Support

### Configuration
- **Allowed File Types**: jpg, jpeg, png, gif, pdf, doc, docx (configurable)
- **Maximum File Size**: 5MB (configurable)
- **Upload Locations**: Customer submissions and staff responses

### Security
- File type validation
- Size limit enforcement
- Secure file storage

## Changelog

### Version 1.3.0 (Current)
#### 🌐 Polylang Integration & URL Routing
- **Polylang Compatibility**: Seamless integration with Polylang multilingual plugin
- **Language-Aware URL Routing**: Enhanced URL functions with automatic language detection
- **Arabic Page Settings**: Optional dedicated Arabic pages in admin settings
- **Multilingual URL Structure**: Support for both parameter-based and path-based language routing
- **Language Detection**: Automatic detection from URL parameters, paths, and WordPress locale

#### 🎛️ Admin Enhancements
- **Arabic Page Configuration**: New admin settings for separate Arabic pages
- **Frontend Pages Settings**: Enhanced configuration for multilingual page setup
- **Language-Specific URLs**: Dedicated settings for Arabic versions of create, access, and view pages
- **Fallback URL System**: Automatic fallback to main pages with language parameters

#### 🚀 URL Functions Enhancement
- **Enhanced URL Functions**: Updated URL functions with language support
- **Language Parameter Support**: Functions now accept language parameters for explicit language targeting
- **Automatic Language Context**: URLs automatically adapt based on current language context
- **Polylang URL Integration**: Native support for Polylang's URL structure

#### 🗑️ Code Cleanup
- **Removed Language Switcher**: Removed custom language switcher in favor of Polylang's native switcher
- **Simplified Interface**: Cleaner frontend templates without redundant language switching
- **Optimized Functions**: Removed unused language switcher functions and templates

### Version 1.2.0
#### 🌐 Internationalization & Localization
- **Complete Arabic Translation**: Added comprehensive Arabic localization for all frontend components
- **Loco Translate Integration**: Created professional translation template (POT file) with 200+ translatable strings
- **Frontend Translation Focus**: Fully translated customer-facing interfaces
- **Arabic Language Pack**: Complete Arabic PO/MO files ready for production use
- **Cultural Adaptation**: Properly localized text for Arabic-speaking users
- **Right-to-Left (RTL) Ready**: Compatible with RTL text direction

### Version 1.1.0
#### 🚀 Major Improvements
- **Fixed Admin Email Notifications**: Resolved issue where admin emails weren't being sent for new tickets
- **Enhanced Role-Based Notifications**: Improved role-based notification system with better reliability
- **Notification System Overhaul**: Complete rewrite of email notification logic
- **Better Settings Management**: Fixed settings key conflicts and improved validation

#### 🔧 Technical Fixes
- Fixed `get_admin_email()` method using wrong settings key
- Added proper notification type checking
- Implemented comprehensive email recipient management
- Enhanced error handling and logging
- Fixed file loading order issues

#### ✨ New Features
- Support for multiple notification recipients
- Enhanced debugging capabilities (removable)
- Better template fallback system
- Improved settings validation

#### 🛠️ Code Quality
- Cleaned up debugging code
- Improved function organization
- Enhanced error handling
- Better code documentation

### Version 1.0.0
- Initial release
- Basic ticket management system
- Customer portal functionality
- Admin dashboard
- Email notifications
- Shortcode support
- Elementor integration

## Technical Requirements

- **WordPress**: 5.0 or higher
- **PHP**: 7.4 or higher
- **MySQL**: 5.6 or higher
- **Email Support**: Configured WordPress mail function or SMTP plugin

## Support and Documentation

### Getting Help
1. Check the **Shortcode Guide** in the plugin admin
2. Review the **Settings** page for configuration options
3. Enable **WordPress Debug** mode for troubleshooting

### Common Issues
- **Emails not being sent**: Check WordPress mail configuration
- **Notifications not working**: Verify settings and user roles
- **Frontend display issues**: Check theme compatibility styles

### Debug Mode
For troubleshooting, enable WordPress debug mode in `wp-config.php`:
```php
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
```

## Security

- **Input Sanitization**: All user inputs are properly sanitized
- **Nonce Verification**: CSRF protection on all forms
- **Access Control**: Secure ticket access verification
- **File Upload Security**: Type and size validation
- **Database Security**: Prepared statements for all queries

## Performance

- **Optimized Queries**: Efficient database operations
- **Conditional Loading**: Scripts and styles loaded only when needed
- **Caching Friendly**: Compatible with WordPress caching plugins
- **Minimal Footprint**: Lightweight codebase

## License

This plugin is licensed under the GPL v2 or later.

## Credits

Developed by Mohamed Ashraf for Altalayi Company.

---

For more information, visit [Altalayi Company](https://altalayi.com.sa)
