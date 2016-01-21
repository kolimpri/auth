<?php

namespace Kolimpri\Auth\Events\User;

use Illuminate\Queue\SerializesModels;

class ProfileUpdated
{
    use Event, SerializesModels;
}
