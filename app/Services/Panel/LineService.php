<?php

namespace App\Services\Panel;

use App\Models\Line;

class LineService
{
    public function getAllLines(){
        $result = Line::all();
        return $result;
    }
}
