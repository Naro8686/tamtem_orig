<?php

namespace App\Http\Middleware;

use Illuminate\Cookie\Middleware\EncryptCookies as Middleware;

class EncryptCookies extends Middleware
{
    /**
     * The names of the cookies that should not be encrypted.
     *
     * @var array
     */
    protected $except = [
        'tamtem-ref-inn',
        'tamtem-ref-org-name',
        'tamtem-ref-agent-id',
        'tamtem-ref-bid-id',
        'api_token',
        'api_auth'
    ];
}
