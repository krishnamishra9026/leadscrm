<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LeadUser;
use Illuminate\Support\Facades\Validator;
use DB;

class LeadUserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        // echo '<pre>'; print_r($request->all()); echo '</pre>'; die();
        $validator = Validator::make($request->all(), [
            // 'name' => 'required|string|max:255',
            'mobile' => 'required|min:10',
        ]);

        if ($validator->fails())
        {
            return response(['errors'=>$validator->errors()->all()], 422);
        }

        $input = $request->all();
        $input['name'] = $request->name;
        $input['email'] = $request->email;
        $input['mobile'] = $request->mobile;
        $input['website'] = $request->website;
        $input['meta'] = $request->custom_data;
        if (isset($request->payment_status) && !empty($request->payment_status)) {
            $input['payment_status'] = $request->payment_status;
        }
        if (isset($request->lead_status) && !empty($request->lead_status)) {
            $input['lead_status'] = $request->lead_status;
        }
        $input['date'] = date('Y-m-d');
        $input['time'] = date("H:i:s");
        $lead_exists = LeadUser::where(['date'=>date('Y-m-d'), 'mobile' => $request->mobile])->exists();
        if ($lead_exists) {
           return $response = ['message' => "Lead already exists!"];
        }
        $user = LeadUser::create($input);
        return $response = ['message' => "Data inserted successfully!"];
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mobile' => 'required|min:10',
        ]);

        if ($validator->fails())
        {
            return response(['errors'=>$validator->errors()->all()], 422);
        }

        DB::table('lead_users')->where(['mobile'=>request('mobile'), 'date'=>date('Y-m-d')])->update(['payment_status' => $request->payment_status]);

        return response(['success'=> 'data updated successfully!'], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
