<?php

use Thunderbite\TableHelper\ButtonHelper;

return [

    /*
     * Define used bootstrap version that also relates to fontawesome syntax
     */
    'bootstrap_version' => env('THUNDERBITE_BOOTSTRAP_VERSION', 4),

    'button_helper' => ButtonHelper::class,

];
