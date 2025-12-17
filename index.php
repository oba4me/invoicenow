<!DOCTYPE html>
<html>
<head>
    <title>Create Invoice</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>

<h2>Create Invoice</h2>

<form action="preview.php" method="post">

<h3>Invoice Info</h3>
<div class="form-row">
    <div>
        <input type="text" name="invoice_no" value="INV#000546" placeholder="Invoice Number">
    </div>
    <div>
        <input type="date" name="invoice_date">
    </div>
</div>

<h3>Billed From</h3>
<input type="text" name="from_name" value="Achievable N Automated Solutions Ltd">
<input type="text" name="from_email" placeholder="Email">
<input type="text" name="from_phone" placeholder="Phone">
<textarea name="from_address" placeholder="Address"></textarea>

<h3>Billed To</h3>
<input type="text" name="to_name" placeholder="Client Name">
<input type="text" name="to_email" placeholder="Email">
<input type="text" name="to_phone" placeholder="Phone">
<textarea name="to_address" placeholder="Address"></textarea>

<h3>Items</h3>
<textarea name="items" placeholder="Description | Qty | Price (one per line)"></textarea>

<h3>Discount</h3>
<input type="number" name="discount" value="2000000">
<h3>Tax</h3>
<div class="form-row">
    <div>
        <input type="number" name="tax_rate" placeholder="Tax %" value="7.5">
    </div>
    <div>
        <select name="tax_type">
            <option value="after_discount">After Discount</option>
            <option value="before_discount">Before Discount</option>
        </select>
    </div>
</div>

<h3>Payments</h3>
<textarea name="payments" placeholder="Amount | Method | Date"></textarea>

<button type="submit">Preview Invoice</button>

</form>

</body>
</html>
