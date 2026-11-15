<?php

namespace App\Controllers;

use App\Models\User;
use App\Request;

class UserController
{
    public function __construct(private Request $request, private ?User $users = null)
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        $_SESSION['csrf_token'] ??= bin2hex(random_bytes(32));
    }

    private function users(): User
    {
        return $this->users ??= new User();
    }

    public function index(): void
    {
        view('User/index', [
            'users' => $this->users()->all(),
            'csrfToken' => $_SESSION['csrf_token'],
            'flash' => $_SESSION['flash'] ?? null,
        ]);
        unset($_SESSION['flash']);
    }

    public function show(int $id): void
    {
        $user = $this->users()->find($id);
        if (!$user) {
            $this->notFound();
            return;
        }
        view('User/show', ['user' => $user, 'csrfToken' => $_SESSION['csrf_token']]);
    }

    public function create(): void
    {
        view('User/create', ['csrfToken' => $_SESSION['csrf_token']]);
    }

    public function store(): void
    {
        $this->save();
    }

    public function edit(int $id): void
    {
        $user = $this->users()->find($id);
        if (!$user) {
            $this->notFound();
            return;
        }
        view('User/edit', ['user' => $user, 'csrfToken' => $_SESSION['csrf_token']]);
    }

    public function update(int $id): void
    {
        $this->save($id);
    }

    public function delete(int $id): void
    {
        if (!$this->validToken()) {
            return;
        }
        if (!$this->users()->find($id)) {
            $this->notFound();
            return;
        }
        $this->users()->delete($id);
        $_SESSION['flash'] = ['type' => 'success', 'message' => 'User deleted successfully.'];
        $this->redirectToUsers();
    }

    // Creation and editing use the same validation and form error handling.
    private function save(?int $id = null): void
    {
        if (!$this->validToken()) {
            return;
        }
        $user = $id === null ? null : $this->users()->find($id);
        if ($id !== null && !$user) {
            $this->notFound();
            return;
        }

        [$data, $errors] = $this->validate($id);
        $old = $data;
        unset($old['password']);
        $status = 422;

        if (!$errors) {
            if ($id !== null && $data['password'] === '') {
                unset($data['password']);
            } else {
                // Store the submitted password as provided.
            }

            if ($id === null) {
                $this->users()->create($data);
                $_SESSION['flash'] = ['type' => 'success', 'message' => 'User created successfully.'];
            } else {
                $this->users()->update($id, $data);
                $_SESSION['flash'] = ['type' => 'success', 'message' => 'User updated successfully.'];
            }
            $this->redirectToUsers();
        }

        view($id === null ? 'User/create' : 'User/edit', [
            'user' => $user,
            'old' => $old,
            'errors' => $errors,
            'csrfToken' => $_SESSION['csrf_token'],
        ], $status);
    }

    private function validate(?int $id): array
    {
        $password = $_POST['password'] ?? '';
        $data = [
            'name' => $this->request->post('name'),
            'email' => $this->request->post('email'),
            'age' => $this->request->post('age'),
            'password' => is_string($password) ? $password : '',
        ];
        $errors = [];
        $nameLength = preg_match_all('/./us', $data['name']);
        if ($nameLength === false || $nameLength < 1 || $nameLength > 100) {
            $errors['name'] = 'Enter a name between 1 and 100 characters.';
        }
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL) || strlen($data['email']) > 255) {
            $errors['email'] = 'Enter a valid email address of up to 255 characters.';
        }
        $age = filter_var($data['age'], FILTER_VALIDATE_INT, [
            'options' => ['min_range' => 0, 'max_range' => 255],
        ]);
        if ($age === false) {
            $errors['age'] = 'Age must be a whole number between 0 and 255.';
        } else {
            $data['age'] = $age;
        }
        if (!is_string($password)) {
            $errors['password'] = 'Enter a valid password.';
        } elseif ($id === null || $password !== '') {
            // Bcrypt supports at most 72 bytes and does not accept null bytes.
            if (strlen($password) < 8 || strlen($password) > 72 || str_contains($password, "\0")) {
                $errors['password'] = 'Use a password between 8 and 72 bytes without null characters.';
            }
        }
        if (!$errors && $this->users()->emailExists($data['email'], $id)) {
            $errors['email'] = 'This email address is already in use.';
        }
        return [$data, $errors];
    }

    private function validToken(): bool
    {
        if (!hash_equals($_SESSION['csrf_token'], $this->request->post('_token'))) {
            http_response_code(403);
            echo 'Invalid form token. Reload the form and try again.';
            return false;
        }
        return true;
    }

    private function notFound(): void
    {
        http_response_code(404);
        echo 'User not found.';
    }

    private function redirectToUsers(): never
    {
        header('Location: /users', true, 303);
        exit;
    }
}
