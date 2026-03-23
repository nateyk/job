@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Edit Evaluation Group</h4>

                    <form id="editGroup" class="ajax-form" method="POST" action="{{ route('admin.evaluation-groups.update', $group->id) }}">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label class="required">Name</label>
                            <input type="text" name="name" class="form-control" value="{{ $group->name }}" required>
                        </div>

                        <div class="form-group">
                            <label>Description</label>
                            <textarea name="description" class="form-control" rows="3">{{ $group->description }}</textarea>
                        </div>

                        <div class="form-group">
                            <label>Status</label>
                            <div>
                                <label class="mr-2">
                                    <input type="checkbox" name="active" @if($group->active) checked @endif> Active
                                </label>
                            </div>
                        </div>

                        @if($user->cans('edit_evaluations'))
                            <button type="submit" class="btn btn-primary">@lang('app.save')</button>
                        @endif
                        <a href="{{ route('admin.evaluation-groups.index') }}" class="btn btn-default">@lang('app.cancel')</a>
                    </form>

                    <hr>

                    <h5 class="mb-3">Criteria</h5>

                    @if($user->cans('add_evaluations'))
                    <form id="createCriterion" class="ajax-form mb-3">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <label class="required">Criterion</label>
                                <input type="text" name="name" class="form-control" required>
                            </div>
                            <div class="col-md-2">
                                <label class="required">Weight %</label>
                                <input type="number" name="weight" class="form-control text-right" min="0" max="100" required>
                            </div>
                            <div class="col-md-2">
                                <label>Order</label>
                                <input type="number" name="position" class="form-control text-right" min="0">
                            </div>
                            <div class="col-md-2">
                                <label>Max points</label>
                                <input type="number" name="max_score" class="form-control text-right" min="0" max="100" placeholder="= weight">
                                <small class="text-muted">≤ weight %; leave empty to use weight.</small>
                            </div>
                        </div>
                        <div class="mt-2">
                            <button type="button" id="add-criterion" class="btn btn-sm btn-outline-success">Add criterion</button>
                        </div>
                    </form>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" id="criteria-table">
                            <thead>
                            <tr>
                                <th>Criterion</th>
                                <th class="text-right">Weight %</th>
                                <th class="text-right">Order</th>
                                <th class="text-right">Max pts</th>
                                <th style="width: 220px;">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($group->criteria as $criterion)
                                <tr data-criterion-id="{{ $criterion->id }}">
                                    <td><input class="form-control form-control-sm" name="name" value="{{ $criterion->name }}"></td>
                                    <td><input class="form-control form-control-sm text-right" name="weight" type="number" min="0" max="100" value="{{ $criterion->weight }}"></td>
                                    <td><input class="form-control form-control-sm text-right" name="position" type="number" min="0" value="{{ $criterion->position }}"></td>
                                    <td><input class="form-control form-control-sm text-right" name="max_score" type="number" min="0" max="{{ $criterion->weight }}" value="{{ $criterion->max_score }}"></td>
                                    <td>
                                        @if($user->cans('edit_evaluations'))
                                            <a href="javascript:;" class="btn btn-sm btn-info save-criterion">@lang('app.save')</a>
                                        @endif
                                        @if($user->cans('delete_evaluations'))
                                            <a href="javascript:;" class="btn btn-sm btn-danger delete-criterion">@lang('app.delete')</a>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="text-center text-muted">No criteria yet.</td></tr>
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
        $('#add-criterion').click(function () {
            var url = "{{ route('admin.evaluation-groups.criteria.store', $group->id) }}";
            $.easyAjax({
                url: url,
                type: 'POST',
                container: '#createCriterion',
                data: $('#createCriterion').serialize(),
                success: function (response) {
                    if (response.status === 'success') {
                        window.location.reload();
                    }
                }
            });
        });

        $('#criteria-table').on('click', '.save-criterion', function () {
            var row = $(this).closest('tr');
            var criterionId = row.data('criterion-id');
            var url = "{{ route('admin.evaluation-groups.criteria.update', [$group->id, ':id']) }}";
            url = url.replace(':id', criterionId);

            var data = {
                _token: '{{ csrf_token() }}',
                _method: 'PUT',
                name: row.find('input[name="name"]').val(),
                weight: row.find('input[name="weight"]').val(),
                position: row.find('input[name="position"]').val(),
                max_score: row.find('input[name="max_score"]').val()
            };

            $.easyAjax({
                url: url,
                type: 'POST',
                container: '#criteria-table',
                data: data,
                success: function () {}
            });
        });

        $('#criteria-table').on('click', '.delete-criterion', function () {
            var row = $(this).closest('tr');
            var criterionId = row.data('criterion-id');
            var url = "{{ route('admin.evaluation-groups.criteria.destroy', [$group->id, ':id']) }}";
            url = url.replace(':id', criterionId);

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

                $.easyAjax({
                    url: url,
                    type: 'POST',
                    container: '#criteria-table',
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

