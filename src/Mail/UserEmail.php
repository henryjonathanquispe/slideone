<?php

namespace ProcessMaker\Package\PackageSlideshow\Mail;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use ProcessMaker\Models\Process;
use ProcessMaker\Models\Screen;
use ProcessMaker\Packages\Connectors\ActionsByEmail\EmailProvider;

class UserEmail
{
    private $emailProvider;

    public function __construct(EmailProvider $emailProvider)
    {
        $this->emailProvider = $emailProvider;
    }

    public function sendMailToUsers($emails, Process $process)
    {
        $emailScreen = Screen::where('title', 'DEFAULT_EMAIL_SLIDESHOW_NOTIFICATION')->first();
        $routeUrl = route('package.slideshow.index', ['process_uuid' => $process->uuid]);
        $defaultBody = __('Mail Delivery Slideshow') . ' ' . $routeUrl;
        if ($emailScreen) {
            $data = [
                '_user' => [],
                '_request' => ['user_id' => Auth::user()->id],
                'urlSlideshow' => $routeUrl,
                'slideshowName' => __('Slideshow Link'),
                'imgHeader' => config('app.url') . '/img/header-slideshow-default-email.png',
                'imgFooter' => config('app.url') . '/img/footer-slideshow-default-email.png',
            ];
            $infoRenderer = $this->emailProvider->screenRenderer($emailScreen->config, $data);
            $body = $infoRenderer['body'] ?? $defaultBody;
        } else {
            $body = $defaultBody;
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
            'emailServer' => 0,
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
