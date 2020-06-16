<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Newform;
use App\evidence;
use App\report;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;

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
        -> where("status","Verified")
        -> first();
        if($inv){
            if (Hash::check($data->password, $inv->password)) {
                return response()->json("Investigator");
            }
            return response()->json("Password is wrong");
        }

        $ver = User::where("id",$data->id) 
        -> where("status","Verifier")
        -> first();
        if($ver){
            if (Hash::check($data->password, $ver->password)) {
                return response()->json("Verifier");
            }
            return response()->json("Password is wrong");
            //$user = $request->user();
            // $tokenResult = $user->createToken('Personal Access Token');
            // $token = $tokenResult->token;
            // $token->save();
            // return response()->json([
            //     "Verifier",
            //     'access_token' => $tokenResult->accessToken,
            //     'token_type' => 'Bearer',
            //     'expires_at' => Carbon::parse($tokenResult->token->expires_at)->toDateTimeString()
            //     ]);
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
        // $y -> password = $data -> password;
        $hash = Hash::make($data->password);
        $y -> password = $hash;
        $y -> email = $data -> email;
        $y -> status = "Unverified";
        $y -> save();
        return response()->json("Success");

    }

    public function forget(Request $data){
        $check = User::where("id",$data->id) 
        -> where("email",$data->email)
        -> first();
        if($check){
            return response()->json("Same");
        }
        else{
            return response()->json("Not");
        }
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
        $get = User::where("id",$data->id) -> first();
        $y = new NewForm;
        $y -> id = $data -> caseNo;
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
        $y -> invID = $get -> id;
        $y -> name = $get -> name;
        $y -> pdf= $data -> pdf;
        $y -> status="Unverified";
        $y -> save();
        return response()->json("Success");
    }

    public function evidence(Request $request, $caseNo){
        $x = Newform::where('caseNo',$request->caseNo) -> first();
        $testExt = [];
        $num = 0;

        foreach($request->file('image') as $image){
            $ext = array_push($testExt,$image->getClientOriginalExtension());
            $filename = "Image -".$num.".".$image->getClientOriginalExtension();
            //$image->move(public_path("/evidence"),"pi".$num.".".$image->getClientOriginalExtension());
            $image->move(public_path("/evidence/".$caseNo),$filename);
            $num += 1;
        }
        //return response()->json($testExt);
        return response()->json($request);
    }


    //satu data <satu row <satu set of data> = (first), many (get)

    // https://github.com/barryvdh/laravel-dompdf/issues/15
    public function Pdf(Request $request){
        $x = Newform::where('caseNo',$request->caseNo) -> first();
        $contents = file::files(public_path().'/evidence/'.$x->caseNo);
        $total = 0;
        foreach($contents as $y){
            $total += 1;
        }
        $z = $x->caseNo;
        $pdf = \PDF::loadView('report', compact('x','total','z'));
        //$x=file::findOrFail($request->caseNo);
        $pdf->save(public_path().'/tempReport/'.$x->caseNo." - ".$x->caseName.".pdf");
        $x -> pdf = '/tempReport/'.$x->caseNo." - ".$x->caseName.".pdf"; //save file path to DB
        $x -> save();
        $base64Pdf = base64_encode($pdf->stream());
        return $base64Pdf;

        // $x = Newform::where('caseNo',$request->caseNo) -> first();
        // $pdf = \PDF::loadView('report', compact('x'));
        // return $pdf->download($x->caseNo." - ".$x->caseName.".pdf"); //download terus 

        // return $pdf->stream('report.pdf'); //tengok(display) html

        // return $pdf->download($x->caseName.".pdf"); <pointing to 1 value>
        // <pointing to 2 values> $x->first.' '.$x->sec;

        // <cara lain>
        // $pdf = \App::make('dompdf.wrapper');
        // $pdf->loadHTML('<h1>Test</h1>');
        // return $pdf->stream();
    }

    public function VerifyingPdf(){
        $very = Newform::where("status","Unverified") -> get();
        return response() -> json($very);
    }

    public function ApprovePdf(Request $prove){
        $z = Newform::where("id",$prove->caseNo) -> first();
        $z -> status="Verified";
        $z -> save();
        return response() -> json("Success");
    }

    public function DenyPdf(Request $deny){
        $a = Newform::where("id",$deny->caseNo) -> first();
        $a -> delete();
        return response() -> json("Success");
    }

    public function VerifiedPdf(){
        $very = Newform::where("status","Verified") -> get();
        return response() -> json($very);
    }

    public function madePdf(Request $request){
        $very = Newform::where("invID",$request->id) 
        -> where("status","Verified")
        -> get();
        return response() -> json($very);
    }

    public function search(Request $request){
        if($request->caseNo){
            $get = Newform::where("id",$request->caseNo)
            -> where("status","Verified") -> get();
            return response() -> json($get);
        }
        else {
            $very = Newform::where("status","Verified") -> get();
            return response() -> json($very);
        }
    }

    public function Report(Request $request){
        if($request->caseNo){
            $get = Newform::where("id",$request->caseNo)
            -> where("invID",$request->id)
            -> where("status","Verified") 
            -> get();
            return response() -> json($get);
        }
        else {
            $very = Newform::where("invID",$request->id) 
            -> where("status","Verified")
            -> get();
            return response() -> json($very);
        }
    }
}