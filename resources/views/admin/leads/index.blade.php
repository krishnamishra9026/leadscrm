@extends('layouts.admin')
@section('content')
<style type="text/css">
    .not-active {
  pointer-events: none;
  cursor: default;
  text-decoration: none;
  color: black;
}
</style>
<div class="card">
    <div class="card-header">
        Lead {{ trans('cruds.user.title_singular') }}s {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-User">
                <thead>
                    <tr>
                        
                        <th>
                            {{ trans('cruds.user.fields.id') }}
                        </th>
                        <th>
                            {{ trans('cruds.user.fields.name') }}
                        </th>                        
                        {{-- <th>
                            {{ trans('cruds.user.fields.email') }}
                        </th> --}}
                       {{--  <th>
                            {{ trans('cruds.user.fields.mobile') }}
                        </th> --}}
                        <th>
                            {{ trans('cruds.user.fields.lead_status') }}
                        </th>
                        <th>
                            {{ trans('cruds.user.fields.payment_status') }}
                        </th>
                        <th>
                            Website
                        </th>
                        <th>
                            Assign to User
                        </th>
                        {{-- <th>
                            Date & Time
                        </th> --}}
                        <th>
                            Time
                        </th>
                        <th>
                           Action
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $page_count = ($lead_users->currentPage()-1)* $lead_users->perPage()+($lead_users->total() ? 1:0);
                        $nos = $page_count +($no);
                    @endphp
                    @foreach($lead_users as $key => $user)
                        <tr data-entry-id="{{ $user->id }}">
                           
                            <td>
                                {{ $nos++ }}
                            </td>
                            <td>
                                {{ $user->name ?? '' }}
                            </td>
                           {{--  <td>
                                {{ $user->email ?? '' }}
                            </td> --}}
                            {{-- <td>
                                {{ $user->mobile ?? '' }}
                            </td> --}}
                             <td>
                                {{ $user->lead_status ?? '' }}
                            </td>
                             <td>
                                {{ ucfirst($user->payment_status) ?? '' }}
                            </td>
                            <td>
                                {{ $user->website ?? '' }}
                            </td>
                            <td>
                                {{ $user->user->name ?? '' }}
                            </td>
                            {{-- <td>
                                {{ date(('d-m-Y H:i:s'),strtotime($user->created_at)) ?? '' }}
                            </td> --}}
                            <td>
                                {{ \Carbon\Carbon::parse($user->created_at)->diffForHumans() }}
                            </td>
                            <td>
                                @php
                                    $dbtimestamp = strtotime($user->time);
                                    if (time() - $dbtimestamp > 15 * 60) {
                                        $button_enable = true;
                                    }else{
                                        $button_enable = false;
                                    }
                                @endphp
                                <a  class="btn btn-xs btn-primary @if (!$button_enable) not-active @endif" href="{{ route('admin.lead-users.show', $user->id) }}">
                                    {{ trans('global.view') }}
                                </a>  
                                @if($user->total > 1)   
                                <a class="btn btn-xs btn-primary" href="{{ route('admin.lead-users.show-all', $user->id) }}">
                                    {{ trans('global.view') }} All
                                </a>  
                                @endif                      

                                <form action="{{ route('admin.lead-users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <input type="submit" class="btn btn-xs btn-danger" value="{{ trans('global.delete') }}">
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div>
                <div style="float: left;" class="d-flex justify-content-center">
                    Showing {{($lead_users->currentPage()-1)* $lead_users->perPage()+($lead_users->total() ? 1:0)}} to {{($lead_users->currentPage()-1)*$lead_users->perPage()+count($lead_users)}}  of  {{$lead_users->total()}}  Records
                </div>
                <div style="float: right;" class="d-flex justify-content-center">
                    {!! $lead_users->links() !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
@parent
@endsection