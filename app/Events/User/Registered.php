<?php

namespace Kolimpri\Auth\Events\User;

use Illuminate\Queue\SerializesModels;

class Registered
{
    use Event, SerializesModels;
}
