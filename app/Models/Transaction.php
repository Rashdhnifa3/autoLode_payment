<?php

namespace App\Models;

use App\Database\MysqlDatabase;

class Transaction
{
    public int $id;
    public float $amount;
    public string $currency;
    public string $payment_type;
    public string $status;
    public string $created_at; // how to handle these always
    public string $updated_at; // how to handle these always

    public static function create(array $data): static
    {
        // call mysql to insert
        $database = new MysqlDatabase();

        $id = $database->insert('transactions', $data);

        $transaction = new static();
        $transaction->id = $id;
        $transaction->amount = $data['amount'];
        $transaction->currency = $data['currency'];
        $transaction->payment_type = $data['payment_type'];
        $transaction->status = $data['status'];

        return $transaction;
    }
}