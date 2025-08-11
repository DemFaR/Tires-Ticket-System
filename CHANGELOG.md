# Changelog

All notable changes to the Altalayi Tire Ticket System will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.4.0] - 2025-08-07

### 🚀 New Features
- **WaSenderAPI Integration**: Added WaSenderAPI as an alternative to WhatsApp Business API for sending WhatsApp notifications
- **Dual WhatsApp Support**: Plugin now supports both official WhatsApp Business API and WaSenderAPI
- **Enhanced Notification Settings**: New dedicated WhatsApp tab in admin settings with separate configuration for both services
- **Priority-Based Notifications**: WaSenderAPI takes priority over WhatsApp Business API when both are enabled

### 🎛️ Admin Enhancements
- **New WhatsApp Settings Tab**: Reorganized WhatsApp settings into a dedicated tab for better organization
- **WaSenderAPI Configuration**: Complete settings interface for WaSenderAPI including API key and session management
- **Test Functionality**: Added test message functionality for both WhatsApp Business API and WaSenderAPI
- **Real-time Validation**: Enhanced form validation for API keys and phone numbers
- **Visual Indicators**: Distinct styling for WhatsApp Business (blue) and WaSenderAPI (green) sections

### 🔧 Technical Improvements
- **New Class**: Added `AltalayiTicketWaSenderAPI` class for WaSenderAPI integration
- **Enhanced Email Class**: Updated notification system to support both WhatsApp services
- **Improved Phone Formatting**: Enhanced phone number formatting for both APIs
- **Session Status Checking**: Added WaSenderAPI session status verification
- **Error Handling**: Comprehensive error handling and logging for both APIs

### 📱 WhatsApp Notifications
- **Service Selection**: Choose between WhatsApp Business API (official) or WaSenderAPI (alternative)
- **Configuration Options**: Separate settings for API keys, tokens, and notification preferences
- **Message Templates**: Consistent message templates across both services
- **Notification Types**: Support for ticket creation, status updates, and employee response notifications

## [1.3.0] - 2025-07-29

### 🌐 Polylang Integration & URL Routing
- **Polylang Compatibility**: Seamless integration with Polylang multilingual plugin
- **Language-Aware URL Routing**: Enhanced URL functions with automatic language detection
- **Arabic Page Settings**: Optional dedicated Arabic pages in admin settings
- **Multilingual URL Structure**: Support for both parameter-based and path-based language routing
- **Language Detection**: Automatic detection from URL parameters, paths, and WordPress locale

### 🎛️ Admin Enhancements
- **Arabic Page Configuration**: New admin settings for separate Arabic pages
- **Frontend Pages Settings**: Enhanced configuration for multilingual page setup
- **Language-Specific URLs**: Dedicated settings for Arabic versions of create, access, and view pages
- **Fallback URL System**: Automatic fallback to main pages with language parameters

### 🚀 URL Functions Enhancement
- **Enhanced URL Functions**: Updated `altalayi_get_create_ticket_url()`, `altalayi_get_access_ticket_url()`, and `altalayi_get_ticket_view_url()` with language support
- **Language Parameter Support**: Functions now accept language parameters for explicit language targeting
- **Automatic Language Context**: URLs automatically adapt based on current language context
- **Polylang URL Integration**: Native support for Polylang's URL structure

### 🗑️ Code Cleanup
- **Removed Language Switcher**: Removed custom language switcher in favor of Polylang's native switcher
- **Simplified Interface**: Cleaner frontend templates without redundant language switching
- **Optimized Functions**: Removed unused language switcher functions and templates

### 📚 Documentation Updates
- **Updated Shortcode Guide**: Revised to reflect three core shortcodes (removed language switcher)
- **Enhanced README**: Added Polylang integration information and multilingual features
- **Arabic Translation Updates**: Added new translation strings for Arabic page settings

## [1.2.0] - 2025-07-29

### 🌐 Internationalization & Localization
- **Complete Arabic Translation**: Added comprehensive Arabic localization for all frontend components
- **Loco Translate Integration**: Created professional translation template (POT file) with 200+ translatable strings
- **Frontend Translation Focus**: Fully translated customer-facing interfaces including:
  - Ticket creation forms with all field labels and placeholders
  - Ticket login page with help text and instructions
  - Ticket viewing interface with status messages
  - All AJAX response messages and validation errors
  - Email templates and notifications
  - Status indicators and system messages

### ✨ Translation Features
- **Arabic Language Pack**: Complete Arabic PO/MO files ready for production use
- **Cultural Adaptation**: Properly localized text for Arabic-speaking users
- **Bilingual Support**: Seamless integration with WordPress language switching
- **Professional Translation**: Native Arabic translations by Arabic speakers
- **Right-to-Left (RTL) Ready**: Compatible with RTL text direction

