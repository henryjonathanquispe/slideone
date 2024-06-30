<?php

namespace ProcessMaker\Package\PackageSlideshow\Mail;

use ProcessMaker\Packages\Connectors\Email\Jobs\SendEmail;
use ProcessMaker\Packages\Connectors\Email\ScreenRenderer;

class EmailProvider
{
    public function screenRenderer($config, $data)
    {
        return ScreenRenderer::render($config, $data);
    }

    public function send($emailConfig)
    {
        SendEmail::dispatchSync($emailConfig);
    }
}