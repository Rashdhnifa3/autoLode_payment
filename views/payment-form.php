<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/style.css">
    <title>Confirm Payment</title>
</head>
<body>
<h1>Confirm Payment</h1>
<div class="container">
    <form action="/transactions" method="POST">
        <div class="form-group">
            <label for="amount">Amount:</label>
            <input type="text" id="amount" value="<?= e(number_format($amount, 2)) ?>" readonly>
        </div>
        <div class="form-group">
            <label for="payment_type">Payment Type:</label>
            <select id="payment_type" name="payment_type" required>
                <option value="direct">Direct</option>
                <option value="undirect">Undirect</option>
            </select>
        </div>
        <div class="form-group">
            <label for="currency">Currency:</label>
            <select id="currency" name="currency" required>
                <option value="USD">USD</option>
                <option value="EUR">EUR</option>
                <option value="GBP">GBP</option>
            </select>
        </div>
        <button type="submit">Connect to Pay</button>
    </form>
    <?php if (!empty($error)): ?>
        <div id="message"><?= e($error) ?></div>
    <?php endif; ?>
</div>

<!--<script src="--><?php //= __DIR__.'/../public/assets/js/app.js'?><!--"></script>-->
<script src="/assets/js/app.js"></script>
</body>
</html>