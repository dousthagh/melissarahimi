<?php

namespace App\Services\Panel\Auth;

use App\Models\Level;
use App\Models\LevelCategory;
use App\Models\User;
use App\ViewModel\User\RegisterViewModel;
use App\ViewModel\User\UserInfoViewModel;
use App\ViewModel\User\LoginViewModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserService implements IUserService
{

    public function Login(LoginViewModel $loginViewModel): UserInfoViewModel
    {
        $result = new UserInfoViewModel();
        $user = User::where("email", "=", $loginViewModel->getUserName())
            ->where("is_active", "=", 1)->first();
        if (!$user) {
            return $result;
        }
        $checkPassword = Hash::check($loginViewModel->getPassword(), $user->password);
        if ($checkPassword) {
            $result->setEmail($user->email);
            $result->setFullName($user->name);
            $result->setId($user->id);

            Auth::login($user);
        }

        return $result;
    }

    public function Register(RegisterViewModel $model){

        if($model->getPassword() != $model->getConfirmPassword()){
            return [
                "result" => false,
                "message" => "رمز عبور و تکرار آن یکسان نمی باشد"
            ];
        }
        $isExistsUser = User::where("email", $model->getEmail())->exists();
        if($isExistsUser){
            return [
                "result" => false,
                "message" => "ایمیل وارد شده تکراری می باشد"
            ];
        }


        $user = new User();
        $user->name = $model->getName();
        $user->first_name = $model->getFirstName();
        $user->last_name = $model->getLastName();
        $user->email = $model->getEmail();
        $user->email_verified_at = date("Y-m-d H:i:s");
        $user->password = Hash::make($model->getPassword());
        $user->is_active = 1;
        $user->accept_terms = $model->getAcceptTerms();
        $user->save();
        return [
            "result" => true,
            "message" => "با موفقیت انجام شد"
        ];

    }

    public function GetUserInfoByEmail($email)
    {
        $user = User::where("email", $email)->where("is_active", 1)->first();
        return $user;
    }

    public function GetMastersOrGrandMasterOfCategory($categoryId)
    {
        return Level::whereRaw("(sort_order=5 or sort_order=6)")
            ->join("level_categories", "level_categories.level_id", "=", "levels.id")
            ->join("user_level_categories", "user_level_categories.level_category_id", "=", "level_categories.id")
            ->join("users", "users.id", "=", "user_level_categories.user_id")
            ->where("level_categories.category_id", $categoryId)
            ->where("user_level_categories.is_active", 1)
            ->select([
                "users.name as user_name",
                "users.email as user_email",
                "users.id as user_id",
                "level_categories.id as level_category_id",
                "user_level_categories.id as user_level_category_id",
                "levels.title as level_title"
            ])->get()->toArray();
    }
}
