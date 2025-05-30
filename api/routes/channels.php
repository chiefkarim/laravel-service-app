<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('service-requests', function ($user) {

    return true;
});
