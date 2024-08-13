<?php

namespace ProcessMaker\Package\PackageSlideshow\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use ProcessMaker\Http\Controllers\Controller;
use ProcessMaker\Http\Resources\ApiCollection;
use ProcessMaker\Http\Resources\ApiResource;
use ProcessMaker\Models\Process;
use ProcessMaker\Package\PackageSlideshow\Mail\UserEmail;

class MailController extends Controller
{
    public function sendEmail(Request $request, Process $process, UserEmail $sendEmail)
    {
        try {
            $request->validate([
                'list_emails' => 'required|array',
            ]);
            $emails = implode(', ', $request->input('list_emails'));
            $response = $sendEmail->sendMailToUsers(
                $emails,
                $process
            );

            return response()->json($response, 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
