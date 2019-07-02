<?php

namespace Pingu\Activity\Entities;

use Pingu\Core\Entities\BaseModel;

class Activity extends BaseModel
{
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(config('auth.providers.users.model'));
    }
}
