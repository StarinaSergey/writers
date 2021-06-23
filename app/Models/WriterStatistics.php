<?php

namespace App\Models;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class WriterStatistics extends model
{
    protected $table = 'writers_statistics';
    protected $fillable = [
        'sw_id',
        'orders_completed',
        'orders_pending',
        'orders_refunded',
        'orders_overdue',
        'fb_rating',
        'fb_reviews'
    ];


}
