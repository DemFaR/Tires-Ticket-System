# WaSenderAPI Integration Guide

## Overview

The Altalayi Ticket System now supports WaSenderAPI as an alternative to WhatsApp Business API for sending WhatsApp notifications to customers. This integration provides a more accessible way to send WhatsApp messages without requiring official WhatsApp Business API approval.

## Features

- **Alternative WhatsApp Solution**: Use WaSenderAPI instead of or alongside WhatsApp Business API
- **Priority-Based Notifications**: WaSenderAPI takes priority when both services are enabled
- **Session Management**: Support for multiple WhatsApp sessions
- **Test Functionality**: Built-in test message feature to verify configuration
- **Comprehensive Notifications**: Support for all notification types (ticket creation, status updates, employee responses)

## Setup Instructions

### 1. Create WaSenderAPI Account

1. Visit [wasenderapi.com](https://wasenderapi.com)
2. Create an account and verify your email
3. Log in to your dashboard

### 2. Set Up WhatsApp Session

1. Go to the Sessions section in your WaSenderAPI dashboard
2. Click "Create New Session"
3. Scan the QR code with your WhatsApp mobile app
4. Copy your API key once the session is connected

### 3. Configure Plugin Settings

1. Navigate to **Altalayi Tickets > Settings** in WordPress admin
2. Go to the **WhatsApp** tab
3. Scroll down to **WaSenderAPI (Alternative Solution)** section
4. Enable WaSenderAPI notifications
5. Enter your API key
6. Optionally enter a session ID if you have multiple sessions
7. Select which notification types to enable
8. Save settings

### 4. Test Configuration

1. Enter a test phone number (with country code, e.g., +966501234567)
2. Click "Send Test Message"
3. Verify the message is received on WhatsApp

## Configuration Options

### Required Settings

- **Enable WaSenderAPI Notifications**: Main toggle to enable/disable the service
- **API Key**: Your WaSenderAPI key from the dashboard

### Optional Settings

- **Session ID**: Specific session identifier (leave empty for default session)
- **Notification Types**: Choose which events trigger WhatsApp notifications
  - New ticket creation
  - Ticket status changes
  - Employee responses

## API Details

### Endpoint
- **Base URL**: `https://www.wasenderapi.com/api/`
- **Send Message**: `POST /send-message`

### Authentication
- **Method**: Bearer Token
- **Header**: `Authorization: Bearer {your-api-key}`

### Message Format
```json
{
  "to": "+966501234567",
  "text": "Your message content here",
  "sessionId": "optional-session-id"
}
```

## Phone Number Format

WaSenderAPI expects phone numbers in international format:
- **Correct**: `+966501234567` (with country code and + symbol)
- **Incorrect**: `0501234567` (without country code)

The plugin automatically formats Saudi Arabian numbers (starting with 0) by replacing the leading 0 with +966.

## Priority System

When both WhatsApp Business API and WaSenderAPI are enabled:
1. WaSenderAPI notifications are sent first (higher priority)
2. WhatsApp Business API notifications are skipped to avoid duplicates
3. Email notifications are always sent regardless of WhatsApp settings

## Troubleshooting

### Common Issues

1. **Test Message Fails**
   - **Verify API key is correct**: Copy the exact API key from your WaSenderAPI dashboard
   - **Check session status**: Ensure your WhatsApp session is connected in the WaSenderAPI dashboard
   - **Ensure phone number includes country code**: Use format +966501234567 (not 0501234567)
   - **Confirm WhatsApp session is active**: Make sure the device with WhatsApp is online and connected

2. **"Unable to verify session status" Error**
   - This error has been removed in the latest version - try the test again
   - If still occurring, check that your API key is valid and not expired
   - Verify you have an active session in your WaSenderAPI dashboard

3. **Messages Not Received**
   - Check API key and session settings in WaSenderAPI dashboard
   - Verify the target phone number has WhatsApp installed and is active
   - Check WaSenderAPI dashboard for message status and delivery reports
   - Review WordPress error logs for detailed error messages
   - Ensure the linked WhatsApp device is online and connected to the internet

4. **Session Disconnected**
   - Re-scan QR code in WaSenderAPI dashboard
   - Check if WhatsApp app is running on the linked device
   - Ensure the device has stable internet connection
   - Try creating a new session if the current one keeps disconnecting

5. **HTTP Error 401 (Unauthorized)**
   - API key is incorrect or expired
   - Copy the API key again from your WaSenderAPI dashboard
   - Regenerate the API key if necessary

6. **HTTP Error 403 (Forbidden)**
   - Your account may have insufficient credits
   - Check your WaSenderAPI account balance and plan limits
   - Upgrade your plan if you've reached the message limit

### Debug Information

The plugin logs detailed information to help troubleshoot issues:
- **Success**: "Test message sent successfully via WaSenderAPI!"
- **API Errors**: Logged with prefix "WaSenderAPI Error"
- **HTTP Errors**: Include response codes and detailed error messages
- **Response Data**: Full API response for debugging

To view logs:
1. Enable WordPress debug logging in `wp-config.php`:
   ```php
   define('WP_DEBUG', true);
   define('WP_DEBUG_LOG', true);
   ```
2. Check `/wp-content/debug.log` for detailed error messages

### Testing Steps

1. **Basic Configuration Test**:
   - Ensure API key is entered correctly (no extra spaces)
   - Save settings before testing
   - Use a valid phone number with country code

2. **WaSenderAPI Dashboard Check**:
   - Log into your WaSenderAPI dashboard
   - Verify your session shows as "Connected"
   - Check your account balance and message limits
   - Review message history for any delivery issues

3. **WordPress Environment**:
   - Ensure your WordPress site can make outbound HTTP requests
   - Check if any firewall or security plugin is blocking API calls
   - Test with a different phone number to isolate the issue

## Support

For WaSenderAPI-specific issues:
- [WaSenderAPI Documentation](https://wasenderapi.com/api-docs)
- [WaSenderAPI Help Center](https://wasenderapi.com/help)
- [Contact WaSenderAPI Support](mailto:contact@wasenderapi.com)

For plugin integration issues:
- Check WordPress error logs
- Review plugin settings configuration
- Verify phone number formatting

## Limitations

- Requires active internet connection
- Dependent on WaSenderAPI service availability
- WhatsApp session must remain active on the linked device
- Rate limits apply based on WaSenderAPI plan
- Message delivery depends on recipient's WhatsApp availability
