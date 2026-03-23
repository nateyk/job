@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="card-title mb-0">@lang('menu.evaluations')</h4>
                        @if($user->cans('add_evaluations'))
                            <a href="{{ route('admin.evaluation-groups.create') }}" class="btn btn-sm btn-primary">
                                @lang('app.add') Group
                            </a>
                        @endif
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
                                        @if($user->cans('edit_evaluations'))
                                            <a href="{{ route('admin.evaluation-groups.edit', $group->id) }}"
                                               class="btn btn-sm btn-info">
                                                @lang('app.edit')
                                            </a>
                                        @endif
                                        @if($user->cans('delete_evaluations'))
                                            <form action="{{ route('admin.evaluation-groups.destroy', $group->id) }}"
                                                  method="POST"
                                                  style="display: inline-block;"
                                                  onsubmit="return confirm('Delete this evaluation group?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">
                                                    @lang('app.delete')
                                                </button>
                                            </form>
                                        @endif
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

