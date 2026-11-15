<?php
namespace App\Controllers;

use App\Database\MysqlDatabase;
use App\Request;
use App\Services\TransactionService;
use DateTime;

class PaymentController
{
    private const CURRENCIES = ['USD', 'EUR', 'GBP'];
    private const TYPES      = ['direct', 'undirect'];
    private const AMOUNT     = 200.00;

    private MysqlDatabase $db;

    public function __construct(
        private Request $request
    ) {
        $this->db = new MysqlDatabase();
    }

    public function showForm(): void
    {
        view('payment-form', ['amount' => self::AMOUNT]);
    }

    public function create(): void
    {
        $currency = $this->request->post('currency');
        $type     = $this->request->post('payment_type');

        if (!in_array($currency, self::CURRENCIES, true) || !in_array($type, self::TYPES, true)) {
            view('payment-form', [
                'amount' => self::AMOUNT,
                'error'  => 'Please select a valid currency and payment type.',
            ], 400);
            return;
        }

        $transaction = (new TransactionService)->store([
            'currency' => $currency,
            'payment_type'     => $type,
            'amount'   => self::AMOUNT,
            'status'   => 'pending',
        ]);

        redirect('/card?transaction_id=' . $transaction->id);
    }

    public function showCardForm(): void
    {
        $id = $this->request->get('transaction_id');

        if (!ctype_digit($id)) {
            view('payment-form', [
                'amount' => self::AMOUNT,
                'error'  => 'Transaction ID is missing.',
            ], 400);
            return;
        }

        view('card-form', ['transactionId' => $id]);
    }

    public function complete(): void
    {
        $id         = $this->request->post('transaction_id');
        $name       = $this->request->post('name');
        $cardNumber = $this->request->post('cardNumber');
        $expiry     = $this->request->post('expiryDate');
        $cvv        = $this->request->post('cvv');

        if ($id === '' || $name === '' || $cardNumber === '' || $expiry === '' || $cvv === '') {
            $this->cardError($id, 'Please fill in all the required fields.', 400);
            return;
        }

        if (!ctype_digit($id)
            || !preg_match('/^[0-9]{16}$/', $cardNumber)
            || !preg_match('/^(0[1-9]|1[0-2])\/[0-9]{2}$/', $expiry)
            || !preg_match('/^[0-9]{3,4}$/', $cvv)) {
            $this->cardError($id, 'Please enter valid card details.', 400);
            return;
        }

        $expiration = DateTime::createFromFormat('!m/y', $expiry);
        if (!$expiration || $expiration < new DateTime('first day of this month')) {
            $this->cardError($id, 'Please enter a valid expiry date.', 400);
            return;
        }

        $pdo = $this->db->pdo();

        $pdo->beginTransaction();

            $stmt = $pdo->prepare("SELECT id FROM transactions WHERE id = ? AND status = 'pending' FOR UPDATE");
            $stmt->execute([$id]);
            if (!$stmt->fetch()) {
                $pdo->rollBack();
                $this->cardError($id, 'Pending transaction not found.', 404);
                return;
            }

            $stmt = $pdo->prepare(
                "INSERT INTO cards (card_number, card_holder_name, expiration_date) VALUES (?, ?, ?)"
            );
            $stmt->execute([$cardNumber, $name, $expiration->format('Y-m-t')]);
            $cardId = $pdo->lastInsertId();

            $stmt = $pdo->prepare("UPDATE transactions SET card_id = ? WHERE id = ?");
            $stmt->execute([$cardId, $id]);

        $pdo->commit();
        view('success');
    }

    private function cardError(string $id, string $message, int $status): void
    {
        view('card-form', ['transactionId' => $id, 'error' => $message], $status);
    }
}


