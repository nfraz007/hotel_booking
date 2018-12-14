<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

use Validator;
use Illuminate\Http\Request;

class Home extends Controller{
    public function __construct(){
        // $this->middleware("jwt.auth");
    }

    public function index(){
        
    }

    public function home(Request $request){
        $data = [];
        $view_data = $this->get_view_data();

        $data["content"] = view("home", $view_data)->render();
        $data["js"] = ["home.js"];

        return $this->view_main($data);
    }

    public function detail(Request $request, $slack = ""){
        $table = $this->check_slack($request, $slack, "hotel");

        $data = [];
        $view_data = $this->get_view_data();

        $view_data["data"] = $table;
        $data["content"] = view("detail", $view_data)->render();
        $data["js"] = ["home.js"];

        return $this->view_main($data);
    }
}
