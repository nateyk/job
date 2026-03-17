@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="card-title mb-0">@lang('menu.evaluations')</h4>
                        <a href="{{ route('admin.evaluation-groups.create') }}" class="btn btn-sm btn-primary">
                            @lang('app.add') Group
                        </a>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>Name</th>
                                <th>Status</th>
                                <th style="width: 180px;">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($groups as $group)
                                <tr>
                                    <td>{{ $group->name }}</td>
                                    <td>
                                        @if($group->active)
                                            <span class="badge badge-success">Active</span>
                                        @else
                                            <span class="badge badge-secondary">Inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.evaluation-groups.edit', $group->id) }}"
                                           class="btn btn-sm btn-info">
                                            @lang('app.edit')
                                        </a>
                                        <a href="javascript:;" class="btn btn-sm btn-danger delete-group"
                                           data-group-id="{{ $group->id }}">
                                            @lang('app.delete')
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted">No evaluation groups yet.</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('footer-script')
    <script>
        $(document).on('click', '.delete-group', function (e) {
            e.preventDefault();
            var groupId = $(this).data('group-id');
            swal({
                title: "@lang('errors.areYouSure')",
                text: "@lang('errors.deleteWarning')",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "@lang('app.delete')",
                cancelButtonText: "@lang('app.cancel')",
                closeOnConfirm: true,
                closeOnCancel: true
            }, function (isConfirm) {
                if (!isConfirm) {
                    return;
                }

                var url = "{{ route('admin.evaluation-groups.destroy', ':id') }}";
                url = url.replace(':id', groupId);
                $.easyAjax({
                    url: url,
                    type: 'POST',
                    container: '.card-body',
                    data: {_token: '{{ csrf_token() }}', _method: 'DELETE'},
                    success: function (response) {
                        if (response.status === 'success') {
                            window.location.reload();
                        }
                    }
                });
            });
        });
    </script>
@endpush

