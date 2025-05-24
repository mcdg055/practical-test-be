<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IPAddress extends Model
{
    const TYPE_IPV4 = 'ipv4';
    const TYPE_IPV6 = 'ipv6';

    protected $table = 'ip_addresses';

    protected $fillable = [
        'ip',
        'label',
        'comments',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
