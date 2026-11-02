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

}
?>
