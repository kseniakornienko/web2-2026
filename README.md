Репозиторий для WebИС-ЛБ-ПИ-20

## Product Catalog Project

This project implements a product catalog with the following features:
- Product catalog page displaying all products from database
- Product detail page with full description
- Review system for each product
- Image storage for products

### Setup Instructions

1. **Database Setup:**
   - Create a MySQL database named `web2_catalog`
   - Run the SQL script in `schema.sql` to create tables and insert sample data

2. **Web Server:**
   - Ensure you have a PHP-enabled web server (Apache/Nginx with PHP)
   - Place the `src/` folder contents in your web root or configure accordingly

3. **Database Configuration:**
   - Update `src/config.php` with your database credentials if different from defaults

4. **Images:**
   - Place product images in `src/assets/img/products/`
   - Update image paths in the database accordingly

### File Structure
- `src/index.php` - Main catalog page
- `src/product.php` - Product detail page
- `src/config.php` - Database configuration
- `schema.sql` - Database schema
- `src/assets/styles/` - CSS files
- `src/assets/scripts/` - JavaScript files
- `src/assets/img/products/` - Product images
