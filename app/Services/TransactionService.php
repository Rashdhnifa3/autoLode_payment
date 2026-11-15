<?php

namespace App\Services;

use App\Models\Transaction;

class TransactionService
{
    public function store(array $data): Transaction
    {
        return Transaction::create($data);
    }
}