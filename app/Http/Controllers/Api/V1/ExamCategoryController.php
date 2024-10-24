<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\ExamCategory;
use Illuminate\Http\Request;
use F9Web\ApiResponseHelpers;
use App\Http\Controllers\Controller;

class ExamCategoryController extends Controller
{
    use ApiResponseHelpers;
    //
    public function __construct()
    {
        
    }

    public function index(Request $request)
    {
        $categories = ExamCategory::where('active', 1)->get();
        $data = [
            'success' => true,
            'message' => '',
            'data' => $categories
        ];
        return $this->respondWithSuccess($data);
    }
}