### 🎯 User Experience Improvements
- **Native Language Support**: Arabic customers can now use the system in their native language
- **Improved Accessibility**: Better user experience for Arabic-speaking customers
- **Professional Presentation**: Consistent Arabic terminology throughout the system
- **Enhanced Communication**: Clear Arabic instructions and help text

### 🛠️ Technical Enhancements
- **Translation Infrastructure**: Proper WordPress i18n implementation
- **Text Domain**: Consistent `altalayi-ticket` text domain usage
- **Translation Functions**: Proper use of `_e()`, `__()`, and `esc_html__()` functions
- **Loco Translate Compatible**: Ready for easy translation management

### 📝 Documentation Updates
- Updated plugin description to mention Arabic localization
- Enhanced README with internationalization features
- Improved plugin metadata for translation support

## [1.1.0] - 2025-07-29

### 🚀 Major Improvements
- **Fixed Admin Email Notifications**: Resolved critical issue where admin emails weren't being sent for new tickets
- **Enhanced Role-Based Notifications**: Completely overhauled role-based notification system with improved reliability
- **Notification System Rewrite**: Complete rewrite of email notification logic for better performance and reliability

### 🔧 Critical Fixes
- Fixed `get_admin_email()` method using wrong settings key (`company_email` vs `admin_notification_email`)
- Added proper notification type checking with `altalayi_notification_type_enabled()`
- Fixed file loading order issue where `functions.php` was loaded after classes that depend on it
- Resolved settings key conflicts in email configuration

### ✨ New Features
- **Multiple Notification Recipients**: Support for sending notifications to multiple email addresses
- **Enhanced Role-Based System**: Improved support for notifying users with specific WordPress roles
- **Additional Email Recipients**: Added support for custom email addresses in notification settings
- **Comprehensive Email Management**: New `altalayi_get_notification_emails()` function for better recipient management

### 🛠️ Technical Improvements
- Enhanced error handling throughout the email system
- Improved settings validation and safety checks
- Better template fallback system with `basic.php` template
- Enhanced logging capabilities for debugging (removed in production)
- Improved function organization and code structure

### 🧹 Code Quality
- Cleaned up all debugging code for production release
- Improved code documentation and comments
- Enhanced function naming and organization
- Better separation of concerns

### 📧 Email System Enhancements
- Fixed email template loading with proper fallbacks
- Improved email header and footer generation
- Enhanced HTML email formatting
- Better email content management

### ⚙️ Configuration Improvements
- Added safety checks for `notification_roles` array validation
- Improved settings form processing
- Enhanced default value handling
- Better configuration validation

## [1.0.0] - 2024-12-XX

### 🎉 Initial Release
- Complete ticket management system
- Customer portal with secure ticket access
- Admin dashboard for support staff
- Basic email notification system
- File upload and attachment support
- Ticket status tracking and management
- Staff assignment functionality
- Shortcode integration for easy page embedding
- Elementor widgets for page builders
- Custom user roles and permissions
- Multi-language support preparation
- Database structure and management
- Frontend and admin styling
- AJAX-powered interactions
- Security implementations (nonce verification, input sanitization)
- Theme compatibility features

### 📋 Core Features
- **Ticket Creation**: Customer-facing form for submitting tire complaints
- **Ticket Management**: Complete admin interface for managing tickets
- **Status System**: Configurable ticket statuses with color coding
- **Assignment System**: Assign tickets to specific support staff
- **File Uploads**: Support for customer and staff file attachments
- **Email Notifications**: Basic email notification system
- **Access Control**: Secure ticket access using ticket number and phone
- **Shortcodes**: Easy integration with WordPress pages and posts
- **Custom Pages**: Dedicated pages for ticket functions
- **Responsive Design**: Mobile-friendly interface

### 🔧 Technical Features
- Custom database tables for ticket data
- WordPress user role integration
- AJAX-powered interactions
- Secure file upload handling
- Email template system
- Settings management
- Rewrite rules for custom URLs
- Plugin activation/deactivation handling

---

## Upgrade Notes

### From 1.0.0 to 1.1.0
- **Email Notifications**: If email notifications weren't working properly, they should now work correctly
- **Settings**: Review your notification settings in Admin → Altalayi Tickets → Settings → Email Notifications
- **Role Configuration**: Check and configure which user roles should receive notifications
- **No Database Changes**: This update doesn't require any database modifications

### Important
- Always backup your site before updating
- Test email notifications after updating
- Review notification settings to ensure they match your requirements

## Support
For support and questions, please refer to the plugin documentation or contact the development team.
