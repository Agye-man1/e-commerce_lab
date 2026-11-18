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

    /**
     * Turn the cart for $ip into a paid order, all inside one transaction:
     * create the order, copy the cart rows into orderdetails, record the
     * payment and empty the cart. Returns the new order_id, or false.
     * $reference is the Paystack reference (already verified by the caller).
     */
    public function processPayment($customer_id, $ip, $reference, $amount, $currency = 'GHS') {

        $items = $this->getCartItems($ip);
        if (empty($items)) {
            return false;
        }

        $this->conn->begin_transaction();

        try {
            // 1. Invoice number
            $invoiceNo = mt_rand(100000, 999999);

            // 2. Order
            $stmt = $this->conn->prepare(
                "INSERT INTO orders (customer_id, invoice_no, order_date, order_status)
                 VALUES (?, ?, CURDATE(), 'paid')"
            );
            $stmt->bind_param("ii", $customer_id, $invoiceNo);
            if (!$stmt->execute()) {
                throw new Exception('orders insert: ' . $stmt->error);
            }
            $orderId = $stmt->insert_id;
            $stmt->close();

            // 3. Copy cart rows into orderdetails
            $stmt = $this->conn->prepare(
                "INSERT INTO orderdetails (order_id, product_id, qty)
                 SELECT ?, p_id, qty FROM cart WHERE ip_add = ?"
            );
            $stmt->bind_param("is", $orderId, $ip);
            if (!$stmt->execute()) {
                throw new Exception('orderdetails insert: ' . $stmt->error);
            }
            $stmt->close();

            // Record the payment against the order
            $stmt = $this->conn->prepare(
                "INSERT INTO payment (amt, customer_id, order_id, currency, payment_date)
                 VALUES (?, ?, ?, ?, CURDATE())"
            );
            $stmt->bind_param("diis", $amount, $customer_id, $orderId, $currency);
            if (!$stmt->execute()) {
                throw new Exception('payment insert: ' . $stmt->error);
            }
            $stmt->close();

            // 4. Empty the cart
            $stmt = $this->conn->prepare("DELETE FROM cart WHERE ip_add = ?");
            $stmt->bind_param("s", $ip);
            if (!$stmt->execute()) {
                throw new Exception('cart delete: ' . $stmt->error);
            }
            $stmt->close();

            $this->conn->commit();

            // 5. New order id
            return $orderId;

        } catch (Exception $e) {
            $this->conn->rollback();
            error_log('processPayment failed (ref ' . $reference . '): ' . $e->getMessage());
            return false;
        }
    }

    /**
     * One order with its payment and line items (product names, prices, images).
     * Returns ['order' => [...], 'items' => [...]] or null if not found.
     */
    public function getOrderById($order_id) {

        $stmt = $this->conn->prepare(
            "SELECT orders.*, payment.amt, payment.currency, payment.payment_date
             FROM orders
             LEFT JOIN payment ON payment.order_id = orders.order_id
             WHERE orders.order_id = ?"
        );
        $stmt->bind_param("i", $order_id);
        $stmt->execute();
        $order = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if (!$order) {
            return null;
        }

        $stmt = $this->conn->prepare(
            "SELECT orderdetails.product_id, orderdetails.qty,
                    products.product_title, products.product_price, products.product_image
             FROM orderdetails
             JOIN products ON orderdetails.product_id = products.product_id
             WHERE orderdetails.order_id = ?"
        );
        $stmt->bind_param("i", $order_id);
        $stmt->execute();
        $items = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return ['order' => $order, 'items' => $items ?: []];
    }

}
?>
