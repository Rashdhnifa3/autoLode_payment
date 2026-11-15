<?php

namespace App\Models;

use App\Database\MysqlDatabase;

class Card
{
    public int $id;
    public string $card_number;
    public string $card_holder_name;
    public string $expiration_date;

    public static function create(array $data): static
    {
        $database = new MysqlDatabase();

        $id = $database->insert('cards', [
            'card_number' => $data['card_number'],
            'card_holder_name' => $data['card_holder_name'],
            'expiration_date' => $data['expiration_date'],
        ]);

        $card = new static();
        $card->id = (int) $id;
        $card->card_number = $data['card_number'];
        $card->card_holder_name = $data['card_holder_name'];
        $card->expiration_date = $data['expiration_date'];

        return $card;
    }
}