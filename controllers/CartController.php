<?php

require_once __DIR__ . '/../classes/CartClass.php';

class CartController {

    private $cartModel;

    public function __construct() {
        $this->cartModel = new CartClass();
    }

    public function isInCart($product_id, $ip) {
        return $this->cartModel->isInCart((int) $product_id, $ip);
    }

    public function addToCart($product_id, $qty, $ip, $customer_id = null) {
        $customer_id = $customer_id !== null ? (int) $customer_id : null;
        return $this->cartModel->addToCart((int) $product_id, max(1, (int) $qty), $ip, $customer_id);
    }

    public function getCartItems($ip) {
        return $this->cartModel->getCartItems($ip);
    }

    public function getCartCount($ip) {
        return $this->cartModel->getCartCount($ip);
    }

    public function getCartTotal($ip) {
        return $this->cartModel->getCartTotal($ip);
    }

    public function removeFromCart($product_id, $ip) {
        return $this->cartModel->removeFromCart((int) $product_id, $ip);
    }

    public function updateQty($product_id, $new_qty, $ip) {
        return $this->cartModel->updateQty((int) $product_id, (int) $new_qty, $ip);
    }

    public function processPayment($customer_id, $ip, $reference, $amount, $currency = 'GHS') {
        return $this->cartModel->processPayment((int) $customer_id, $ip, (string) $reference, (float) $amount, (string) $currency);
    }

    public function getOrderById($order_id) {
        return $this->cartModel->getOrderById((int) $order_id);
    }

    // Unique Paystack reference for one checkout, e.g. SHP-1731940000-3f9a1c2b7d
    public function createPaymentReference() {
        return 'SHP-' . time() . '-' . bin2hex(random_bytes(5));
    }

    /**
     * Email the customer a receipt for an order using PHPMailer over SMTP
     * (settings in core/mail_config.php). Falls back to PHP's mail() if SMTP
     * is not configured. Returns true if the email was accepted for delivery.
     */
    public function sendOrderConfirmation($order_id, $email, $name) {

        $result = $this->getOrderById($order_id);
        if (!$result || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return false;
        }

        $order = $result['order'];
        $items = $result['items'];
        // Strip line breaks so the name cannot inject extra mail headers
        $name = trim(preg_replace('/[\r\n]+/', ' ', (string) $name));

        $subject = 'Your shoppn order is confirmed - Invoice #' . (int) $order['invoice_no'];
        $html = $this->renderEmail('order_confirmation', compact('order', 'items', 'name'));
        $text = $this->orderAsText($order, $items, $name);

        $configFile = __DIR__ . '/../core/mail_config.php';
        if (is_file($configFile)) {
            require_once $configFile;
        }
        $smtpReady = defined('MAIL_USERNAME') && strpos(MAIL_USERNAME, 'you@') !== 0
                  && defined('MAIL_PASSWORD') && strpos(MAIL_PASSWORD, 'xxxx') !== 0;

        if (!$smtpReady) {
            $sent = @mail($email, $subject, $text, "From: shoppn <no-reply@shoppn.local>\r\nContent-Type: text/plain; charset=UTF-8\r\n");
            if (!$sent) {
                error_log('Order confirmation (mail()) failed for order ' . (int) $order_id);
            }
            return $sent;
        }

        require_once __DIR__ . '/../lib/PHPMailer/Exception.php';
        require_once __DIR__ . '/../lib/PHPMailer/PHPMailer.php';
        require_once __DIR__ . '/../lib/PHPMailer/SMTP.php';

        $mail = new \PHPMailer\PHPMailer\PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host       = MAIL_HOST;
            $mail->Port       = (int) MAIL_PORT;
            $mail->SMTPAuth   = true;
            $mail->Username   = MAIL_USERNAME;
            $mail->Password   = MAIL_PASSWORD;
            $mail->SMTPSecure = (int) MAIL_PORT === 465
                ? \PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_SMTPS
                : \PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Timeout    = 15;
            $mail->CharSet    = 'UTF-8';

            $mail->setFrom(MAIL_FROM, MAIL_FROM_NAME);
            $mail->addAddress($email, $name);

            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body    = $html;
            $mail->AltBody = $text;   // plain-text version for clients without HTML

            $mail->send();
            return true;
        } catch (\PHPMailer\PHPMailer\Exception $e) {
            error_log('Order confirmation (PHPMailer) failed for order ' . (int) $order_id . ': ' . $mail->ErrorInfo);
            return false;
        }
    }

    // Render an email template from views/emails/ into a string
    private function renderEmail($template, array $data) {
        extract($data);
        ob_start();
        include __DIR__ . '/../views/emails/' . $template . '.php';
        return ob_get_clean();
    }

    // Plain-text copy of the receipt
    private function orderAsText($order, $items, $name) {
        $lines = [];
        foreach ($items as $item) {
            $lineTotal = (int) $item['qty'] * (float) $item['product_price'];
            $lines[] = sprintf('- %s x%d   GHS %s', $item['product_title'], (int) $item['qty'], number_format($lineTotal, 2));
        }
        return "Hi {$name},\n\n"
             . "Thank you for shopping with shoppn. Your payment was received and your order is confirmed.\n\n"
             . 'Invoice No: #' . (int) $order['invoice_no'] . "\n"
             . 'Order ID:   ' . (int) $order['order_id'] . "\n"
             . 'Date:       ' . date('j M Y', strtotime($order['order_date'])) . "\n\n"
             . implode("\n", $lines) . "\n\n"
             . 'Total paid: GHS ' . number_format((float) $order['amt'], 2) . "\n\n"
             . "Thank you,\nshoppn\n";
    }

}
?>
