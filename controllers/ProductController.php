<?php

require_once __DIR__ . '/../classes/ProductClass.php';

class ProductController {

    private $productModel;

    public function __construct() {
        $this->productModel = new ProductClass();
    }

    // ── Brands ──────────────────────────────────────────────

    public function addBrand($name) {
        return $this->productModel->addBrand($name);
    }

    public function getAllBrands() {
        return $this->productModel->getAllBrands();
    }

    public function getBrandById($id) {
        return $this->productModel->getBrandById((int) $id);
    }

    public function updateBrand($id, $name) {
        return $this->productModel->updateBrand((int) $id, $name);
    }

    // ── Categories ──────────────────────────────────────────

    public function addCategory($name) {
        return $this->productModel->addCategory($name);
    }

    public function getAllCategories() {
        return $this->productModel->getAllCategories();
    }

    public function getCategoryById($id) {
        return $this->productModel->getCategoryById((int) $id);
    }

    public function updateCategory($id, $name) {
        return $this->productModel->updateCategory((int) $id, $name);
    }

    // ── Products ────────────────────────────────────────────

    public function addProduct($cat, $brand, $title, $price, $desc, $image_filename, $keywords) {
        return $this->productModel->addProduct(
            (int) $cat,
            (int) $brand,
            $title,
            $price,
            $desc,
            $image_filename,
            $keywords
        );
    }

    public function updateProduct($id, $cat, $brand, $title, $price, $desc, $image_filename, $keywords) {
        return $this->productModel->updateProduct(
            (int) $id,
            (int) $cat,
            (int) $brand,
            $title,
            $price,
            $desc,
            $image_filename,
            $keywords
        );
    }

    public function getProductById($id) {
        return $this->productModel->getProductById((int) $id);
    }

    public function getAllProducts() {
        return $this->productModel->getAllProducts();
    }

    public function getFeaturedProducts($limit = 6) {
        return $this->productModel->getFeaturedProducts((int) $limit);
    }

    public function getProductsByCategory($cat_id) {
        return $this->productModel->getProductsByCategory((int) $cat_id);
    }

    public function getProductsByBrand($brand_id) {
        return $this->productModel->getProductsByBrand((int) $brand_id);
    }

    public function searchProducts($query) {
        return $this->productModel->searchProducts($query);
    }

}
?>
