# Altalayi Tire Ticket System

A professional WordPress plugin for managing tire warranty and customer complaint tickets for Altalayi company.

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
