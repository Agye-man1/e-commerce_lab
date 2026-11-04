# E-Commerce Lab: Product, Brand, and Category Management

**Live Server URL:** http://169.239.251.102:442/~kofi.agyeman/shoppn/  
**GitHub Repository:** https://github.com/Agye-man1/e-commerce_lab  

---

## What I Learned

In this lab, I advanced my understanding of the Model-View-Controller (MVC) architecture by building out a complete product catalog and administration system. Specifically, I learned:
1. **Relational Database Queries & JOINs**: How to retrieve products with foreign keys (`product_cat` and `product_brand`) using SQL `INNER JOIN`s to display readable category and brand names alongside product details.
2. **Secure File Upload Handling**: How to securely handle image uploads in PHP using `$_FILES`, validating file size (≤ 2MB) and MIME types (`image/jpeg`, `image/png`, `image/gif`, `image/webp`) on both the client-side via JavaScript and server-side in PHP, while generating unique filenames (`uniqid()`) to prevent filename collisions.
3. **Dynamic Filtering & Search Queries**: How to construct dynamic queries using prepared statements and `LIKE ?` with wildcard parameters (`%$query%`) for search functionality, as well as filtering by category (`?cat=N`) or brand (`?brand=N`).
4. **Environment-Aware URL Routing**: How to build dynamic URL helpers (`app_url()`) that work consistently between local XAMPP and live Apache UserDir environments (`~kofi.agyeman/shoppn/`).

---

## How This Lab Works

This lab brings together product catalog display, search, and administrative management across the MVC layers:

1. **Catalog & Filtering Flow ([`index.php`](index.php) & [`views/home.php`](views/home.php))**:
   - `index.php` acts as the traffic controller. It checks incoming GET parameters:
     - If `?cat=N` is present, it fetches products belonging to that category via `ProductController -> getProductsByCategory()`.
     - If `?brand=N` is present, it fetches products from that brand via `ProductController -> getProductsByBrand()`.
     - Otherwise, it fetches 6 randomized featured products via `ProductController -> getFeaturedProducts(6)`.
   - The results are passed to `views/home.php`, which renders the responsive product grid with pricing in Ghana Cedis (GH₵), details links, and Add to Cart triggers.
2. **Category & Brand Sidebar ([`views/layout/sidebar.php`](views/layout/sidebar.php))**:
   - The sidebar fetches categories and brands via `ProductController` and highlights the currently active filter, allowing customers to easily filter the store.
3. **Product Search ([`views/search_results.php`](views/search_results.php))**:
   - The search input in the header submits to `search_results.php`, which executes `searchProducts()` against product titles and keywords using SQL prepared statements.
4. **Product Details ([`views/single_product.php`](views/single_product.php))**:
   - Accepts a `?pro_id=N` parameter, loads the full product record (including JOINed category and brand names) via `getProductById()`, and displays product images, descriptions, tags, and pricing. If the ID is invalid, it safely redirects home.
5. **Admin Product Management ([`views/admin/product.php`](views/admin/product.php))**:
   - Protected by `require_admin()` before HTML output.
   - Populates foreign key dropdowns for Categories (`cat_id` / `cat_name`) and Brands (`brand_id` / `brand_name`).
   - For adding: Handles `multipart/form-data` uploads, validates file types and sizes, moves the file to `images/products/`, and inserts the product.
   - For editing (`?edit_id=N`): Pre-fills fields from `getProductById()`, shows the current image preview, and optionally replaces the image if a new file is uploaded while keeping the existing file if none is chosen.

---

## What Was Challenging

1. **Secure File Upload & Edit Image Preservation**: Handling file uploads during product updates was challenging because uploading a new image is optional when editing. If an administrator only wanted to edit the price or description, the existing image filename had to be preserved without requiring re-upload or overwriting the image path.
2. **Live Server Path Resolution & Broken Assets**: Uploading the application to the university server (`http://169.239.251.102:442/~kofi.agyeman/shoppn/`) initially caused CSS and page links to return 404 errors because hardcoded `/shoppn/` paths stripped out the Apache user directory (`~kofi.agyeman/`).
3. **Multi-Table SQL JOINs & Form Validation**: Writing queries that joined `products`, `categories`, and `brands` while keeping queries strictly inside the model layer, combined with validating multiple fields (title, price, category, brand, image, description) both client-side and server-side.

