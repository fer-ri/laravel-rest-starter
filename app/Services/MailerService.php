<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Contracts\Mail\Mailer;

class MailerService
{
    /**
     * The Laravel Mailer instance.
     *
     * @var Mailer
     */
    protected $mailer;

    /**
     * The subject for email.
     *
     * @var string
     */
    protected $subject;

    /**
     * The email of sender.
     *
     * @var string
     */
    protected $from;

    /**
     * The name of sender.
     *
     * @var string
     */
    protected $fromName;

    /**
     * The recipient of the email.
     *
     * @var string
     */
    protected $to;

    /**
     * The view for the email.
     *
     * @var string
     */
    protected $view;

    /**
     * The data associated with the view for the email.
     *
     * @var array
     */
    protected $data = [];

    /**
     * Create a new app mailer instance.
     *
     * @param Mailer $mailer
     */
    public function __construct(Mailer $mailer)
    {
        $this->mailer = $mailer;

        $this->from = env('MAIL_SENDER_EMAIL');
        $this->fromName = env('MAIL_SENDER_NAME');
    }
    /**
     * Deliver the email activation.
     *
     * @param  User $user
     * @return void
     */
    public function sendEmailActivationForUser(User $user)
    {
        $this->subject = 'Confirm your email address';
        $this->to = $user->email;
        $this->view = 'emails.activation';
        $this->data = compact('user');

        $this->send();
    }
    /**
     * Send the email.
     *
     * @return void
     */
    public function send()
    {
        $this->mailer->send($this->view, $this->data, function ($message) {
            $message->subject($this->subject)
                ->from($this->from)
                ->to($this->to);
        });
    }
}
