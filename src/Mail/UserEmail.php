<?php

namespace ProcessMaker\Package\PackageSlideshow\Mail;

use Illuminate\Support\Facades\Log;
use ProcessMaker\Models\Screen;

class UserEmail
{
    private $emailProvider;

    public function __construct(EmailProvider $emailProvider)
    {
        $this->emailProvider = $emailProvider;
    }

    public function sendMailToUsers($emails, $proId)
    {
        $emailScreen = Screen::where('title', 'DEFAULT_EMAIL_SLIDESHOW_NOTIFICATION')->first();
        $link = '/slideshow/main/' . $proId;
        if ($emailScreen) {
            $data = [
                "urlSlideshow" => config('app.url') . $link,
                "slideshowName" => __('Slideshow Link')
            ];
            $body = $this->emailProvider->screenRenderer($emailScreen->config, json_encode($data));
        } else {
            $body = __('Mail Delivery Slideshow') . ' ' . $link;
        }
        // Send the email
        $this->emailProvider->send([
            'email' => $emails,
            'subject' => __('Mail Delivery Slideshow'),
            'body' => $body,
            'textBody' => '',
            'addEmails' => '',
            'type' => 'html',
            'json_data' => '{}',
            'emailServer' => 0
        ]);
        // Register in the log
        Log::info('Slideshow Email: a email was sent', [
            'email' => $emails,
        ]);
    }
}
