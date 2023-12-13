<?php

namespace App\Services\Panel;

use App\Models\UserLevelCategory;

class AllStudentService
{
    public function GetAllStudentWithMaster($parentId)
    {
        $students = UserLevelCategory::where("parent_id", $parentId)
            ->join("users", "users.id", "=", "user_level_categories.user_id")
            ->join("level_categories", "level_categories.id", "=", "user_level_categories.level_category_id")
            ->join("levels", "levels.id", "=", "level_categories.level_id")
            ->where("user_level_categories.is_active", 1)
            ->select([
                "user_level_categories.id as user_level_category_id",
                "user_level_categories.is_active as user_level_category_is_active",
                "levels.title as level_title",
                "user_level_categories.parent_id",
                "user_level_categories.user_id",
                "user_level_categories.level_category_id",
                "users.name as user_full_name",
                "users.email as user_email",
                "user_level_categories.created_at as user_level_category_create_date",
                "user_level_categories.expire_date as user_level_category_expire_date"
            ])
            ->orderBy("user_level_categories.id", "desc")
            ->get();

        return $students;
    }

    public function GetHistories($parentId, $levelCategoryId, $userId)
    {
        $students = UserLevelCategory::where("parent_id", $parentId)
            ->where("user_level_categories.level_category_id", $levelCategoryId)
            ->where("user_level_categories.user_id", $userId)
            ->join("users", "users.id", "=", "user_level_categories.user_id")
            ->join("level_categories", "level_categories.id", "=", "user_level_categories.level_category_id")
            ->join("levels", "levels.id", "=", "level_categories.level_id")
            ->select([
                "user_level_categories.id as user_level_category_id",
                "user_level_categories.is_active as user_level_category_is_active",
                "levels.title as level_title",
                "user_level_categories.parent_id",
                "user_level_categories.user_id",
                "user_level_categories.level_category_id",
                "users.name as user_full_name",
                "users.email as user_email",
                "user_level_categories.created_at as user_level_category_create_date",
                "user_level_categories.expire_date as user_level_category_expire_date"
            ])
            ->orderBy("user_level_categories.id", "desc")
            ->get();
        return $students;
    }
}
