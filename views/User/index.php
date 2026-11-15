<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users</title>
    <link rel="stylesheet" href="/style.css">

    
</head>
<body class="users-page">
<main class="container users-list">
    <h1>Users</h1>

    <?php if (!empty($flash)): ?>
        <div class="toast toast-<?= e($flash['type']) ?>" role="status" aria-live="polite"><?= e($flash['message']) ?></div>
    <?php endif; ?>

    <a href="/users/create">Create new user</a>

    <?php if (empty($users)): ?>
        <p style="margin-top: 20px;">No users found.</p>
    <?php else: ?>
        <div class="table-wrapper">
            <table>
                <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Name</th>
                    <th scope="col">Email</th>
                    <th scope="col">Age</th>
                    <th scope="col">Actions</th>
                </tr>
                </thead>

                <tbody>
                <?php foreach ($users as $user): ?>
                    <?php $id = (int) $user['id']; ?>

                    <tr>
                        <td><?= $id ?></td>
                        <td><?= e($user['name']) ?></td>
                        <td><?= e($user['email']) ?></td>
                        <td><?= (int) $user['age'] ?></td>
                        <td>
                            <div class="actions">
                                <a href="/users/<?= $id ?>">View</a>

                                <a href="/users/<?= $id ?>/edit">Edit</a>

                                <form
                                    action="/users/<?= $id ?>"
                                    method="POST"
                                    onsubmit="return confirm('Delete this user?');"
                                >
                                    <input
                                        type="hidden"
                                        name="_method"
                                        value="DELETE"
                                    >

                                    <?php if (isset($csrfToken)): ?>
                                        <input
                                            type="hidden"
                                            name="_token"
                                            value="<?= e($csrfToken) ?>"
                                        >
                                    <?php endif; ?>

                                    <button type="submit" class="delete-button">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</main>
<script src="/assets/js/app.js" defer></script>
</body>
</html>
