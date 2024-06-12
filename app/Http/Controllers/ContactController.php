<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\ContactResource;
use App\Http\Requests\ContactCreateRequest;

class ContactController extends Controller
{
    public function create(ContactCreateRequest $request): JsonResponse {
        $data = $request->validated();
        $user = Auth::user();

        $contact = new Contact($data);
        $contact->user_id = $user->id;

        $contact->save();

        return (new ContactResource($contact))->response()->setStatisCode(201);
    }
}
