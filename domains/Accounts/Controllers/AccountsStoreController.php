<?php

namespace Domains\Accounts\Controllers;

use App\Http\Controllers\Controller;
use Domains\Accounts\Models\User;
use Domains\Accounts\Notifications\VerifyEmailNotification;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class AccountsStoreController extends Controller
{
    private User $user;

    public function __construct(User $users)
    {
        $this->users = $users;
    }

    public function __invoke(Request $request): Response
    {
        $this->validate($request, [
            'name' => ['required', 'string'],
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $user = new User();
        $user->forceFill([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
        ])->save();

        $user->notify(new VerifyEmailNotification());

        return Response::create('', Response::HTTP_NO_CONTENT);
    }
}