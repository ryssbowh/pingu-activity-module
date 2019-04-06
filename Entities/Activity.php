<?php

namespace Modules\Activity\Entities;

use Modules\Core\Entities\BaseModel;

class Activity extends BaseModel
{
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(config('auth.providers.users.model'));
    }
}
