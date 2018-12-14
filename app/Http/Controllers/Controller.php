<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

use Validator;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;

class Controller extends BaseController{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    
    public static $token = "";
    public static $user_id = "";
    public static $user = [];

    public function __construct(){

    }

    // this function will initiate the login/token user
    public function init_user($user_id = ""){
    	static::$user_id = $user_id;
    	$this->set_user();
    }

    // for login user, it will set the user data in user variable
    public function set_user(){
    	$user = DB::table("user")->where("id", static::$user_id)->first();

    	static::$user = $user;
    	static::$token = session()->get("token");
    }

    // data which is common in every sub view
    public function get_view_data(){
    	$data = [
            "token" => static::$token, 
        ];

    	return $data;
    }

    // the main view page, where i am passing all the require data
    public function view_main($data = []){
    	$data["user"]  = static::$user;
    	$data["token"] = static::$token;
    	return view("main", $data);
    }

    // it will check whether a slack is valid or not. we treat deleted data as deleted
    public function check_slack($request = "", $slack = "", $table = ""){
    	$api = ($request->segment(1) == "api") ? true : false;
    	$exist = DB::table($table)->select("*")->where("slack", $slack)->first();

    	if($api){
    		if(!$exist){
    			echo json_encode(array(
    				"status" => false,
    				"message" => "Invalid Slack."
    			));die;
    		}else{
    			return $exist;
    		}
    	}else{
	    	if(!$exist){
	    		echo view("error.invalid_slack");
		    	die;
	    	}else{
                return $exist;
            }
		}
    }

    public function status($type = "", $status = ""){
        $class = "default";

        switch($type){
            case "status":
                if($status == "ACTIVE") $class = "success";
                elseif($status == "INACTIVE") $class = "danger";
                elseif($status == "DELETED") $class = "danger";
                break;
        }

        return '<span class="label label-'.$class.'">'.$status.'</span>';
    }

   	public function toggle_status($status = ""){
   		if($status == "ACTIVE") return "INACTIVE";
   		elseif($status == "INACTIVE") return "ACTIVE";
   		else return "INACTIVE";
   	}

    // this will create a checkbox with the input data
   	public function select($data = [], $key = "", $value = [], $extra = [], $extra_data = []){
        $title = (isset($extra_data) && isset($extra_data["title"])) ? $extra_data["title"] : "Select option";
        $seperator = (isset($extra_data) && isset($extra_data["seperator"])) ? $extra_data["seperator"] : " - ";

   		$data_array = ["" => ["label" => $title, "extra" => ["", "", ""]]];

   		foreach($data as $single){
   			$single = $this->object_to_array($single);
   			if(array_key_exists($key, $single)){
                $label = array_map(function($v) use ($single){
                  return $single[$v];
                }, $value);

                $extra_label = [];
                if($extra){
                    $extra_label = array_map(function($v) use ($single){
                      return $single[$v];
                    }, $extra);
                }

   				$data_array[$single[$key]] = [
   					"label" => implode($seperator, $label),
   					"extra" => $extra_label
   				];
   			}
   		}
   		// echo json_encode($data_array);die;
   		return $data_array;
   	}

    public function autocomplete($data = [], $key = "", $value = [], $extra = ""){
        $data_array = [];

        foreach($data as $single){
            $single = $this->object_to_array($single);
            if(array_key_exists($key, $single)){
                $label = array_map(function($v) use ($single){
                  return $single[$v];
                }, $value);
                $data_array[] = [
                    "value" => $single[$key],
                    "label" => implode(" - ", $label),
                    "extra" => ($extra) ? $single[$extra] : ""
                ];
            }
        }
        // echo json_encode($data_array);die;
        return $data_array;
    }

    public function get_name_by_user_id($user_id = ""){
    	$user = DB::table("user")->select("full_name")->where("id", $user_id)->first();

    	if($user) return $user->full_name;
    	else return "NA";
    }

