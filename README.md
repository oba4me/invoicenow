## InvoiceNow
A simple PHP-based invoice generator that allows users to create, preview, and download professional PDF invoices using HTML forms and Dompdf.

## Features
Create invoices with custom details (from/to, items, discounts, taxes, payments)
Preview invoices in the browser
Download invoices as PDF files
Supports multiple items, tax calculations (before/after discount), and payment tracking
Responsive design for forms
Uses Dompdf for PDF generation with embedded fonts (DejaVu Sans)

## Requirements
PHP 8.1 or higher
Composer (for dependency management)
Web server (e.g., Apache, Nginx)
Extensions: DOM, MBString

## Installation
1. Clone the repository:
git clone https://github.com/yourusername/invoicenow.git
cd invoicenow

2. Install dependencies via Composer:
composer install

3. Ensure the vendor directory is properly loaded (Composer autoload).

4. (Optional) Add a logo: Place a logo.jpg file in the root directory for embedding in invoices.

5. Run on a web server. For local development, use PHP's built-in server:
php -S localhost:8000

## Usage
Open index.php in your browser to access the invoice creation form.

## Fill in the form fields:

Invoice number and date
Billed from/to details
Items (format: Description | Qty | Price, one per line)
Discount amount
Tax rate and type
Payments (format: Amount | Method | Date, one per line)
Click "Preview Invoice" to see the rendered invoice.

Click "Download PDF" to generate and download the PDF.

Project Structure
index.php: Main form for creating invoices
preview.php: Processes form data and displays preview
download.php: Generates PDF using Dompdf
invoice-template.php: HTML template for the invoice
style.css: CSS styles for the form and invoice
vendor: Composer dependencies (Dompdf, etc.)

## Contributing
Contributions are welcome! Please fork the repository and submit a pull request. For major changes, open an issue first to discuss. You can extend and build db around it.

## License
This project uses Dompdf, which is licensed under LGPL-2.1. See LICENSE for details.

## Author
Alexander Bamidele
Email: oba4me@outlook.com
