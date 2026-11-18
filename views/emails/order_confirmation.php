<?php
// HTML order confirmation email. Rendered by CartController::sendOrderConfirmation()
// with $order, $items and $name. Email clients need inline styles and tables.
$money = function ($amount) { return 'GH&#8373; ' . number_format((float) $amount, 2); };
?>
<!DOCTYPE html>
<html lang="en">
<head><meta charset="UTF-8"><title>Order confirmation</title></head>
<body style="margin:0;padding:0;background:#f2f2f2;font-family:Arial,Helvetica,sans-serif;color:#000;">
<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#f2f2f2;padding:24px 12px;">
<tr><td align="center">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width:600px;background:#ffffff;">
        <tr>
            <td style="padding:22px 28px;border-bottom:1px solid #e5e5e5;">
                <table role="presentation" cellpadding="0" cellspacing="0"><tr>
                    <td style="padding-right:10px;"><img src="cid:shoppnlogo" width="40" height="40" alt="" style="display:block;border:0;"></td>
                    <td style="font-size:26px;font-weight:800;letter-spacing:-0.5px;">shoppn.</td>
                </tr></table>
            </td>
        </tr>
        <tr>
            <td style="padding:28px 28px 8px;">
                <p style="margin:0 0 6px;font-size:13px;letter-spacing:2px;text-transform:uppercase;color:#15803d;font-weight:bold;">Payment received</p>
                <h1 style="margin:0 0 12px;font-size:22px;text-transform:uppercase;letter-spacing:1px;">Thank you for your order</h1>
                <p style="margin:0;font-size:15px;line-height:1.5;color:#4a4a4a;">
                    Hi <?php echo htmlspecialchars($name); ?>, your payment was received and your order is confirmed.
                </p>
            </td>
        </tr>
        <tr>
            <td style="padding:20px 28px;">
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#f2f2f2;font-size:13px;">
                    <tr>
                        <td style="padding:14px 16px;"><span style="color:#8a8a8a;text-transform:uppercase;font-size:11px;letter-spacing:1px;">Invoice No.</span><br><strong>#<?php echo (int) $order['invoice_no']; ?></strong></td>
                        <td style="padding:14px 16px;"><span style="color:#8a8a8a;text-transform:uppercase;font-size:11px;letter-spacing:1px;">Order ID</span><br><strong><?php echo (int) $order['order_id']; ?></strong></td>
                        <td style="padding:14px 16px;"><span style="color:#8a8a8a;text-transform:uppercase;font-size:11px;letter-spacing:1px;">Date</span><br><strong><?php echo htmlspecialchars(date('j M Y', strtotime($order['order_date']))); ?></strong></td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="padding:0 28px;">
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="font-size:14px;border-collapse:collapse;">
                    <tr>
                        <th align="left" style="padding:10px 0;border-bottom:2px solid #000;font-size:11px;text-transform:uppercase;letter-spacing:1px;">Product</th>
                        <th align="center" style="padding:10px 0;border-bottom:2px solid #000;font-size:11px;text-transform:uppercase;letter-spacing:1px;">Qty</th>
                        <th align="right" style="padding:10px 0;border-bottom:2px solid #000;font-size:11px;text-transform:uppercase;letter-spacing:1px;">Total</th>
                    </tr>
                    <?php foreach ($items as $item): ?>
                        <tr>
                            <td style="padding:12px 0;border-bottom:1px solid #e5e5e5;">
                                <?php echo htmlspecialchars($item['product_title']); ?><br>
                                <span style="color:#8a8a8a;font-size:12px;"><?php echo $money($item['product_price']); ?> each</span>
                            </td>
                            <td align="center" style="padding:12px 0;border-bottom:1px solid #e5e5e5;"><?php echo (int) $item['qty']; ?></td>
                            <td align="right" style="padding:12px 0;border-bottom:1px solid #e5e5e5;white-space:nowrap;"><?php echo $money((int) $item['qty'] * (float) $item['product_price']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                    <tr>
                        <td colspan="2" style="padding:16px 0;font-weight:bold;">Total paid</td>
                        <td align="right" style="padding:16px 0;font-size:20px;font-weight:bold;white-space:nowrap;"><?php echo $money($order['amt']); ?></td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="padding:20px 28px 28px;font-size:13px;line-height:1.5;color:#4a4a4a;">
                Paid securely with Paystack. Keep this email as your receipt.
            </td>
        </tr>
        <tr>
            <td style="padding:18px 28px;background:#000;color:#fff;font-size:12px;">&copy; <?php echo date('Y'); ?> shoppn &middot; Accra, Ghana</td>
        </tr>
    </table>
</td></tr>
</table>
</body>
</html>
