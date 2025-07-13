# 📧 Cirqular Emails

**Developed by Altitude Media in South Africa.**

## 🚀 Introduction

The Cirqular Emails module is a comprehensive solution for Drupal 10/11 that provides a centralized user interface to manage email branding and override core Drupal emails with beautiful, configurable HTML templates.

This module is designed for starterkits and client projects where maintaining a consistent and professional brand identity across all email communications is crucial. It replaces the default, plain-text emails with fully themed, responsive HTML templates that are easy to configure and customize.

## ✨ Features

* **🎨 Centralized Branding UI:** A single settings page at `/admin/config/system/cirqular-emails` to manage all email branding.
* **🖼️ Global Logo & Colors:** Upload a global logo and define a full brand and system color palette that is injected into all email templates.
* **📝 Customizable Subjects & Preview Text:** Edit the subject line and inbox preview text for each email directly in the UI, with full support for Drupal's Token system.
* **⚙️ Dynamic Configuration:** The settings page intelligently shows options only for features (like TFA) whose parent modules are enabled.
* **🔔 Helpful Admin Notices:** Adds a warning message to core admin pages (like Account Settings and TFA Settings) to notify administrators that email templates are being overridden.
* **🖌️ Themable & Overridable:** All email templates are designed to be easily overridden by a custom theme for ultimate flexibility.

## 📬 Overridden Emails

This module currently overrides the following Drupal core and contrib emails:

* **System Emails**
    * Available updates notification
* **Two-Factor Authentication (TFA)**
    * TFA Enabled
    * TFA Disabled
* **User Account Emails**
    * Password Recovery
    * Welcome (New user created by administrator)
    * Welcome (User awaiting admin approval)
    * Admin Notification (User awaiting approval)
    * Welcome (No approval required)
    * Account Activated
    * Account Suspended
    * Account Cancellation Confirmation
    * Account Canceled

## 🛠️ Configuration

1.  Install the module as usual.
2.  Navigate to **Configuration > System > Cirqular Emails**.
3.  **Global Settings Tab:**
    * **Basics:** Upload your site's primary logo and enter the legal registered name for the company.
    * **Brand Colors & System Colors:** Adjust the color palette to match your brand identity.
4.  **Email Settings Tab:**
    * Click through each tab to customize the **Subject** and **Inbox Preview Text** for each individual email. You can use the "Browse available tokens" link to add dynamic content.
5.  Save the configuration.

## 🎨 Customizing Email Layouts (Theming)

This module is designed to be easily themed. To override the HTML content of any email:

1.  Locate the base template file inside the module's `templates/` directory (e.g., `templates/accounts/cirqular-password-recovery.html.twig`).
2.  Copy that file into your custom theme's own `templates/` directory. You can even create sub-folders to match (e.g., `my_theme/templates/accounts/cirqular-password-recovery.html.twig`).
3.  Clear Drupal's cache. Drupal will now use your theme's version of the template instead of the module's default.