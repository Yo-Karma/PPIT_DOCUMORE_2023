# Documore

### _3rd Year Project_

---

Documore is a self-hosted documentation site that allows you to create, manage and share your documentation. It's perfect for developers, technical writers, and anyone else who needs to create and share documentation. And the best part is, it's open source and free!

With Documore, you can create a document and share it via a URL within minutes. You can add admins that can work on any documents with you, or even let them create their own documents. It also includes an API, so you can manage your documents and admins via another application.

# Requirements

To run Documore, you will need:

• Linux VPS \
• Nginx \
• PHP \
• MySQL

# Installation

1. Clone this repository into your web server root directory.
2. Create a MySQL database and import the `import.sql` file.
3. Edit the config.php file and enter your database details.
4. Configure your Nginx server block to point to the public directory of the Documore installation.
5. Visit the URL of your Documore installation in your browser.

# API Usage

The Documore API allows you to manage your documents and admins programmatically. To use the API, you'll need to pass a Bearer token that was set in the `config.php` file.

## Whitelist a User

To whitelist a user for a specific document, make a GET request to the **whitelist** endpoint, passing the **username** and **documentId** as query parameters:

```php
GET /api/whitelist?username=<username>&documentId=<documentId>
Authorization: Bearer <your_token>
```

## Unwhitelist a User

To remove a user from the whitelist for a specific document, make a GET request to the **unwhitelist** endpoint, passing the **username** and **documentId** as query parameters:

```php
GET /api/unwhitelist?username=<username>&documentId=<documentId>
Authorization: Bearer <your_token>
```

# Documentation

The full documentation for Documore can be found in the docs directory.

# A little info about the project

This project was from the 2022-2023 for the PPIT module, and was created by a group of 2 students. It was reuploaded due to the original repository containing sensitive information.
