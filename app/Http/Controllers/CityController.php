<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Doctor;
use App\Traits\HttpResponse;
use Illuminate\Http\Request;

class CityController extends Controller
{
    use HttpResponse;
    public function all_city(){
        $all_city= City::all();
        return $this->response(true,200,'ok',$all_city);
    }
    public function doctor_city(Request $request){
        $request->validate([
            'city_id' =>'required|exists:cities,id'
        ]);
        $city_id = $request->input('city_id');
        $all_doctor=Doctor::where('city_id',  $city_id)->get();
        if (  $all_doctor) {
            $all_doctor->makeHidden(['qualification_id','specialization_id','city_id',]);

            return $this->response(true,200,'ok',$all_doctor);
        }
    }


    public function version(){
        return ['version' => '1.0.0',
      'author' => 'doctors app',
     'year'=> date('Y')
    ];
    }
}
