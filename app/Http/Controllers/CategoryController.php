<?php

namespace App\Http\Controllers;

use App\Services\Panel\CategoryService;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    private CategoryService $categoryService;

    public function __construct()
    {
        $this->categoryService = new CategoryService();
    }

    public function GetAllCategoriesByLineId($line_id)
    {
        return response()->json($this->categoryService->GetAllCategoriesByLineId($line_id));
    }
}
