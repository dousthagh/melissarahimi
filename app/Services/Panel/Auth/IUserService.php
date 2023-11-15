<?php

namespace App\Services\Panel\Auth;

use App\ViewModel\User\LoginViewModel;

interface IUserService
{
    public function Login(LoginViewModel $loginViewModel);
}
