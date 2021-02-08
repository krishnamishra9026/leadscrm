<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LeadUser;
use Illuminate\Support\Facades\Gate;
use Auth;
use DB;
use Session;
use App\User;
use URL;

class LeadController extends Controller
{
      /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (! Gate::allows('manage-leads')) {
            return abort(401);
        }

        $leadUsers = DB::table('lead_users')
                ->select('*', DB::raw('count(*) as total'))
                ->groupBy('mobile')
                ->latest();
        $filter_status = $request->input('filter_status');
        $filter_name = $request->input('filter_name');
        $filter_website = $request->input('filter_website');
        $filter_user = $request->input('filter_user');

        if ($request->has('filter_status')) {
            $leadUsers->where('lead_users.lead_status','LIKE', '%'.$request->input('filter_status').'%');
        }        

        if ($request->has('filter_name')) {
            $leadUsers->where('lead_users.name','LIKE', '%'.$request->input('filter_name').'%');
        }

        if($request->has('filter_website')) {  
            $leadUsers->where('lead_users.website','LIKE', '%'.$request->input('filter_website').'%');
        }

        if($request->has('filter_user')) {
            $leadUsers->where('lead_users.user_id', $request->input('filter_user'));
        }
        $lead_users = $leadUsers->paginate(10);

        foreach ($lead_users as $key => $value) {
            $user_detail = LeadUser::where('id',$value->id)->with('user')->first();
            $lead_users[$key]->user = $user_detail['user'];
        }

        $users = User::whereNotIn('id',[1])->get();

        Session::forget('back_leads_url');
        Session::put('back_leads_url', URL::current());
        return view('admin.leads.index', compact('lead_users','users','filter_name','filter_status','filter_website','filter_user'))->with('no',0);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
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
    public function show($id)
    {
        $lead_user = LeadUser::find($id);
        if (! Gate::allows('manage-leads')) {
            return abort(401);
        }

        if (empty($lead_user->user_id)) {
            if (Auth::user()->id != 1) {
               $lead_user->user_id = Auth::user()->id;
               $lead_user->save();
           }           
       }      

       $lead_user = LeadUser::where('id',$lead_user->id)->with('user')->with('comments')->latest()->first(); 

        return view('admin.leads.show', compact('lead_user'));
       
    }

    public function view($id)
    {
        $lead_user = LeadUser::find($id);
        if (! Gate::allows('manage-leads')) {
            return abort(401);
        }    

       $lead_user = LeadUser::where('id',$lead_user->id)->with('user')->with('comments')->latest()->first(); 

        return view('admin.leads.view', compact('lead_user'));
       
    }

    public function showAll($id)
    {

        if (! Gate::allows('manage-leads')) {
            return abort(401);
        }

        $lead_user = LeadUser::where('id',$id)->first();
        $mobile = $lead_user->mobile;

        $lead_users = LeadUser::where('mobile',$mobile)->with('user')->latest()->get();  

        Session::forget('back_leads_url');
        Session::put('back_leads_url', URL::current());

        return view('admin.leads.show_all', compact('lead_users'))->with('no',1);
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
    public function destroy($id)
    {
        $lead_user = LeadUser::find($id);
        if (! Gate::allows('manage-leads')) {
            return abort(401);
        }

        $lead_user->delete();

        return redirect()->back()->with('message','Lead deleted successfully!');

        return redirect()->route('admin.leads.index');
    }

    /**
     * Delete all selected User at once.
     *
     * @param Request $request
     */
    public function massDestroy(Request $request)
    {
        if (! Gate::allows('manage-leads')) {
            return abort(401);
        }
        
        LeadUser::whereIn('id', request('ids'))->delete();

        return response()->noContent();
    }
}
