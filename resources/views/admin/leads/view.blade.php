@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.user.title') }}
        <a style="float: right;" class="btn btn-default" href="{{ Session::get('back_leads_url') }}">
            <i class="fa fa-arrow-left" aria-hidden="true"></i> {{ trans('global.back_to_list') }}
        </a>
    </div>

    <div class="card-body" >
        <div class="alert alert-success fade-message" id="show-message" style="display: none;">
        </div>
        <div class="mb-2">
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.user.fields.id') }}
                        </th>
                        <td>
                            {{ $lead_user->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.user.fields.name') }}
                        </th>
                        <td>
                            {{ $lead_user->name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.user.fields.email') }}
                        </th>
                        <td>
                            {{ $lead_user->email }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.user.fields.mobile') }}
                        </th>
                        <td>
                            {{ $lead_user->mobile }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.user.fields.meta') }}
                        </th>
                        @php
                            $custom_datas = json_decode($lead_user->meta, true);
                            $i = 1;
                        @endphp
                        <td>                            
                            @if($custom_datas)
                            @foreach ($custom_datas as $key => $custom_data)
                                <span><b>{{ ucwords(str_replace('_', ' ', $key)) }}</b> : {{ $custom_data }}</span> @if($i < count($custom_datas)), @endif
                                @php
                                    $i++;
                                @endphp
                            @endforeach
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.user.fields.lead_status') }}
                        </th>
                        <td>
                            {{ $lead_user->lead_status }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.user.fields.payment_status') }}
                        </th>
                        <td>
                            {{ $lead_user->payment_status }}
                        </td>
                    </tr>

                    <tr>
                        <th>
                            Assign to User
                        </th>
                        <td>
                            {{ $lead_user->user->name ?? '' }}
                        </td>
                    </tr>
                    
                    <tr>
                        <th>
                           Comments
                        </th>
                        <td>
                           @if(count($lead_user->comments) > 0)  <a  class="btn btn-xs btn-primary" href="{{ route('admin.comments.show', $lead_user->id) }}"> {{ trans('global.view') }} Comments </a> @else No comments.. @endif
                        </td>
                    </tr>
                    <tr>
                        <th>
                           Created Date & Time
                        </th>
                        <td>
                            {{ date(('d-m-Y H:i:s'),strtotime($lead_user->created_at)) ?? '' }}
                        </td>
                    </tr>
                </tbody>
            </table>            
        </div>
    </div>
</div>
@endsection
@section('scripts')
@parent
<script>
    $(function() {
        $('#toggle-class').change(function() {
            var status = this.value; 
            var user_id = $(this).data('id'); 

            $.ajax({
                type: "GET",
                dataType: "json",
                url: '/admin/changeStatus',
                data: {'status': status, 'user_id': user_id},
                success: function(data){
                    $("#show-message").show().html('<center>'+ data.success +'</center>');
                    $(function(){
                        setTimeout(function() {
                            $('.fade-message').slideUp();
                        }, 5000);
                    });
                }
            });
        })
    })
</script>
@endsection
