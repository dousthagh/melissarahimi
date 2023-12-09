<?php

use App\Models\Message;
use App\Models\User;
use Illuminate\Support\Facades\DB;

if(!function_exists("GetUserCategoriesWithLevel")){
    function GetUserCategoriesWithLevel(User $user){
        $userLevelCategories = $user->userLevelCategories()
            ->join("user_level_categories as parent_user_level_category", "user_level_categories.parent_id", "=", "parent_user_level_category.id")
            ->join("users as parent_user", "parent_user.id", "=", "parent_user_level_category.user_id")
            ->where("user_level_categories.is_active", 1)
            ->join("level_categories", "user_level_categories.level_category_id", "=", "level_categories.id")
            ->join("levels", "level_categories.level_id", "=", "levels.id")
            ->join("categories", "level_categories.category_id", "=", "categories.id")
            ->orderByDesc("levels.sort_order")
            ->groupBy("level_categories.level_id")
            ->select(
                "user_level_categories.id as user_level_category_id",
                "level_categories.level_id",
                "levels.title as level_title",
                "levels.sort_order as level_order",
                DB::raw("group_concat(level_categories.category_id) as category_id"),
                DB::raw("group_concat(categories.title) as category_title"),
                DB::raw("group_concat(parent_user.name) as parent_user_name"),
                DB::raw("group_concat(user_level_categories.id) as user_level_categories_ids"),
                DB::raw("group_concat(user_level_categories.parent_id) as user_level_categories_parent_ids"),
                DB::raw("group_concat(user_level_categories.expire_date) as user_level_categories_expire_date"),
                DB::raw("group_concat(user_level_categories.created_at) as user_level_categories_created_at"),
            )
            ->get();
        $result = array();
        foreach ($userLevelCategories as $key=>$value){
            $current = array();
            $current['user_level_category_id'] = $value->user_level_category_id;

            $current['level']['id'] = $value->level_id;
            $current['level']['title'] = $value->level_title;
            $current['level']['order'] = $value->level_order;


            $categoryIds = explode(",", $value->category_id);
            $categoryTitles = explode(",", $value->category_title);
            $parentUserNames = explode(",", $value->parent_user_name);
            $userLevelCategoryIds = explode(",", $value->user_level_categories_ids);
            $userLevelCategoriesParentIds = explode(",", $value->user_level_categories_parent_ids);
            $userLevelCategoriesExpireDate = explode(",", $value->user_level_categories_expire_date);
            $userLevelCategoriesCreatedDate = explode(",", $value->user_level_categories_created_at);

            $categories = array();
            for ($i = 0; $i<count($categoryIds); $i++){
                $categories[$i]['id'] = $categoryIds[$i];
                $categories[$i]['title'] = $categoryTitles[$i];
                $categories[$i]['parent_user_name'] = $parentUserNames[$i];
                $categories[$i]['user_level_category_id'] = $userLevelCategoryIds[$i];
                $categories[$i]['parent_user_level_category_id'] = $userLevelCategoriesParentIds[$i];
                $categories[$i]['expire_date'] = $userLevelCategoriesExpireDate[$i];
                $categories[$i]['created_date'] = $userLevelCategoriesCreatedDate[$i];
            }
            $current['categories'] = $categories;

            $result[] = $current;
        }
        return $result;

    }
}


if(!function_exists("GetUserUnreadMessages")){
    function GetUserUnreadMessages(){
        return Message::where("receiver_user_id", auth()->id())
            ->where("is_read", 0)
            ->select("title", "content", "id")
            ->orderByDesc("id")
            ->get();
    }
}