---

## How I Solved the Problems

1. **Conditional File Replacement Logic**: In [`actions/update_product_action.php`](actions/update_product_action.php), I passed the existing image name via a hidden form field. The action checks if `$_FILES['product_image']['error'] !== UPLOAD_ERR_NO_FILE`. If a new file is uploaded, it validates and moves the new file; otherwise, it keeps the existing image filename before calling `updateProduct()`.
2. **Environment-Aware Base URL Detection**: In [`core/core.php`](core/core.php), I built a robust `get_base_url()` function that checks `$_SERVER['REQUEST_URI']` and `$_SERVER['SCRIPT_NAME']`. It explicitly detects when the application runs under `~kofi.agyeman/shoppn` on the live server and resolves paths to `http://169.239.251.102:442/~kofi.agyeman/shoppn/...`, while maintaining `/shoppn/` for local XAMPP.
3. **Prepared Statements with Wildcards**: For product search, I prepared the query using `LIKE ?` and passed the wildcard string `('%' . $query . '%')` into `bind_param("ss", ...)`. This allowed partial matching across titles and keywords while preventing SQL injection.

---

## Explanation of One Piece of Code

One critical piece of code is the `getProductById()` method in [`classes/ProductClass.php`](classes/ProductClass.php):

```php
public function getProductById($id) {
    $sql = "SELECT products.*,
                   categories.cat_name,
                   brands.brand_name
            FROM products
            JOIN categories ON products.product_cat = categories.cat_id
            JOIN brands ON products.product_brand = brands.brand_id
            WHERE products.product_id = ?";

    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();

    $result = $stmt->get_result();
    $product = $result->fetch_assoc();
    $stmt->close();

    return $product;
}
```

### Explanation:
* **Relational Data Retrieval**: Rather than making three separate database queries to get the product, its category name, and its brand name, this method uses `INNER JOIN`s on `product_cat = categories.cat_id` and `product_brand = brands.brand_id`. This allows the view to display "Nike" and "Footwear" directly instead of raw foreign key IDs.
* **Prepared Statement Security**: The query uses a parameter placeholder (`?`) and binds `$id` as an integer (`"i"`), neutralizing any attempt at SQL injection via the query string parameter `?pro_id=`.
* **Clean Return**: It returns a single associative array (`fetch_assoc()`) representing the product, keeping database logic strictly in the Model layer.

---

## Declaration of AI Use

I used Antigravity (powered by Gemini) as an AI pair programmer and learning assistant during this lab.

* **How I Used It**: I used the AI to help troubleshoot URL path issues on the university Apache UserDir server, structure file upload validation checks (MIME types, file size limits), implement client-side regex and file validation in `js/validate.js`, and design a modern, minimalist interface for the product grid and detail views.
* **What I Learned From It**: I learned the importance of defense-in-depth when handling file uploads—never trusting client-side validation alone, validating MIME types server-side, and ensuring upload directories have appropriate write permissions. I also learned how to diagnose network and caching discrepancies when deploying to remote web servers.

---

## What I Would Do Differently With More Time

1. **Multiple Product Images**: Add support for product image galleries (multiple images per product) with thumbnail switching on the product details page.
2. **Pagination**: Implement pagination (e.g. 12 products per page) with page numbers rather than loading all items on a single page as the catalog expands.
3. **Inventory & Stock Management**: Add a `stock_quantity` column to track inventory levels and automatically flag items as "Out of Stock" when quantity reaches zero.

---

## Where Else I Could Apply What I Learned

The concepts mastered in this lab apply to almost any production web application:
* **E-Commerce & Marketplaces**: Multi-vendor product catalogs, category filtering, search engines, and media management.
* **Real Estate & Automotive Portals**: Filtering listings by price, manufacturer/make, model, and displaying high-resolution property or vehicle photo galleries.
* **Digital Asset Libraries & Portfolios**: Securely uploading, categorizing, and searching documents, media assets, and user-generated content.
