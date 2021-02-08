@extends('layouts.admin')
@section('content')
<div class="card">
    <div class="card-header">
        All Lead {{ trans('cruds.user.title_singular') }}s {{ trans('global.list') }}
        <a style="float: right;" class="btn btn-default" href="{{ route('admin.lead-users.index') }}">
            <i class="fa fa-arrow-left" aria-hidden="true"></i> {{ trans('global.back_to_list') }}
        </a>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-User">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.user.fields.id') }}
                        </th>
                        <th>
                            {{ trans('cruds.user.fields.name') }}
                        </th>                        
                        {{-- <th>
                            {{ trans('cruds.user.fields.email') }}
                        </th> --}}
                        <th>
                            {{ trans('cruds.user.fields.mobile') }}
                        </th>
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
                        <th>
                            Date & Time
                        </th>
                        <th>
                           Action
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($lead_users as $key => $user)
                        <tr data-entry-id="{{ $user->id }}">
                            <td>

                            </td>
                            <td>
                                {{ $user->id }}
                            </td>
                            <td>
                                {{ $user->name ?? '' }}
                            </td>
                           {{--  <td>
                                {{ $user->email ?? '' }}
                            </td> --}}
                             <td>
                                {{ $user->mobile ?? '' }}
                            </td>
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
                            <td>
                                {{ date(('d-m-Y H:i:s'),strtotime($user->created_at)) ?? '' }}
                            </td>
                            <td>
                                <a class="btn btn-xs btn-primary" href="{{ route('admin.lead-users.show', $user->id) }}">
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
        </div>


    </div>
</div>
@endsection
@section('scripts')
@parent
<script>
    $(function () {
  let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.lead-users.mass_destroy') }}",
    className: 'btn-danger',
    action: function (e, dt, node, config) {
      var ids = $.map(dt.rows({ selected: true }).nodes(), function (entry) {
          return $(entry).data('entry-id')
      });

      if (ids.length === 0) {
        alert('{{ trans('global.datatables.zero_selected') }}')

        return
      }

      if (confirm('{{ trans('global.areYouSure') }}')) {
        $.ajax({
          headers: {'x-csrf-token': _token},
          method: 'POST',
          url: config.url,
          data: { ids: ids, _method: 'DELETE' }})
          .done(function () { location.reload() })
      }
    }
  }
  dtButtons.push(deleteButton)

  $.extend(true, $.fn.dataTable.defaults, {
    order: [[ 1, 'desc' ]],
    pageLength: 100,
  });
  $('.datatable-User:not(.ajaxTable)').DataTable({ buttons: dtButtons })
    $('a[data-toggle="tab"]').on('shown.bs.tab', function(e){
        $($.fn.dataTable.tables(true)).DataTable()
            .columns.adjust();
    });
})

</script>
@endsection