<?php

use Pingu\Activity\Entities\Activity;
use Pingu\Field\Entities\BundleFieldValue;

return [
    'name' => 'Activity',
    'lifetime' => 604800,
    'ignoreModels' => [
        Activity::class,
        BundleFieldValue::class
    ]
];
