<?php

require_once __DIR__ . '/../core/db_class.php';

class CartClass extends Database {

    public function isInCart($product_id, $ip) {

        $sql = "SELECT p_id FROM cart WHERE p_id = ? AND ip_add = ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("is", $product_id, $ip);
        $stmt->execute();
        $stmt->store_result();

        $exists = $stmt->num_rows > 0;
        $stmt->close();

        return $exists;
    }

    public function addToCart($product_id, $qty, $ip, $customer_id = null) {

        $sql = "INSERT INTO cart (p_id, ip_add, c_id, qty) VALUES (?, ?, ?, ?)";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("isii", $product_id, $ip, $customer_id, $qty);
        $success = $stmt->execute();
        if (!$success) {
            error_log('addToCart failed: ' . $stmt->error);
        }
        $stmt->close();

        return $success;
    }

    public function getCartItems($ip) {

        $sql = "SELECT cart.*,
                       products.product_title,
                       products.product_price,
                       products.product_image
                FROM cart
                JOIN products ON cart.p_id = products.product_id
                WHERE cart.ip_add = ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $ip);
        $stmt->execute();

        $result = $stmt->get_result();
        $items = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $items ?: [];
    }

    public function getCartCount($ip) {

        $sql = "SELECT COUNT(*) FROM cart WHERE ip_add = ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $ip);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();

        return (int) $count;
    }

    public function getCartTotal($ip) {

        $total = 0.0;

        foreach ($this->getCartItems($ip) as $item) {
            $total += (int) $item['qty'] * (float) $item['product_price'];
        }

        return $total;
    }

    public function removeFromCart($product_id, $ip) {

        $sql = "DELETE FROM cart WHERE p_id = ? AND ip_add = ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("is", $product_id, $ip);
        $success = $stmt->execute();
        if (!$success) {
            error_log('removeFromCart failed: ' . $stmt->error);
        }
        $stmt->close();

        return $success;
    }

    public function updateQty($product_id, $new_qty, $ip) {

        // Quantity must be at least 1 — use removeFromCart() to delete an item
        if ($new_qty < 1) {
            return false;
        }

        $sql = "UPDATE cart SET qty = ? WHERE p_id = ? AND ip_add = ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("iis", $new_qty, $product_id, $ip);
        $success = $stmt->execute();
        if (!$success) {
            error_log('updateQty failed: ' . $stmt->error);
        }
        $stmt->close();

        return $success;
    }

}
?>
