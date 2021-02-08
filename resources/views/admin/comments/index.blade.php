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
        Comments {{ trans('global.list') }}
        <a style="float: right;" class="btn btn-default" href="{{ url()->previous() }}">
                <i class="fa fa-arrow-left" aria-hidden="true"></i> {{ trans('global.back_to_list') }}
            </a>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-User">
                <thead>
                    <tr>
                        <th></th>
                        
                        <th>
                            {{ trans('cruds.user.fields.id') }}
                        </th>
                        <th>
                            Comment Type
                        </th>
                        <th>
                            Comment
                        </th>
                        
                        <th>
                            Created By User
                        </th>
                        
                        <th>
                            Date & Time
                        </th>
                    </tr>
                </thead>
                <tbody>
                   
                    @foreach($comments as $key => $comment)
                        <tr data-entry-id="{{ $comment->id }}">
                           <td></td>
                            <td>
                                {{ $no++ }}
                            </td>
                            <td>
                                {{ $comment->comment_type ?? '' }}
                            </td>
                             <td>
                                {{ $comment->comment ?? '' }}
                            </td>
                            <td>
                                {{ $comment->user->name ?? '' }}
                            </td>
                            
                            <td>
                                {{ date(('d-m-Y H:i:s'),strtotime($comment->created_at)) ?? '' }}
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
  let dtButtons = $.extend(true, [], [])

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
  $('.datatable-User:not(.ajaxTable)').DataTable({ })
    $('a[data-toggle="tab"]').on('shown.bs.tab', function(e){
        $($.fn.dataTable.tables(true)).DataTable()
            .columns.adjust();
    });
})

</script>

<script type="text/javascript">
    $('#button-filter').on('click', function() {
        url = '{{route('admin.leads.index')}}?s=1';

        var filter_name = $('input[name=\'filter_name\']').val();
        if (filter_name) {
            url += '&filter_name=' + encodeURIComponent(filter_name);
        }

        var filter_status = $('select[name=\'filter_status\']').val();
        if (filter_status) {
            url += '&filter_status=' + encodeURIComponent(filter_status);
        }
        
        var filter_website = $('input[name=\'filter_website\']').val();
        if (filter_website) {
            url += '&filter_website=' + encodeURIComponent(filter_website);
        }

        var filter_user = $('select[name=\'filter_user\']').val();
        if (filter_user) {
            url += '&filter_user=' + encodeURIComponent(filter_user);
        }


        location = url;
    });

    $('#reset-filter').on('click', function(event) {
        event.preventDefault();
        url = '{{route('admin.leads.index')}}';
        location = url;
    });
  </script>   

@endsection