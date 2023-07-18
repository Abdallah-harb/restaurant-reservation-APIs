<?php

namespace App\Http\Controllers\Api\Public;

use App\Http\Controllers\Controller;
use App\Http\Helper\ResponseHelper;
use App\Http\Resources\Data\MealsResource;
use App\Http\Resources\Data\TableResource;
use App\Models\Meal;
use App\Models\Table;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class DataController extends Controller
{
    public function allMeals(){
        $meals = Meal::get();
        if(count($meals) < 1 ){
            return ResponseHelper::sendResponseSuccess([],Response::HTTP_OK,'No meals added Yet .!');
        }
        return ResponseHelper::sendResponseSuccess( MealsResource::collection($meals));
    }

    public function allTables(){
        $tables = Table::get();
        if(count($tables) < 1 ){
            return ResponseHelper::sendResponseSuccess([],Response::HTTP_OK,'No tables added Yey .! ');
        }
        return ResponseHelper::sendResponseSuccess( TableResource::collection($tables));
    }
}
