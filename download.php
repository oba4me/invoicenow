<?php

require 'vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

if (!isset($_GET['data'])) {
    die('Invalid request');
}

$postData = unserialize(urldecode($_GET['data']));
if (!is_array($postData)) {
    die('Invalid invoice data');
}

$invoice_no   = $postData['invoice_no'] ?? '';
$invoice_date = $postData['invoice_date'] ?? '';
$discount     = (float)($postData['discount'] ?? 0);

// ---------- FROM / TO ----------
$from = trim(
    ($postData['from_name'] ?? '') . "\n" .
    ($postData['from_email'] ?? '') . "\n" .
    ($postData['from_phone'] ?? '') . "\n" .
    ($postData['from_address'] ?? '')
);

$to = trim(
    ($postData['to_name'] ?? '') . "\n" .
    ($postData['to_email'] ?? '') . "\n" .
    ($postData['to_phone'] ?? '') . "\n" .
    ($postData['to_address'] ?? '')
);

// ---------- ITEMS ----------
$items = [];
$subtotal = 0;

foreach (preg_split("/\r\n|\n|\r/", $postData['items'] ?? '') as $line) {
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

foreach (preg_split("/\r\n|\n|\r/", $postData['payments'] ?? '') as $line) {
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
$tax_rate = (float)($postData['tax_rate'] ?? 0);
$tax_type = $postData['tax_type'] ?? 'after_discount';

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

// ---------- RENDER ----------
ob_start();
require 'invoice-template.php';
$html = ob_get_clean();

// ---------- PDF ----------
$options = new Options();
$options->set('defaultFont', 'DejaVu Sans');
$options->set('isHtml5ParserEnabled', true);
$options->set('isRemoteEnabled', true);

$pdf = new Dompdf($options);
$pdf->loadHtml($html);
$pdf->setPaper('A4', 'portrait');
$pdf->render();
$pdf->stream("Invoice_$invoice_no.pdf", ["Attachment" => true]);