    // this will generate slack before inserting new row
    public function generate_slack($table = ""){
    	do{
    		$slack = str_random(20);

    		$exist = DB::table($table)->where("slack", $slack)->first();
    	}while($exist);
    	return $slack;
    }

    // generate code
    public function generate_code($key = "", $value = ""){
        $code = "";
        $date = strtoupper(date("yMd"));

        switch($key) {
            case "order":
                $code = implode("", ["#ODR", $date, $value]);
                break;
            
            default: break;
        }

        return $code;
    }

    // rules for every attr used in project, be carefull, because it is using in many place
    public function get_rule($type = "", $required = true){
    	$data = "";

    	switch($type){
    		case "name"               : $data = "nullable|regex:/^[a-zA-Z0-9 ]*$/|max:50"; break;
            case "username"           : $data = "nullable|regex:/^[a-zA-Z0-9 ,\.@]*$/|max:50"; break;
    		case "password"           : $data = "nullable|string|min:4|max:50"; break;
    		case "mobile"             : $data = "nullable|regex:/^[789][\d]{9}$/"; break;
    		case "email"              : $data = "nullable|email|max:50"; break;
    		case "website"            : $data = "nullable|url|max:500"; break;
            case "address"            : $data = "nullable|string|max:500"; break;
            case "state"              : $data = "nullable|string|max:50"; break;
    		case "city"               : $data = "nullable|string|max:50"; break;
    		case "pincode"            : $data = "nullable|regex:/^[0-9]{6}$/"; break;
    		case "company_name"       : $data = "nullable|regex:/^[a-zA-Z0-9 ,-\.]*$/|max:50"; break;
    		case "pan_number"         : $data = "nullable|regex:/^[a-zA-Z]{5}[0-9]{4}[a-zA-Z]{1}$/"; break;
    		case "gst_number"         : $data = "nullable|regex:/^\d{2}[a-zA-Z]{5}\d{4}[a-zA-Z]{1}\d[zZ]{1}[a-zA-Z\d]{1}$/"; break;
            case "bank_name"          : $data = "nullable|regex:/^[a-zA-Z0-9 ]*$/|max:50"; break;
            case "bank_branch_name"   : $data = "nullable|regex:/^[a-zA-Z0-9 ]*$/|max:50"; break;
            case "bank_account_number": $data = "nullable|string|max:50"; break;
            case "bank_ifsc_code"     : $data = "nullable|string|max:50"; break;
    		case "id"                 : $data = "nullable|numeric|min:1|max:9999999999"; break;
    		case "slack"              : $data = "nullable|alpha_num|max:50"; break;
            case "user_type"          : $data = $this->get_rule_constant("user_type"); break;
            case "honorofics"         : $data = $this->get_rule_constant("honorofics"); break;
            case "gender"             : $data = $this->get_rule_constant("gender"); break;
            case "status"             : $data = $this->get_rule_constant("status"); break;
            case "date_format"        : $data = $this->get_rule_constant("date_format"); break;
            case "time_format"        : $data = $this->get_rule_constant("time_format"); break;
    		case "currency_format"    : $data = $this->get_rule_constant("currency_format"); break;
            case "hsn_code"           : $data = "nullable|numeric|digits_between:0,8"; break;
            case "percent"            : $data = "nullable|numeric|min:0|max:1000"; break;
            case "text"               : $data = "nullable|string"; break;
            case "text_50"            : $data = "nullable|string|max:50"; break;
            case "text_100"           : $data = "nullable|string|max:100"; break;
            case "text_1000"          : $data = "nullable|string|max:1000"; break;
            case "product_name"       : $data = "nullable|regex:/^[a-zA-Z0-9 ,-\.\/]*$/|max:100"; break;
            case "sku"                : $data = "nullable|regex:/^[a-zA-Z0-9 ,-\.]*$/|max:50"; break;
            case "sell_price"         : $data = "nullable|numeric|min:0|max:999999999"; break;
            case "mrp"                : $data = "nullable|numeric|min:0|max:999999999"; break;
            case "quantity"           : $data = "nullable|numeric|min:1|max:999999999"; break;
            case "reward_point"       : $data = "nullable|numeric|min:0|max:999999999"; break;
            case "credit_limit"       : $data = "nullable|numeric|min:0|max:999999999"; break;
            case "credit_duration"    : $data = "nullable|numeric|min:0|max:999999999"; break;
            case "term_payment"       : $data = "nullable|numeric|min:0|max:999999999"; break;
            case "reason"             : $data = "nullable|string|min:1|max:500"; break;
            case "message"            : $data = "nullable|string|max:500"; break;
            case "comment"            : $data = "nullable|string|max:500"; break;
            case "title"              : $data = "nullable|string|max:100"; break;
            case "invoice_number"     : $data = "nullable|regex:/^[a-zA-Z0-9-#\/\-_\\\]*$/|max:50"; break;
            case "invoice_start"      : $data = "nullable|numeric|min:1|max:99999"; break;
            case "invoice_prefix"     : $data = "nullable|regex:/^[a-zA-Z0-9-#\/\-_\\\]*$/|max:5"; break;
            case "invoice_postfix"    : $data = "nullable|regex:/^[a-zA-Z0-9-#\/\-_\\\]*$/|max:5"; break;
            case "invoice_seperator"  : $data = "nullable|regex:/^[\/\-_\\\]*$/|max:1"; break;
            case "date"               : $data = "nullable|regex:/^[123][0-9]{3}-[01][0-9]-[0123][0-9]$/|max:10"; break;
            case "payment_type"       : $data = $this->get_rule_constant("payment_type"); break;
            case "amount"             : $data = "nullable|numeric|min:0|max:999999999"; break;
            case "file"               : $data = "nullable|file|max:3000";
    	}

    	if($required) return implode("|", ["required", $data]);
    	else return $data;
    }

