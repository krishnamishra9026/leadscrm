@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.user.title') }}
        <a style="float: right;" class="btn btn-default" href="{{ url()->previous() }}">
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
                        <td>
                            {{ $lead_user->meta }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.user.fields.lead_status') }}
                        </th>
                        <td>
                            <select id="toggle-class" data-id="{{ $lead_user->id }}" class="form-control col-md-2" >
                                <option @if ($lead_user->lead_status == 'Posted') selected @endif value="Posted">Posted</option>
                                <option @if ($lead_user->lead_status == 'Status1') selected @endif value="Status1">Status1</option>
                                <option @if ($lead_user->lead_status == 'Status2') selected @endif value="Status2">Status2</option>
                                <option @if ($lead_user->lead_status == 'Status3') selected @endif value="Status3">Status3</option>
                                <option @if ($lead_user->lead_status == 'Status4') selected @endif value="Status4">Status4</option>
                                <option @if ($lead_user->lead_status == 'Status5') selected @endif value="Status5">Status5</option>
                                <option @if ($lead_user->lead_status == 'Status6') selected @endif value="Status6">Status6</option>
                            </select>
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
