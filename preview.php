<?php

$invoice_no   = $_POST['invoice_no'] ?? '';
$invoice_date = $_POST['invoice_date'] ?? '';
$discount     = (float)($_POST['discount'] ?? 0);

// ---------- FROM / TO ----------
$from = trim(
    ($_POST['from_name'] ?? '') . "\n" .
    ($_POST['from_email'] ?? '') . "\n" .
    ($_POST['from_phone'] ?? '') . "\n" .
    ($_POST['from_address'] ?? '')
);

$to = trim(
    ($_POST['to_name'] ?? '') . "\n" .
    ($_POST['to_email'] ?? '') . "\n" .
    ($_POST['to_phone'] ?? '') . "\n" .
    ($_POST['to_address'] ?? '')
);

// ---------- ITEMS ----------
$items_raw = $_POST['items'] ?? '';
$items = [];
$subtotal = 0;

foreach (preg_split("/\r\n|\n|\r/", $items_raw) as $line) {
    $line = trim($line);
    if ($line === '') continue;

    $parts = array_map('trim', explode('|', $line));
    if (count($parts) < 3) continue;

    [$desc, $qty, $price] = $parts;

    $qty   = (float)$qty;
    $price = (float)$price;
    $total = $qty * $price;

    $subtotal += $total;

    $items[] = compact('desc', 'qty', 'price', 'total');
}

// ---------- PAYMENTS ----------
$payments = [];
$payments_raw = $_POST['payments'] ?? '';

foreach (preg_split("/\r\n|\n|\r/", $payments_raw) as $line) {
    $line = trim($line);
    if ($line === '') continue;

    $parts = array_map('trim', explode('|', $line));
    if (count($parts) !== 3) continue;

    [$amount, $method, $date] = $parts;

    $payments[] = [
        'amount' => (float)$amount,
        'method' => $method,
        'date'   => $date
    ];
}

// ---------- TAX ----------
$tax_rate = (float)($_POST['tax_rate'] ?? 0);
$tax_type = $_POST['tax_type'] ?? 'after_discount';

$tax_base = ($tax_type === 'before_discount')
    ? $subtotal
    : ($subtotal - $discount);

$tax_amount = max(0, ($tax_base * $tax_rate) / 100);

// ---------- TOTALS ----------
$total_due = max(0, ($subtotal - $discount) + $tax_amount);

// ---------- STATUS ----------
$total_paid = 0;
foreach ($payments as $p) {
    $total_paid += $p['amount'];
}

if ($total_paid <= 0) {
    $status = 'UNPAID';
} elseif ($total_paid < $total_due) {
    $status = 'PARTIALLY PAID';
} else {
    $status = 'PAID';
}

// ---------- TEMPLATE ----------
require 'invoice-template.php';
?>

<div style="text-align:center; margin:20px;">
    <a href="download.php?data=<?= urlencode(serialize($_POST)) ?>">
        Download PDF
    </a>
</div>
