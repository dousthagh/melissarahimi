<?php

namespace App\Http\Controllers;

use App\Services\Panel\Auth\UserService;
use App\ViewModel\User\LoginViewModel;
use App\ViewModel\User\RegisterViewModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    private UserService $userService;

    /**
     * @param UserService $userService
     */
    public function __construct()
    {
        $this->userService = new UserService();
    }

    public function Login()
    {
        return view('panel.guest.login');
    }

    public function Register()
    {
        return view('panel.guest.register');
    }

    public function Term()
    {
        return view('panel.guest.terms');
    }

    public function Logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }

    public function DoLogin(Request $request)
    {
        $loginViewModel = new LoginViewModel();
        $loginViewModel->setUserName($request->email);
        $loginViewModel->setPassword($request->password);
        $loginResult = $this->userService->Login($loginViewModel);
        if ($loginResult->getId() > 0) {
            return \redirect()->route("panel.dashboard");
        } else {
            return \redirect()->back()->with("state", "0");
        }
    }

    public function DoRegister(Request $request)
    {
        $validator = Validator::make($request->toArray(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            're_password' => 'required',
            'accept_term' => 'required',
        ]);
        if (!$validator->getMessageBag()->isEmpty()) {
            return redirect()->back()->withErrors($validator->errors(), 'validator');
        }

        $viewModel = new RegisterViewModel();
        $viewModel->setFirstName($request->first_name);
        $viewModel->setLastName($request->last_name);
        $viewModel->setEmail($request->email);
        $viewModel->setPassword($request->password);
        $viewModel->setConfirmPassword($request->re_password);
        $viewModel->setAcceptTerms($request->accept_term == 'on');

        $result = $this->userService->Register($viewModel);
        if(!$result['result']){
            return \redirect()->route('register')->with('state', '0')->with('message', $result['message']);
        }
        else{
            return \redirect()->route('login')->with('state', '1');
        }

    }
}
