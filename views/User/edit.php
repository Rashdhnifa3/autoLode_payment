<?php
$values = array_replace($user, $old ?? []);
$isEdit = true;
$id = (int) $user['id'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <link rel="stylesheet" href="/style.css">
</head>
<body class="users-page">
<main class="container">
    <h1>Edit user</h1>
    <form action="/users/<?= $id ?>" method="POST">
        <input type="hidden" name="_method" value="PUT">
        <?php require __DIR__ . '/_form.php'; ?>
        <div class="actions">
            <button type="submit">Save changes</button>
            <a href="/users/<?= $id ?>">Cancel</a>
        </div>
    </form>
</main>
</body>
</html>
