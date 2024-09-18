<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\Specialization;
use App\Traits\HttpResponse;
use Illuminate\Http\Request;

class SpecializationController extends Controller
{
    use HttpResponse;
    public function all_specializations(){
        $all_city= Specialization::all();
        return $this->response(true,200,'ok',$all_city);
    }
    public function doctor_specializations(Request $request){
        $request->validate([
            'specializations_id' =>'required|exists:specializations,id'
        ]);
        $specialization_id = $request->input('city_id');
        $all_doctor=Doctor::where('specialization_id',  $specialization_id)->get();
        if (  $all_doctor) {
            $all_doctor->makeHidden(['qualification_id','specialization_id','city_id',]);

            return $this->response(true,200,'ok',$all_doctor);
        }
    }
}
