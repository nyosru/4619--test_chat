<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model {

    use HasFactory;

    protected $fillable = ['place', 'fio', 'sell_type', 'return_ticket'];
    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];

}
