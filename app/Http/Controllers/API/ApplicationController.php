<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\ApplicationStoreRequest;
use App\Http\Requests\API\ApplicationRejectRequest;
use App\Mail\ApplicationReject;
use App\Models\Application;
use App\Models\ApplicationStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class ApplicationController extends Controller
{
    public function index(Request $request): \Illuminate\Http\JsonResponse
    {
        $applications = Application::with('status')->filter($request)->get();
        return response()->json(compact('applications'));
    }

    public function statuses(): \Illuminate\Http\JsonResponse
    {
        $application_statuses = ApplicationStatus::get();
        return response()->json(compact('application_statuses'));
    }

    public function store(ApplicationStoreRequest $request): \Illuminate\Http\JsonResponse
    {
        Auth::user()->applications()->create($request->validated());
        return response()->json([
            'message' => 'Ваша заявка успешно отправлена!'
        ], 201);
    }

    public function approve(Application $application): \Illuminate\Http\JsonResponse
    {
        $application->update([
            'status_id' => Application::APPROVED
        ]);

        $application->user()->update([
            'sms_name' => $application->name
        ]);

        return response()->json(null, 204);
    }

    public function reject(Application $application, ApplicationRejectRequest $request): \Illuminate\Http\JsonResponse
    {
        $data = $request->validated();
        $data['status_id'] = Application::REJECTED;
        $application->update($data);

        //TODO перенести в events
        Mail::to($application->user->email)->send(new ApplicationReject($application));

        return response()->json(null, 204);
    }
}
