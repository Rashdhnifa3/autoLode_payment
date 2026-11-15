<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/style.css">
    <title>Payment</title>
</head>
<body>
<h1>Payment</h1>
<div class="container">
    <form action="/card" method="POST">
        <input type="hidden" name="transaction_id" value="<?= e($transactionId) ?>">
        <div class="form-group">
            <label for="name">Name on Card:</label>
            <input type="text" id="name" name="name" required>
        </div>
        <div class="form-group">
            <label for="cardNumber">Card Number:</label>
            <input type="text" id="cardNumber" name="cardNumber" pattern="[0-9]{16}" required>
        </div>
        <div class="form-group">
            <label for="expiryDate">Expiry Date:</label>
            <input type="text" id="expiryDate" name="expiryDate" placeholder="MM/YY"
                   pattern="(0[1-9]|1[0-2])\/([0-9]{2})" required>
        </div>
        <div class="form-group">
            <label for="cvv">CVV:</label>
            <input type="text" id="cvv" name="cvv" pattern="[0-9]{3,4}" required>
        </div>
        <button type="submit">Submit Payment</button>
    </form>
    <?php if (!empty($error)): ?>
        <div id="message"><?= e($error) ?></div>
    <?php endif; ?>
</div>
</body>
</html>