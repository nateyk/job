@foreach($applications as $application)
        <div id="task-card{{$application->id}}" class="panel panel-default lobipanel show-detail task-card"
                data-widget="control-sidebar" data-slide="true"
                data-row-id="{{ $application->id }}"
                data-column-id="{{ $application->status->id }}"
                data-application-id="{{ $application->id }}"
                data-sortable="true"
                style="border-color: {{$application->status->color}};">
            <div class="panel-body">
                <h5>
                    <img src="{{$application->photo_url}}" alt="user" class="img-circle"
                            width="25">
                    {{ ucwords($application->full_name) }}</h5>
                <div class="stars stars-example-fontawesome">
                    <select id="example-fontawesome_{{$application->id}}"
                            data-value="{{ $application->rating }}"
                            data-id="{{ $application->id }}"
                            class="example-fontawesome bar-rating" name="rating"
                            autocomplete="off">
                        <option value=""></option>
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                    </select>
                </div>
                <h6 class="text-muted">{{ ucwords($application->job->title) }}</h6>
                <div class="pt-2 pb-2 mt-3">
                    <span class="text-dark font-14">
                        @if(!is_null($application->schedule)  && $application->status->id == 3)
                            {{ $application->schedule->schedule_date->format('d M, Y') }}
                        @else
                            {{ ($application->created_at->format('d M, Y')) }}
                        @endif
                    </span>
                    @if(in_array("add_schedule", $userPermissions))
                    <span id="buttonBox{{ $application->status->id }}{{$application->id}}"
                        data-timestamp="@if(!is_null($application->schedule)){{$application->schedule->schedule_date->timestamp}}@endif">

                        @if(!is_null($application->schedule) && $application->status->id == 3 &&
                        $currentDate < $application->schedule->schedule_date->timestamp)
                            <button onclick="sendReminder({{$application->id}}, 'reminder')"
                                type="button"
                                class="btn btn-sm btn-info notify-button">@lang('app.reminder')</button>
                        @endif
                        @if(in_array($application->status->status,  ['hired', 'rejected']))
                            <button onclick="sendReminder({{$application->id}}, 'notify', '{{ $application->status->status }}')" type="button" class="btn btn-sm btn-info notify-button">@lang('app.notify')</button>
                        @endif
                    </span>
                    @endif
                </div>
                
            </div>
            
        </div>
    @endforeach
