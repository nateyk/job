@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">@lang('app.add') @lang('menu.evaluations')</h4>

                    <form id="createGroup" class="ajax-form" method="POST" action="{{ route('admin.evaluation-groups.store') }}">
                        @csrf

                        <div class="form-group">
                            <label class="required">Name</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label>Description</label>
                            <textarea name="description" class="form-control" rows="3"></textarea>
                        </div>

                        <div class="form-group">
                            <label>Status</label>
                            <div>
                                <label class="mr-2">
                                    <input type="checkbox" name="active" checked> Active
                                </label>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">@lang('app.save')</button>
                        <a href="{{ route('admin.evaluation-groups.index') }}" class="btn btn-default">@lang('app.cancel')</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

