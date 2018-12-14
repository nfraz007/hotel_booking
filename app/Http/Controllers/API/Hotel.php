<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;


use Validator;
use Exception;
use Illuminate\Http\Request;

class Hotel extends Controller{
    public function __construct(){
        // $this->middleware("jwt.auth");
        DB::beginTransaction();
    }

    public function list(Request $request){
        try{
            // validating every thing
            $validator=Validator::make($request->all(), [
                'search' => "nullable|string",
                'sort_by' => "nullable|in:name,review_rating",
                'sort_type' => "nullable|in:asc,desc",
                'page' => "nullable|numeric",
            ]);
            if($validator->fails()){
                throw new Exception($validator->errors()->first());
            }

            $search = $request->input("search");
            $sort_by = $request->input("sort_by");
            $sort_type = $request->input("sort_type");
            $page = $request->input("page");

            $template_data = [];

            $db = DB::table("hotel");

            // filter code
            if($search) $db->where("name", "like", "%$search%")->orWhere("city", "like", "%$search%")->orWhere("country", "like", "%$search%");
            if($sort_by && $sort_type) $db->orderBy($sort_by, $sort_type);

            $hotel = $db->get();
            $template_data["hotel"] = $hotel;
            $template = view("template.hotel_list", $template_data)->render();
            DB::commit();
            return response()->json(array(
                "status" => true,
                "message" => "Successfully get the result",
                "redirect" => "",
                "data" => $hotel,
                "template" => $template
            ));
        }catch(Exception $e){
            DB::rollback();
            return response()->json(array(
                "status" => false,
                "message" => $e->getMessage()
            ));
        }
    }
}
