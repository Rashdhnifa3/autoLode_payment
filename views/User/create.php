<?php
$values = $old ?? [];
$isEdit = false;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create User</title>
    <link rel="stylesheet" href="/style.css">
</head>
<body class="users-page">
<main class="container">
    <h1>Create user</h1>
    <form action="/users" method="POST">
        <?php require __DIR__ . '/_form.php'; ?>
        <div class="actions">
            <button type="submit">Create user</button>
            <a href="/users">Cancel</a>
        </div>
    </form>
</main>
</body>
</html>
