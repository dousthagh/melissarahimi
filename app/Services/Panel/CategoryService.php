<?php

namespace App\Services\Panel;

use App\Models\Category;

class CategoryService
{
    public function GetAllCategoriesByLineId($lineId){
        return Category::where("line_id", $lineId)->get();
    }
}
