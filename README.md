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

## Features

### Customer Features
- **Easy Ticket Submission**: Customers can submit tire complaints with detailed information
- **File Uploads**: Support for tire images, receipts, and additional documents
- **Secure Access**: Access tickets using ticket number and phone number credentials
- **Real-time Updates**: Email notifications for all ticket status changes
- **Response System**: Customers can provide additional information when requested
- **Mobile Friendly**: Responsive design works on all devices

### Admin Features
- **Comprehensive Dashboard**: Overview of ticket statistics and recent activity
- **Ticket Management**: View, update, and manage all tickets from admin panel
- **Status Management**: Customizable ticket statuses with color coding
- **Assignment System**: Assign tickets to specific employees
- **Employee Role**: Limited access role for ticket employees
- **Email Notifications**: Automatic email updates for customers and staff
- **File Management**: Secure file upload and download system
- **Search & Filters**: Filter tickets by customer, date, status, and more

### Technical Features
- **Database Optimization**: Efficient database structure with proper indexing
- **Security**: Nonce verification, input sanitization, and secure file handling
- **AJAX Interface**: Smooth user experience without page reloads
- **Responsive Design**: Works perfectly on desktop, tablet, and mobile
- **WordPress Standards**: Follows WordPress coding standards and best practices

## Installation

1. Download the plugin files
2. Upload to your WordPress `/wp-content/plugins/` directory
3. Activate the plugin through the WordPress admin panel
4. The plugin will automatically create necessary database tables

## Usage

### For Administrators

1. **Access the Plugin**: Go to "Tire Tickets" in your WordPress admin menu
2. **Dashboard**: View statistics and recent tickets
3. **Manage Tickets**: Use "Open Tickets" and "Closed Tickets" to manage cases
4. **Status Management**: Customize ticket statuses in "Ticket Statuses"
5. **Employee Management**: Assign the "Ticket Employee" role to staff members

### For Customers

1. **Submit Tickets**: Visit `/new-ticket` on your website
2. **Access Tickets**: Visit `/ticket-login` to access existing tickets
3. **Track Progress**: View real-time updates and status changes
4. **Respond**: Provide additional information when requested

### Default Ticket Statuses

- **Open**: New tickets awaiting review
- **Assigned**: Tickets assigned to an employee
- **More Information Required**: Waiting for customer response
- **In Progress**: Actively being worked on
- **Escalated to Management**: Escalated for management review
- **Escalated to Bridgestone**: Escalated to manufacturer
- **Resolved**: Issue resolved successfully
- **Closed**: Ticket closed (final status)
- **Rejected**: Not eligible for compensation (final status)

## File Structure

```
altalayi-ticket-system/
├── altalayi-ticket-system.php     # Main plugin file
├── includes/
│   ├── class-database.php         # Database operations
│   ├── class-admin.php            # Admin interface
│   ├── class-frontend.php         # Frontend interface
│   ├── class-email.php            # Email notifications
│   ├── class-ajax.php             # AJAX handlers
│   └── functions.php              # Helper functions
├── templates/
│   ├── admin/                     # Admin templates
│   ├── frontend/                  # Frontend templates
│   └── emails/                    # Email templates
├── assets/
│   ├── css/                       # Stylesheets
│   └── js/                        # JavaScript files
└── README.md                      # This file
```

## Database Tables

The plugin creates the following database tables:

- `wp_altalayi_tickets`: Main ticket information
- `wp_altalayi_ticket_attachments`: File attachments
- `wp_altalayi_ticket_statuses`: Ticket status definitions
- `wp_altalayi_ticket_updates`: Ticket history and updates

## Customization

### Adding Custom Statuses

1. Go to "Tire Tickets" > "Ticket Statuses"
2. Click "Add New Status"
3. Configure name, color, and order
4. Save the status

### Email Templates

Email templates are located in `/templates/emails/` and can be customized:

- `ticket-created.php`: New ticket confirmation
- `status-update.php`: Status change notifications
- `customer-response.php`: Customer response notifications
- `ticket-assigned.php`: Assignment notifications

### Styling

Customize the appearance by modifying:

- `/assets/css/frontend.css`: Customer-facing styles
- `/assets/css/admin.css`: Admin interface styles

## Hooks and Filters

The plugin provides several WordPress hooks for customization:

### Actions
- `altalayi_ticket_created`: Fired when a new ticket is created
- `altalayi_ticket_status_changed`: Fired when ticket status changes
- `altalayi_ticket_assigned`: Fired when ticket is assigned

### Filters
- `altalayi_email_template`: Customize email templates
- `altalayi_allowed_file_types`: Modify allowed file types
- `altalayi_max_file_size`: Change maximum file upload size

## Security

The plugin implements comprehensive security measures:

- **Nonce Verification**: All form submissions use WordPress nonces
- **Input Sanitization**: All user input is properly sanitized
- **File Validation**: Uploaded files are validated for type and size
- **Access Control**: Proper permission checks for all actions
- **SQL Injection Prevention**: Uses prepared statements
- **XSS Protection**: Output is properly escaped

## Support

For support and customization requests, please contact the development team.

## Changelog

### Version 1.0.0
- Initial release
- Complete ticket management system
- Customer portal
- Admin interface
- Email notifications
- File upload system
- Responsive design

## License

This plugin is proprietary software developed for Altalayi company.

## Credits

Developed for Altalayi Company's tire warranty and customer support operations.
