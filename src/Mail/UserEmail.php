<?php

namespace ProcessMaker\Package\PackageSlideshow\Mail;

use Illuminate\Support\Facades\Log;
use ProcessMaker\Models\Screen;
use ProcessMaker\Packages\Connectors\ActionsByEmail\EmailProvider;

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
            $data['urlSlideshow'] = config('app.url') . $link;
            $data['slideshowName'] = __('Slideshow Link');
            $body = $this->emailProvider->screenRenderer($emailScreen->config, $data);
        } else {
            $body = __('Mail Delivery Slideshow') . ' ' . $link;
        }
        // Send the email
        $this->emailProvider->send([
            'email' => $emails,
            'subject' => __('Mail Delivery Slideshow'),
            'body' => $body,
            'textBody' => '',
            'addEmails' => $emails,
            'type' => 'html',
            'json_data' => '{}',
            'emailServer' => 0
        ]);
        // Register in the log
        Log::info('Slideshow Email: a email was sent', [
            'email' => $emails,
        ]);

        // Return a response to indicate success
        return [
            'status' => 'success',
            'message' => 'Emails sent successfully',
        ];
    }
}
