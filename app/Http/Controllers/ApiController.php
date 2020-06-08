<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Newform;
use App\evidence;
use Illuminate\Support\Facades\File;

class ApiController extends Controller
{
    public function Login(Request $data){
        // if ($data->id=="Investigator"){
        //     return response()->json("Investigator");
        // }
        // if ($data->id=="Verifier"){
        //     return response()->json("Verifier");
        // }

        $inv = User::where("id",$data->id) 
        -> where("password",$data->password)
        -> where("status","Verified")
        -> first();
        if($inv){
            return response()->json("Investigator");
        }
        $ver = User::where("id",$data->id) 
        -> where("password",$data->password)
        -> where("status","Verifier")
        -> first();
        if($ver){
            return response()->json("Verifier");
        }

        // $loginDetails = $data -> only('id','password');
        // if (User::attempt($loginDetails)){
        //     if ($data->status=="Verified"){
        //         return response()->json("Investigator");
        //     }else if ($data->status=="Verifier"){
        //         return response()->json("Verifier");
        //     }
        //     else if ($data->status=="Unverified"){
        //         return response()->json(["message" => "Login Unsuccessful! User is unverified."]);
        //     }
        //     else{
        //         return response()->json(['message' => "Login Unsuccessful!"]);
        //     }
        // }else{
        //     return response()->json(['message' => "Wrong login details."]);
        // }
        //https://scotch.io/tutorials/react-native-app-with-authentication-and-user-management-in-15-minutes
    }

    public function SignUp(Request $data){
        // CHECK id ade ke x
        $check = User::where("id",$data->id) -> first();
        if(!empty($check)){ // Not empty mean database already exist this username
            return response()->json("Exist");
        }

        $y = new User;
        $y -> id = $data -> id;
        $y -> name = $data -> name;
        $y -> password = $data -> password;
        $y -> email = $data -> email;
        $y -> status = "Unverified";
        $y -> save();
        return response()->json("Success");

    }

    public function Verifying(){
        $very = User::where("status","Unverified") -> get();
        return response() -> json($very);
    }

    public function Test(){
        $x = User::all();
        dd($x);
    }

    public function Approve(Request $prove){
        $z = User::where("id",$prove->id) -> first();
        $z -> status="Verified";
        $z -> save();
        return response() -> json("Success");
    }

    public function Deny(Request $deny){
        $a = User::where("id",$deny->id) -> first();
        $a -> delete();
        return response() -> json("Success");
    }

    public function Newform(Request $data){
        $y = new NewForm;
        $y -> caseNo = $data -> caseNo;
        $y -> caseName = $data -> caseName;
        $y -> caseDetail = $data -> caseDetail;
        $y -> date = $data -> date;
        $y -> time = $data -> time;
        $y -> latitude = $data -> latitude;
        $y -> longitude = $data -> longitude;
        $y -> address = $data -> address;
        $y -> scene = $data -> scene;
        $y -> weather = $data -> weather;
        $y -> victim = $data -> victim;
        $y -> involveA = $data -> involveA;
        $y -> involveB = $data -> involveB;
        $y -> involveC = $data -> involveC;
        $y -> involveD = $data -> involveD;
        $y -> save();
        return response()->json("Success");
    }

    public function evidence(Request $request){
        $testExt = [];
        $num = 0;

        foreach($request->file('image') as $image){
            $ext = array_push($testExt,$image->getClientOriginalExtension());
            $image->move(public_path("/evidence"),'test'.$num.".".$image->getClientOriginalExtension());
            $num += 1;
        }
        //return response()->json($testExt);
        return response()->json($num);
    }
    //satu data <satu row <satu set of data> = (first), many (get)

    // https://github.com/barryvdh/laravel-dompdf/issues/15
    public function Pdf(Request $request){
        $x = Newform::where('caseNo',$request->caseNo) -> first();
        $pdf = \PDF::loadView('report', compact('x'));
        return $pdf->download($x->caseNo." - ".$x->caseName.".pdf"); //download terus 

        // return $pdf->stream('report.pdf'); //tengok(display) html

        // return $pdf->download($x->caseName.".pdf"); <pointing to 1 value>
        // <pointing to 2 values> $x->first.' '.$x->sec;

        // <cara lain>
        // $pdf = \App::make('dompdf.wrapper');
        // $pdf->loadHTML('<h1>Test</h1>');
        // return $pdf->stream();
    }


}
