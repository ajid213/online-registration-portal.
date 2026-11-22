# Online Registration Portal

A responsive online application/registration form built with HTML, CSS, JavaScript, jQuery, and PHP.

## Features
- Personal and application information form
- Client-side validation using JavaScript and jQuery
- Server-side validation and HTML escaping in PHP
- Formatted success receipt with generated application ID
- Print / Save as PDF from the browser
- Responsive desktop and mobile layout

## Requirements
- PHP 8.0 or newer
- A web server with PHP support (Apache, Nginx, or PHP built-in server)
- Internet connection for the jQuery CDN and Google Fonts (the form still works without the font)

## Run locally

1. Install PHP, or install XAMPP/WAMP/MAMP.
2. Copy the `online_registration_project` folder into your server's web root (for XAMPP, `htdocs`).
3. Start Apache.
4. Open `http://localhost/online_registration_project/` in your browser.

Alternatively, from this folder run:

```bash
php -S localhost:8000
```

Then open `http://localhost:8000`.

## Cloud hosting (Render using a PHP Docker image)

This project needs PHP, so deploy it to a PHP-capable host. One simple approach is Render with Docker:

1. Create a GitHub repository and upload all project files.
2. Add the `Dockerfile` from this project to the repository root.
3. In Render, create a new **Web Service** and connect the repository.
4. Choose Docker as the runtime and deploy.
5. Open the public URL Render provides.

The included Dockerfile runs PHP's built-in server on the port provided by the platform.

## Important project note
This is a student/demo registration application. It generates a receipt and keeps the latest submission in the PHP session; it does not store applications permanently in a database, send emails, or provide official admission confirmation. For real deployment, add a database, CSRF protection, HTTPS, privacy notice, and access controls as appropriate.
