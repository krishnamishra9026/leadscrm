<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LeadUser;
use Illuminate\Support\Facades\Gate;
use Auth;
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

        $lead_users = DB::table('lead_users')
                 ->select('*', DB::raw('count(*) as total'))
                 ->groupBy('mobile')
                 ->latest()->get();

        // $lead_users = LeadUser::with('user')->latest()->get();
        return view('admin.lead_users.index', compact('lead_users'))->with('no',1);
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(LeadUser $lead_user)
    {
         if (! Gate::allows('users_manage')) {
            return abort(401);
        }

        if (empty($lead_user->user_id)) {
            if (Auth::user()->id != 1) {
               $lead_user->user_id = Auth::user()->id;
               $lead_user->save();
           }           
       }      

       $lead_user = LeadUser::where('id',$lead_user->id)->with('user')->latest()->first(); 

        return view('admin.lead_users.show', compact('lead_user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

     public function changeLeadStatus(Request $request)
    {
        $user = LeadUser::find($request->user_id);
        $user->lead_status = $request->status;
        $user->save();
  
        return response()->json(['success'=>'Lead status changed successfully.']);
    }

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
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(LeadUser $lead_user)
    {
        if (! Gate::allows('users_manage')) {
            return abort(401);
        }

        $lead_user->delete();

        return redirect()->route('admin.lead-users.index');
    }

    /**
     * Delete all selected User at once.
     *
     * @param Request $request
     */
    public function massDestroy(Request $request)
    {
        if (! Gate::allows('users_manage')) {
            return abort(401);
        }
        LeadUser::whereIn('id', request('ids'))->delete();

        return response()->noContent();
    }
}
