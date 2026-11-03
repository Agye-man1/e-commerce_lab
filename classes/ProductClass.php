<?php

require_once __DIR__ . '/../core/db_class.php';

class ProductClass extends Database {

    // ── Brands ──────────────────────────────────────────────

    public function addBrand($name) {

        $sql = "INSERT INTO brands (brand_name) VALUES (?)";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $name);
        $success = $stmt->execute();
        $stmt->close();

        return $success;
    }

    public function getAllBrands() {

        $sql = "SELECT * FROM brands ORDER BY brand_name ASC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();

        $result = $stmt->get_result();
        $brands = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $brands;
    }

    public function getBrandById($id) {

        $sql = "SELECT * FROM brands WHERE brand_id = ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();

        $result = $stmt->get_result();
        $brand = $result->fetch_assoc();
        $stmt->close();

        return $brand;
    }

    public function updateBrand($id, $name) {

        $sql = "UPDATE brands SET brand_name = ? WHERE brand_id = ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("si", $name, $id);
        $success = $stmt->execute();
        $stmt->close();

        return $success;
    }

    // ── Categories ──────────────────────────────────────────

    public function addCategory($name) {

        $sql = "INSERT INTO categories (cat_name) VALUES (?)";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $name);
        $success = $stmt->execute();
        $stmt->close();

        return $success;
    }

    public function getAllCategories() {

        $sql = "SELECT * FROM categories ORDER BY cat_name ASC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();

        $result = $stmt->get_result();
        $categories = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $categories;
    }

    public function getCategoryById($id) {

        $sql = "SELECT * FROM categories WHERE cat_id = ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();

        $result = $stmt->get_result();
        $category = $result->fetch_assoc();
        $stmt->close();

        return $category;
    }

    public function updateCategory($id, $name) {

        $sql = "UPDATE categories SET cat_name = ? WHERE cat_id = ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("si", $name, $id);
        $success = $stmt->execute();
        $stmt->close();

        return $success;
    }

    // ── Products ────────────────────────────────────────────

    public function addProduct($cat, $brand, $title, $price, $desc, $image_filename, $keywords) {

        $sql = "INSERT INTO products
                (product_cat, product_brand, product_title, product_price,
                 product_desc, product_image, product_keywords)
                VALUES (?, ?, ?, ?, ?, ?, ?)";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param(
            "iisdsss",
            $cat,
            $brand,
            $title,
            $price,
            $desc,
            $image_filename,
            $keywords
        );
        $success = $stmt->execute();
        $stmt->close();

        return $success;
    }

    public function updateProduct($id, $cat, $brand, $title, $price, $desc, $image_filename, $keywords) {

        $sql = "UPDATE products
                SET product_cat = ?, product_brand = ?, product_title = ?,
                    product_price = ?, product_desc = ?, product_image = ?,
                    product_keywords = ?
                WHERE product_id = ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param(
            "iisdsssi",
            $cat,
            $brand,
            $title,
            $price,
            $desc,
            $image_filename,
            $keywords,
            $id
        );
        $success = $stmt->execute();
        $stmt->close();

        return $success;
    }

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

    public function getAllProducts() {

        $sql = "SELECT products.*,
                       categories.cat_name,
                       brands.brand_name
                FROM products
                JOIN categories ON products.product_cat = categories.cat_id
                JOIN brands ON products.product_brand = brands.brand_id
                ORDER BY products.product_title ASC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();

        $result = $stmt->get_result();
        $products = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $products;
    }

    public function getFeaturedProducts($limit = 6) {

        $limit = (int) $limit;
        $sql = "SELECT * FROM products ORDER BY RAND() LIMIT ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $limit);
        $stmt->execute();

        $result = $stmt->get_result();
        $products = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $products ?: [];
    }

    public function getProductsByCategory($cat_id) {

        $sql = "SELECT * FROM products WHERE product_cat = ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $cat_id);
        $stmt->execute();

        $result = $stmt->get_result();
        $products = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $products ?: [];
    }

    public function getProductsByBrand($brand_id) {

        $sql = "SELECT * FROM products WHERE product_brand = ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $brand_id);
        $stmt->execute();

        $result = $stmt->get_result();
        $products = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $products ?: [];
    }

    public function searchProducts($query) {

        $term = '%' . $query . '%';
        $sql = "SELECT * FROM products
                WHERE product_title LIKE ? OR product_keywords LIKE ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ss", $term, $term);
        $stmt->execute();

        $result = $stmt->get_result();
        $products = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $products ?: [];
    }

}
?>
