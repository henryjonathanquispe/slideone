<?php

namespace ProcessMaker\Package\PackageSlideshow\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use ProcessMaker\Http\Resources\ApiCollection;
use ProcessMaker\Http\Controllers\Controller;
use ProcessMaker\Http\Resources\ApiResource;
use ProcessMaker\Models\Process;
use ProcessMaker\Package\PackageSlideshow\Mail\UserEmail;

class MailController extends Controller
{
    public function sendEmail(Request $request, Process $process, UserEmail $sendEmail)
    {
        try {
            return $sendEmail->sendMailToUsers(
                $request->input('list_emails'),
                $process->id
            );
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => __('Unable to send mail. Please check the server settings.'),
            ], 500);
        }
    }
}
