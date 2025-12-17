<?php
$items     = $items ?? [];
$payments  = $payments ?? [];
$subtotal  = $subtotal ?? 0;
$discount  = $discount ?? 0;
$total_due = $total_due ?? 0;
$tax_amount = $tax_amount ?? 0;
$tax_rate = $tax_rate ?? 0;

function naira($amount): string {
    return 'NGN ' . number_format((float)$amount, 2);
}

// BASE64 LOGO
$logoPath = 'logo.jpg';
$logoData = file_exists($logoPath) ? base64_encode(file_get_contents($logoPath)) : '';
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Invoice <?= htmlspecialchars($invoice_no) ?></title>
<style>
    @page {
    margin: 20mm;
}
body {
    font-family: DejaVu Sans, Arial, sans-serif;
    font-size: 12px;
    color: #333;
}

table {
    width: 100%;
    border-collapse: collapse;
}

.header-table td {
    vertical-align: top;
}

.logo img {
    max-width: 140px;
}

.invoice-title {
    text-align: right;
}

.invoice-title h1 {
    margin: 0;
    color: #2ea3f2;
}

.status {
    display: inline-block;
    padding: 4px 10px;
    font-size: 11px;
    color: #fff;
}

.status.PAID { background: #28a745; }
.status.UNPAID { background: #dc3545; }
.status.PARTIALLY { background: #f0ad4e; }

.meta td {
    padding: 4px 0;
}

.billing td {
    width: 50%;
    padding: 8px;
    background: #eaf6ff;
}

.items th {
    background: #2ea3f2;
    color: #fff;
    padding: 6px;
    border: 1px solid #ccc;
}

.items td {
    padding: 6px;
    border: 1px solid #ccc;
}

.items td:nth-child(2),
.items td:nth-child(3),
.items td:nth-child(4) {
    text-align: right;
}

.totals {
    width: 40%;
    margin-top: 10px;
    float: right;
}

.totals td {
    padding: 6px;
    border: 1px solid #ccc;
}

.totals .grand {
    font-weight: bold;
    background: #eaf6ff;
}

.payments th {
    background: #2ea3f2;
    color: #fff;
    padding: 6px;
}

.payments td {
    padding: 6px;
    border: 1px solid #ccc;
}

.footer {
    margin-top: 30px;
    font-size: 11px;
    text-align: center;
}
.invoice-meta td {
    font-size: 14px;
}

</style>

</head>
<body>

<!-- HEADER -->
<table class="header-table">
    <tr>
      <td class="logo">
    <?php if ($logoData): ?>
        <img src="data:image/jpeg;base64,<?= $logoData ?>">
    <?php endif; ?>
</td>
        <td class="invoice-title">
            <h1>INVOICE</h1>
            <span class="status <?= str_replace(' ', '', $status) ?>">
                <?= htmlspecialchars($status) ?>
            </span>
        </td>
    </tr>
</table>

<div class="clear"></div>

<!-- META -->
<table class="invoice-meta">
    <tr>
<td style="font-size:14px;"><strong>Invoice No:</strong> <?= htmlspecialchars($invoice_no) ?></td>
<td style="text-align:right; font-size:14px;"><strong>Date:</strong> <?= htmlspecialchars($invoice_date) ?></td>
</tr>
</table>

<!-- BILLING -->
<table class="billing">
    <tr>
        <td>
            <h3>Billed From</h3>
            <?= nl2br(htmlspecialchars($from)) ?>
        </td>
        <td>
            <h3>Billed To</h3>
            <?= nl2br(htmlspecialchars($to)) ?>
        </td>
    </tr>
</table>

<!-- ITEMS -->
<table class="items">
    <tr>
        <th>Description</th>
        <th>Qty</th>
        <th>Unit Price</th>
        <th>Amount</th>
    </tr>

    <?php foreach ($items as $item): ?>
    <tr>
        <td><?= htmlspecialchars($item['desc']) ?></td>
        <td><?= $item['qty'] ?></td>
        <td><?= naira($item['price']) ?></td>
        <td><?= naira($item['total']) ?></td>
    </tr>
    <?php endforeach; ?>
</table>

<!-- TOTALS -->
<table class="totals">
    <tr>
        <td>Sub Total</td>
        <td><?= naira($subtotal) ?></td>
    </tr>
    <tr>
        <td>Discount</td>
        <td><?= naira($discount) ?></td>
    </tr>

    <?php if ($tax_amount > 0): ?>
    <tr>
        <td>Tax (<?= $tax_rate ?>%)</td>
        <td><?= naira($tax_amount) ?></td>
    </tr>
    <?php endif; ?>

    <tr class="grand">
        <td>Total Due</td>
        <td><?= naira($total_due) ?></td>
    </tr>
</table>

<div class="payment-info"> <h3>Payment Details</h3> 
<p> <strong>Bank:</strong> Polaris Bank<br> 
<strong>Account No:</strong> 9016245053<br> 
<strong>Name:</strong> Achievable N Automated Solutions Ltd</p> </div> 
<div class="notes"> <h4>Notes</h4> <p>Tax is calculated after discount.</p> </div>
<div class="clear"></div>

<!-- PAYMENTS -->
<?php if (!empty($payments)): ?>
<table class="payments" style="margin-top:20px;">
    <tr>
        <th>#</th>
        <th>Amount</th>
        <th>Method</th>
        <th>Date</th>
    </tr>
    <?php foreach ($payments as $i => $p): ?>
    <tr>
        <td><?= $i + 1 ?></td>
        <td><?= naira($p['amount']) ?></td>
        <td><?= htmlspecialchars($p['method']) ?></td>
        <td><?= htmlspecialchars($p['date']) ?></td>
    </tr>
    <?php endforeach; ?>
</table>
<?php endif; ?>

<!-- FOOTER -->
<div class="footer">
    Thank you for using our services. We look forward to serving you again.
</div>

</body>
</html>
