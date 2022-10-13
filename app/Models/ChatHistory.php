<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ChatHistory extends Model
{
    use HasFactory, SoftDeletes;

    public $table = 'chat_histories';

    public const CHATS = 'chats';
    public const USER_FROM = 'user_from';
    public const USER_TO = 'user_to';
    public const MESSAGES = 'messages';
    public const STATUS = 'status';
    public const CREATED_AT = 'created_at';

    protected $fillable = [
        self::CHATS,
        self::USER_FROM,
        self::USER_TO,
        self::MESSAGES,
        self::STATUS,
        self::CREATED_AT,
    ];
}
