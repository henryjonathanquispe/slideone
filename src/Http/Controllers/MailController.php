<?php

namespace ProcessMaker\Package\PackageSlideshow\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use ProcessMaker\Http\Resources\ApiCollection;
use ProcessMaker\Http\Controllers\Controller;
use ProcessMaker\Http\Resources\ApiResource;
use ProcessMaker\Package\PackageSlideshow\Mail\UserEmail;

class MailController extends Controller
{
    public function sendMailToUsers(Request $request)
    {
        $userEmails = ['paula.quispe@processmaker.com'];
        $details = [
            'title' => 'Test Mail Title',
            'body' => 'This is a test mail body.'
        ];

        foreach ($userEmails as $email) {
            Mail::to($email)->send(new UserEmail($details));
        }
    }
}