    // it will add validation for list, we dont accept other data
    public function get_rule_constant($type = ""){
    	$constant = $this->get_constant();

    	if(array_key_exists($type, $constant)){
    		$rule = $constant[$type];
    		$rule = array_keys($rule);

    		return "in:".implode(",", $rule);
    	}else{
    		return "";
    	}
    }

    // constant for select, we only accept these data
    public function get_constant($constant = ""){
    	$data = [
    		"status" => [
    			"ACTIVE" => "Active",
    			"INACTIVE" => "Inactive"
    		],
    		"gender" => [
    			"MALE" => "Male",
    			"FEMALE" => "Female",
    			"OTHERS" => "Others"
    		],
    		"honorofics" => [
    			"Mr." => "Mr.",
    			"Miss." => "Miss.",
    			"Mrs." => "Mrs."
    		],
    	];

    	if($constant && array_key_exists($constant, $data)) return $data[$constant];
    	else return $data;
    }

    // in laravel get and first return in object, this will convert into array, it is helpfull if u r using array function for something like array_comlumn or something
    public function object_to_array($object = []){
    	$array = json_decode(json_encode($object), true);
    	return $array;
    }

    // jwt encode class, please dont mess with this
    public function jwt_encode($user_id = "") {
        $payload = [
            'iss' => "laravel_jwt_token", // Issuer of the token
            'sub' => $user_id, // Subject of the token
            'iat' => time(), // Time when JWT was issued.
            'exp' => time() + 60*60*24 // Expiration time in sec, 24 hours
        ];

        // As you can see we are passing `JWT_KEY` as the second parameter that will
        // be used to decode the token in the future.
        return JWT::encode($payload, env('JWT_KEY', ""));
    }

    // please dont mess with this also
    public function jwt_decode($token = ""){
        return JWT::decode($token, env('JWT_KEY', ""), ['HS256']);
    }
}
