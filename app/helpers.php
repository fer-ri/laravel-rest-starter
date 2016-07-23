<?php

if (! function_exists('mailer')) {
    function mailer()
    {
        return app()->make(App\Services\MailerService::class);
    }
}
