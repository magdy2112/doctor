<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\Qualification;
use App\Traits\HttpResponse;
use Illuminate\Http\Request;

class QualificationController extends Controller
{
    use HttpResponse;
    public function all_qualifications(){
        $allqualifications= Qualification::all();
        return $this->response(true,200,'ok',    $allqualifications);
    }

    public function doctor_qualifications(Request $requset){
      $requset->validate([
        'qualification_id' =>'required|exists:qualifications,id'
      ]);
      $doctor_qualifications= Doctor::where('qualification_id',$requset->input('qualification_id'))->with([])->get();
      if($doctor_qualifications->isEmpty()){
        return $this->response(false,404,'doctor Not found');
      }else{
        $doctor_qualifications->makeHidden(['qualification_id','specialization_id','city_id',]);

        return $this->response(true,200,'ok',    $doctor_qualifications);
      }
    }

}
