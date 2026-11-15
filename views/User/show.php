<?php $id = (int) $user['id']; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Details</title>
    <link rel="stylesheet" href="/style.css">
</head>
<body class="users-page">
<main class="container">
    <h1>User details</h1>
    <dl class="user-details">
        <dt>ID</dt>
        <dd><?= $id ?></dd>
        <dt>Name</dt>
        <dd><?= e($user['name']) ?></dd>
        <dt>Email</dt>
        <dd><?= e($user['email']) ?></dd>
        <dt>Age</dt>
        <dd><?= (int) $user['age'] ?></dd>
    </dl>
    <div class="actions">
        <a href="/users/<?= $id ?>/edit">Edit</a>
        <a href="/users">Back to users</a>
        <form action="/users/<?= $id ?>" method="POST"
              onsubmit="return confirm('Delete this user?');">
            <input type="hidden" name="_method" value="DELETE">
            <input type="hidden" name="_token" value="<?= e($csrfToken) ?>">
            <button class="delete-button" type="submit">Delete</button>
        </form>
    </div>
</main>
</body>
</html>
