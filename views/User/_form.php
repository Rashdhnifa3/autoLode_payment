<?php $errors = $errors ?? []; ?>
<input type="hidden" name="_token" value="<?= e($csrfToken) ?>">

<?php if ($errors): ?>
    <div class="validation-errors" role="alert">
        <p>Please correct the following:</p>
        <ul>
            <?php foreach ($errors as $message): ?>
                <li><?= e($message) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<div class="form-group">
    <label for="name">Name</label>
    <input type="text" id="name" name="name" required maxlength="100"
           autocomplete="name" value="<?= e((string) ($values['name'] ?? '')) ?>"
           aria-invalid="<?= isset($errors['name']) ? 'true' : 'false' ?>">
</div>

<div class="form-group">
    <label for="email">Email</label>
    <input type="email" id="email" name="email" required maxlength="255"
           autocomplete="email" value="<?= e((string) ($values['email'] ?? '')) ?>"
           aria-invalid="<?= isset($errors['email']) ? 'true' : 'false' ?>">
</div>

<div class="form-group">
    <label for="password"><?= $isEdit ? 'New password (optional)' : 'Password' ?></label>
    <input type="password" id="password" name="password" minlength="8" maxlength="72"
           autocomplete="new-password" aria-describedby="password-help"
           aria-invalid="<?= isset($errors['password']) ? 'true' : 'false' ?>"
           <?= $isEdit ? '' : 'required' ?>>
    <small id="password-help">
        <?= $isEdit ? 'Leave blank to keep the current password. ' : '' ?>Use at least 8 characters.
    </small>
</div>

<div class="form-group">
    <label for="age">Age</label>
    <input type="number" id="age" name="age" required min="0" max="255" step="1"
           value="<?= e((string) ($values['age'] ?? '')) ?>"
           aria-invalid="<?= isset($errors['age']) ? 'true' : 'false' ?>">
</div>
