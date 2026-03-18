<link rel="stylesheet" href="{{ asset('assets/plugins/jquery-bar-rating-master/dist/themes/fontawesome-stars.css') }}">
<style>

    .right-panel-box {
        overflow-x: scroll;
        max-height: 34rem;
    }

    .resume-button {
        text-align: center;
        margin-top: 1rem
    }


</style>
<div class="rpanel-title"> @lang('menu.jobApplications') <span><i class="ti-close right-side-toggle"></i></span></div>
<div class="r-panel-body p-3">

    <div class="row font-12">
        <div class="col-4">
            <img src="{{ $application->photo_url }}" class="img-circle img-fluid">

            {{--<div class="col-sm-6">--}}
            <p class="text-muted resume-button mr-6" id="resume-{{ $application->id }}">
                @if ($application->resume_url)
                    <a target="_blank" href="{{ $application->resume_url }}"
                    class="btn btn-sm btn-primary">         
                        @lang('app.view') @lang('modules.jobApplication.resume')
                    </a>
                @endif
            </p>
            {{--</div>--}}
            @if($user->cans('edit_job_applications'))
                <div class="stars stars-example-fontawesome text-center">
                    <select id="example-fontawesome" name="rating" autocomplete="off">
                        <option value=""></option>
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                    </select>
                </div>
                @if(isset($applicationEvaluations) && $applicationEvaluations->count() > 0)
                    @php
                        $latestEval = $applicationEvaluations->sortByDesc('updated_at')->first();
                    @endphp
                    @if($latestEval && $latestEval->total_score !== null)
                        <div class="text-center mt-2">
                            <span class="badge badge-primary">
                                Eval: {{ $latestEval->total_score }}/100
                            </span>
                        </div>
                    @endif
                @endif
            @endif
            @if($application->status->status == 'hired' && is_null($application->onboard))
                <p class="text-muted resume-button">
                    <a href="{{ route('admin.job-onboard.create') }}?id={{$application->id}}"
                       class="btn btn-sm btn-success">@lang('app.startOnboard')</a>
                </p>
           @endif
           @if ($user->cans('delete_job_applications'))
                <div class="text-muted resume-button">
                    <a href="javascript:archiveApplication({{ $application->id }})" class="btn btn-sm btn-info">
                        @lang('modules.jobApplication.archiveApplication')
                    </a>
                </div>
                <div class="text-muted resume-button">
                    <a href="javascript:deleteApplication({{ $application->id }})" class="btn btn-sm btn-danger">
                        @lang('modules.jobApplication.deleteApplication')
                    </a>
                </div>
           @endif
        </div>

        <div class="col-8 right-panel-box">
            <div class="col-sm-12">
                <strong>@lang('app.name')</strong><br>
                <p class="text-muted">{{ ucwords($application->full_name) }}</p>
            </div>

            <div class="col-sm-12">
                <strong>@lang('modules.jobApplication.appliedFor')</strong><br>
                <p class="text-muted">{{ ucwords($application->job->title).' ('.ucwords($application->job->location->location).')' }}</p>
            </div>

            <div class="col-sm-12">
                <strong>@lang('app.email')</strong><br>
                <p class="text-muted">{{ $application->email }}</p>
            </div>

            <div class="col-sm-12">
                <strong>@lang('app.phone')</strong><br>
                <p class="text-muted">{{ $application->phone }}</p>
            </div>

            <div class="col-sm-12">
                <div class="row">
                    @if (!is_null($application->gender))
                        <div class="col-sm-12 col-md-4">
                            <strong>@lang('app.gender')</strong><br>
                            <p class="text-muted" id="gender-{{ $application->id }}">{{ ucfirst($application->gender) }}</p>
                        </div>
                    @endif
                    @if (!is_null($application->dob))
                        <div class="col-sm-12 col-md-4">
                            <strong>@lang('app.dob')</strong><br>
                            <p class="text-muted" id="dob-{{ $application->id }}">{{ $application->dob->format('jS F, Y') }}</p>
                        </div>
                    @endif
                </div>
            </div>

            @if (!is_null($application->address))
                        <div class="col-sm-12 col-md-4">
                            <strong>@lang('app.address')</strong><br>
                            <p class="text-muted" id="address-{{ $application->id }}">{{ $application->address}}</p>
                        </div>
                    @endif

            @if (!is_null($application->country))
                <div class="col-sm-12">
                    <div class="row">
                        <div class="col">
                            <strong>@lang('app.country')</strong><br>
                            <p class="text-muted" id="country-{{ $application->id }}">{{ $application->country }}</p>
                        </div>
                        <div class="col">
                            <strong>@lang('app.state')</strong><br>
                            <p class="text-muted" id="state-{{ $application->id }}">{{ $application->state }}</p>
                        </div>
                        <div class="col">
                            <strong>@lang('app.city')</strong><br>
                            <p class="text-muted" id="city-{{ $application->id }}">{{ $application->city }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <div class="col-sm-12">
                <strong>@lang('modules.jobApplication.appliedAt')</strong><br>
                <p class="text-muted">{{ $application->created_at->timezone($global->timezone)->format('d M, Y H:i') }}</p>
            </div>
            @if (!is_null($application->cover_letter))
            <div class="col-sm-12">
                <strong>@lang('modules.jobs.coverLetter')</strong><br>
                <p class="text-muted">{{ $application->cover_letter }}</p>
            </div>
            @endif
            <div class="col-sm-12">
                <h4>@lang('modules.front.additionalDetails')</h4>
                @forelse($answers as $answer)
                    <strong>
                        {{$answer->question->question}}
                    </strong><br>
                    @if($answer->question->type == 'text')
                    <p class="text-muted">{{ ucfirst($answer->answer)}}</p>
                    @else
                    @if(!is_null($answer->file))
                    <a target="_blank" href="{{ $answer->file_url }}" class="btn btn-sm btn-primary mb-2">@lang('app.view') @lang('app.file')</a><br>
                    @endif
                    @endif
                @empty
                @endforelse
            </div>

            {{-- Evaluations UI moved under Skills (see below) --}}
            @if(!is_null($application->schedule))
                <hr>

                <h5>@lang('modules.interviewSchedule.scheduleDetail')</h5>
                <div class="col-sm-6">
                    <strong>@lang('modules.interviewSchedule.scheduleDate')</strong><br>
                    <p class="text-muted">{{ $application->schedule->schedule_date->format('d M, Y H:i') }}</p>
                </div>
                @if($zoom_setting->enable_zoom == 1)
                <div class="col-sm-6">
                    <strong>@lang('modules.interviewSchedule.interviewType')</strong><br>
                    <p class="text-muted">{{ $application->schedule->interview_type == 'online' ?'Online' :'offline' }}</p>
                </div>
                @endif
                <div class="row">
                    <div class="col-sm-6">
                        <strong>@lang('modules.interviewSchedule.assignedEmployee')</strong><br>
                    </div>
                    <div class="col-sm-6">
                        <strong>@lang('modules.interviewSchedule.employeeResponse')</strong><br>
                    </div>
                    @forelse($application->schedule->employee as $key => $emp )
                        <div class="col-sm-6">
                            <p class="text-muted">{{ ucwords($emp->user->name) }}</p>
                        </div>

                        <div class="col-sm-6">
                            @if($emp->user_accept_status == 'accept')
                                <label class="badge badge-success">{{ ucwords($emp->user_accept_status) }}</label>
                            @elseif($emp->user_accept_status == 'refuse')
                                <label class="badge badge-danger">{{ ucwords($emp->user_accept_status) }}</label>
                            @else
                                <label class="badge badge-warning">{{ ucwords($emp->user_accept_status) }}</label>
                            @endif
                        </div>
                    @empty
                    @endforelse
                </div>

            @endif

            @if(isset($application->schedule->comments) == 'interview' && count($application->schedule->comments) > 0)
                <hr>

                <h5>@lang('modules.interviewSchedule.comments')</h5>
                @forelse($application->schedule->comments as $key => $comment )

                    <div class="col-sm-12">
                        <p class="text-muted"><b>{{$comment->user->name }}:</b> {{ $comment->comment }}</p>
                    </div>
                @empty
                @endforelse

            @endif
            <div class="col-sm-12">
                <p class="text-muted">
                    @if(!is_null($application->skype_id))
                        <span class="skype-button rounded" data-contact-id="live:{{$application->skype_id}}"
                              data-text="Call"></span>
                    @endif
                </p>
            </div>
            <div class="row">
                @if($user->cans('add_schedule') && $application->status->status == 'interview' && is_null($application->schedule))
                    <div class="col-sm-6">
                        <p class="text-muted">
                            <a onclick="createSchedule('{{$application->id}}')" href="javascript:;"
                               class="btn btn-sm btn-info">@lang('modules.interviewSchedule.scheduleInterview')</a>
                        </p>
                    </div>
                @endif
            </div>
        </div>
        @if ($user->cans('edit_job_applications'))
            <div class="col-12" id="skills-container">
                <hr>
                <div class="col-sm-12 mb-3">
                    <h5>@lang('modules.jobApplication.skills')</h5>
                </div>
                <div class="form-group mb-2">
                    <select name="skills[]" id="skills" class="form-control select2 custom-select" multiple>
                        @forelse ($skills as $skill)
                            <option @if (!is_null($application->skills) && in_array($skill->id, $application->skills)) selected @endif value="{{ $skill->id }}">{{ $skill->name }}</option>
                        @empty
                        @endforelse
                    </select>
                </div>
                <a href="javascript:addSkills({{ $application->id}});" id="add-skills" class="btn btn-sm btn-outline-success">
                    @if (!is_null($application->skills) && sizeof($application->skills) > 0)
                        @lang('modules.jobApplication.updateSkills')
                    @else
                        @lang('modules.jobApplication.addSkills')
                    @endif
                </a>
            </div>

            @if (isset($evaluationGroups) && $evaluationGroups->count() > 0)
                <div class="col-12" id="evaluations-container">
                    <hr>
                    <div class="col-sm-12 mb-3">
                        <h5>@lang('menu.evaluations')</h5>
                    </div>

                    <div class="form-group mb-2">
                        <label for="evaluation_group_id">Evaluation group</label>
                        <select id="evaluation_group_id" class="form-control">
                            <option value="">Select evaluation group</option>
                            @foreach($evaluationGroups as $group)
                                @php
                                    $eval = $applicationEvaluations[$group->id] ?? null;
                                @endphp
                                <option value="{{ $group->id }}">
                                    {{ $group->name }}
                                    @if($eval && $eval->total_score !== null)
                                        ({{ $eval->total_score }}/100)
                                    @endif
                                </option>
                            @endforeach
                        </select>
                        <small class="text-muted">Select a group to start scoring. Nothing is selected by default.</small>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div class="text-muted">Total</div>
                        <div class="font-weight-bold" id="evaluation-total">-- / 100</div>
                    </div>

                    <div id="evaluation-criteria-container"></div>

                    <div class="form-group">
                        <label for="overall_comment">Overall comment</label>
                        <textarea id="overall_comment" class="form-control" rows="2"></textarea>
                    </div>

                    <button type="button"
                            id="save-evaluation"
                            class="btn btn-sm btn-outline-primary">
                        Save evaluation
                    </button>

                    <script>
                        window.evaluationData = {
                            groups: @json($evaluationGroups),
                            evaluations: @json($applicationEvaluations),
                            saveUrl: "{{ route('admin.job-applications.saveEvaluation', $application->id) }}",
                            csrfToken: "{{ csrf_token() }}"
                        };
                    </script>
                </div>
            @endif
        @endif
        <div class="col-12">
            <hr>
            <div class="col-sm-12 mb-3">
                <h5>@lang('modules.jobApplication.applicantNotes')</h5>
            </div>

            <div id="applicant-notes" class="col-sm-12">
                <ul class="list-unstyled">
                    @foreach($application->notes as $key => $notes )
                        <li class="media mb-3" id="note-{{ $notes->id }}">
                            <div class="media-body">
                                <h6 class="mt-0 mb-1">{{ ucwords($notes->user->name) }}
                                    <span class="pull-right font-italic font-weight-light"><small> {{ $notes->created_at->diffForHumans() }} </small>
                                        @if($user->cans('edit_job_applications'))
                                            <a href="javascript:;" class="edit-note" data-note-id="{{ $notes->id }}"><i class="fa fa-edit ml-2"  title="Edit"></i></a>
                                            <a href="javascript:;" class="delete-note" data-note-id="{{ $notes->id }}" title="Delete"><i class="fa fa-trash ml-1 text-danger"></i></a>
                                        @endif
                                </span>
                                </h6>
                                <small class="note-text">{{ ucfirst($notes->note_text) }}</small>
                                <div class="note-textarea"></div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>

            @if($user->cans('edit_job_applications'))
                <div class="col-sm-12">
                    <div class="form-group mb-2">
                        <textarea name="note" id="note_text" cols="30" rows="2" class="form-control"></textarea>
                    </div>
                    <a href="javascript:;" id="add-note" class="btn btn-sm btn-outline-primary">@lang('modules.jobApplication.addNote')</a>
                </div>
            @endif

        </div>


    </div>

</div>
@if($user->cans('edit_job_applications'))
    <script src="{{ asset('assets/plugins/jquery-bar-rating-master/dist/jquery.barrating.min.js') }}"
            type="text/javascript"></script>
    <script>
        $('#example-fontawesome').barrating({
            theme: 'fontawesome-stars',
            showSelectedRating: false,
            onSelect: function (value, text, event) {
                if (event !== undefined && value !== '') {
                    var url = "{{ route('admin.job-applications.rating-save',':id') }}";
                    url = url.replace(':id', {{$application->id}});
                    var token = '{{ csrf_token() }}';
                    var id = {{$application->id}};
                    $.easyAjax({
                        type: 'Post',
                        url: url,
                        container: '#example-fontawesome',
                        data: {'rating': value, '_token': token},
                        success: function (response) {
                            $('#example-fontawesome_' + id).barrating('set', value);
                        }
                    });
                }

            }
        });
        @if($application->rating !== null)
        $('#example-fontawesome').barrating('set', {{$application->rating}});
        @endif

    </script>
    <script>
        (function () {
            if (typeof window.evaluationData === 'undefined') {
                return;
            }

            var groups      = window.evaluationData.groups || [];
            var evaluations = window.evaluationData.evaluations || {};
            var saveUrl     = window.evaluationData.saveUrl;
            var csrfToken   = window.evaluationData.csrfToken;

            var groupSelect        = document.getElementById('evaluation_group_id');
            var criteriaContainer  = document.getElementById('evaluation-criteria-container');
            var overallCommentEl   = document.getElementById('overall_comment');
            var saveButton         = document.getElementById('save-evaluation');
            var totalEl            = document.getElementById('evaluation-total');

            function escapeHtml(value) {
                return String(value)
                    .replace(/&/g, '&amp;')
                    .replace(/</g, '&lt;')
                    .replace(/>/g, '&gt;')
                    .replace(/"/g, '&quot;')
                    .replace(/'/g, '&#039;');
            }

            function findGroup(id) {
                id = parseInt(id, 10);
                for (var i = 0; i < groups.length; i++) {
                    if (parseInt(groups[i].id, 10) === id) {
                        return groups[i];
                    }
                }
                return null;
            }

            function findEvaluation(groupId) {
                groupId = parseInt(groupId, 10);
                return evaluations[groupId] || null;
            }

            function renderCriteria() {
                if (!groupSelect || !criteriaContainer) {
                    return;
                }

                var groupId = groupSelect.value;
                if (!groupId) {
                    criteriaContainer.innerHTML = '';
                    if (overallCommentEl) {
                        overallCommentEl.value = '';
                    }
                    if (totalEl) {
                        totalEl.textContent = '-- / 100';
                    }
                    return;
                }
                var group   = findGroup(groupId);
                var existing = findEvaluation(groupId);

                if (!group) {
                    criteriaContainer.innerHTML = '';
                    if (overallCommentEl) {
                        overallCommentEl.value = '';
                    }
                    if (totalEl) {
                        totalEl.textContent = '-- / 100';
                    }
                    return;
                }

                var rows = [];
                (group.criteria || []).forEach(function (criterion) {
                    var existingScore = null;
                    if (existing && existing.scores) {
                        existing.scores.forEach(function (scoreRow) {
                            if (parseInt(scoreRow.evaluation_criterion_id, 10) === parseInt(criterion.id, 10)) {
                                existingScore = scoreRow.score;
                            }
                        });
                    }

                    var maxScore = parseInt(criterion.max_score, 10);
                    if (!maxScore || maxScore < 1) {
                        maxScore = 100;
                    }

                    rows.push(
                        '<tr>' +
                            '<td>' + escapeHtml(criterion.name) + '</td>' +
                            '<td class="text-right">' + criterion.weight + '%</td>' +
                            '<td class="text-right">' +
                                '<input type="number" class="form-control form-control-sm text-right" ' +
                                'name="criteria[' + criterion.id + ']" ' +
                                'value="' + (existingScore !== null ? existingScore : '') + '" ' +
                                'min="0" max="' + maxScore + '" />' +
                                '<div class="small text-muted text-right">0–' + maxScore + '</div>' +
                            '</td>' +
                        '</tr>'
                    );
                });

                var html = '';
                if (rows.length) {
                    html += '<table class="table table-sm mb-2">';
                    html += '<thead><tr><th>Criterion</th><th class="text-right">Weight %</th><th class="text-right">Score (0–100)</th></tr></thead>';
                    html += '<tbody>' + rows.join('') + '</tbody></table>';
                } else {
                    html = '<p class="text-muted">No criteria configured for this group.</p>';
                }

                criteriaContainer.innerHTML = html;

                if (overallCommentEl) {
                    overallCommentEl.value = existing && existing.overall_comment ? existing.overall_comment : '';
                }

                // Show saved total (until user edits inputs)
                if (totalEl) {
                    if (existing && existing.total_score !== null && typeof existing.total_score !== 'undefined') {
                        totalEl.textContent = existing.total_score + ' / 100';
                    } else {
                        totalEl.textContent = '-- / 100';
                    }
                }
            }

            if (groupSelect) {
                groupSelect.addEventListener('change', renderCriteria);
                renderCriteria();
            }

            if (saveButton) {
                saveButton.addEventListener('click', function () {
                    if (!groupSelect) {
                        return;
                    }
                    var groupId = groupSelect.value;
                    if (!groupId) {
                        return;
                    }

                    var data = {
                        _token: csrfToken,
                        evaluation_group_id: groupId,
                        criteria: {},
                        overall_comment: overallCommentEl ? overallCommentEl.value : ''
                    };

                    // Send all criteria IDs for this group so clearing a field clears it server-side too.
                    var group = findGroup(groupId);
                    (group && group.criteria ? group.criteria : []).forEach(function (criterion) {
                        var input = criteriaContainer.querySelector('input[name="criteria[' + criterion.id + ']"]');
                        var value = input ? (input.value || '').trim() : '';
                        data.criteria[String(criterion.id)] = value === '' ? null : parseInt(value, 10);
                    });

                    $.easyAjax({
                        url: saveUrl,
                        type: 'POST',
                        container: '#evaluation-criteria-container',
                        data: data,
                        success: function (response) {
                            if (response.status === 'success') {
                                window.location.reload();
                            }
                        }
                    });
                });
            }

            // Live weighted total while typing
            function updateLiveTotal() {
                if (!totalEl || !groupSelect || !criteriaContainer) {
                    return;
                }

                var groupId = groupSelect.value;
                if (!groupId) {
                    totalEl.textContent = '-- / 100';
                    return;
                }

                var group = findGroup(groupId);
                if (!group || !(group.criteria || []).length) {
                    totalEl.textContent = '-- / 100';
                    return;
                }

                var sumWeights = 0;
                var weightedSum = 0;

                (group.criteria || []).forEach(function (criterion) {
                    var weight = parseInt(criterion.weight, 10) || 0;
                    sumWeights += weight;

                    var input = criteriaContainer.querySelector('input[name="criteria[' + criterion.id + ']"]');
                    var score = 0;
                    if (input && (input.value || '').trim() !== '') {
                        score = parseInt(input.value, 10) || 0;
                    }

                    var maxScore = parseInt(criterion.max_score, 10);
                    if (!maxScore || maxScore < 1) {
                        maxScore = 100;
                    }
                    if (score < 0) score = 0;
                    if (score > maxScore) score = maxScore;

                    var percent = (maxScore > 0) ? ((score / maxScore) * 100) : 0;
                    weightedSum += percent * weight;
                });

                if (sumWeights <= 0) {
                    totalEl.textContent = '-- / 100';
                    return;
                }

                var total = Math.round(weightedSum / sumWeights);
                totalEl.textContent = total + ' / 100';
            }

            if (criteriaContainer) {
                criteriaContainer.addEventListener('input', function (e) {
                    if (e && e.target && e.target.tagName === 'INPUT') {
                        updateLiveTotal();
                    }
                });
            }
        })();
    </script>
@endif
<script>
    $('.select2#skills').select2();

    function addSkills(applicationId) {
        let url = "{{ route('admin.job-applications.addSkills', ':id') }}";
        url = url.replace(':id', applicationId);

        $.easyAjax({
            url: url,
            type: 'POST',
            container: '#skills-container',
            data: {
                _token: '{{ csrf_token() }}',
                skills: $('#skills').val()
            },
            success: function (response) {
                if (response.status === 'success') {
                    $("body").removeClass("control-sidebar-slide-open");
                    if (typeof table !== 'undefined') {
                        table._fnDraw();
                    }
                    else {
                        loadData();
                    }
                }
            }
        })

    }

    function deleteApplication(applicationId) {
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
        }, function(isConfirm){
            if (isConfirm) {

                var url = "{{ route('admin.job-applications.destroy', ':id') }}";
                url = url.replace(':id', applicationId);

                var token = '{{ csrf_token() }}';

                $.easyAjax({
                    type: 'POST',
                    url: url,
                    data: {'_token':token, '_method': 'DELETE'},
                    success: function (response) {
                        $("body").removeClass("control-sidebar-slide-open");
                        if (response.status === 'success') {
                            if (typeof table !== 'undefined') {
                                table._fnDraw();
                            }
                            else {
                                loadData();
                            }
                        }
                    }
                });
            }
        });
    }

    function archiveApplication(applicationId) {
        swal({
            title: "@lang('errors.areYouSure')",
            text: "@lang('errors.archiveWarning')",
            type: "info",
            showCancelButton: true,
            confirmButtonColor: "#28A745",
            confirmButtonText: "@lang('app.yes')",
            cancelButtonText: "@lang('app.no')",
            closeOnConfirm: true,
            closeOnCancel: true
        }, function(isConfirm){
            if (isConfirm) {

                var url = "{{ route('admin.job-applications.archiveJobApplication', ':id') }}";
                url = url.replace(':id', applicationId);

                var token = '{{ csrf_token() }}';

                $.easyAjax({
                    type: 'POST',
                    url: url,
                    data: {'_token':token},
                    success: function (response) {
                        $("body").removeClass("control-sidebar-slide-open");
                        if (response.status === 'success') {
                            if (typeof table !== 'undefined') {
                                table._fnDraw();
                            }
                            else {
                                loadData();
                            }
                        }
                    }
                });
            }
        });
    }

    $('#add-note').click(function () {
        var url = "{{ route('admin.applicant-note.store') }}";
        var id = {{$application->id}};
        var note = $('#note_text').val();
        var token = '{{ csrf_token() }}';

        $.easyAjax({
            type: 'POST',
            url: url,
            data: {'_token':token, 'id':id, 'note': note},
            success: function (response) {
                if(response.status == 'success') {
                    $('#applicant-notes').html(response.view);
                    $('#note_text').val('');
                }
            }
        });
    });

    $('body').on('click', '.edit-note', function() {
        $(this).hide();
        let noteId = $(this).data('note-id');
        $('body').find('#note-'+noteId+' .note-text').hide();

        let noteText = $('body').find('#note-'+noteId+' .note-text').html();
        let textArea = '<textarea id="edit-note-text-'+noteId+'" class="form-control" row="4">'+noteText+'</textarea><a class="update-note" data-note-id="'+noteId+'" href="javascript:;"><i class="fa fa-check"></i> @lang("app.save")</a>';
        $('body').find('#note-'+noteId+' .note-textarea').html(textArea);
    });

    $('body').on('click', '.update-note', function () {
        let noteId = $(this).data('note-id');

        var url = "{{ route('admin.applicant-note.update', ':id') }}";
        url = url.replace(':id', noteId);

        var note = $('#edit-note-text-'+noteId).val();
        var token = '{{ csrf_token() }}';

        $.easyAjax({
            type: 'POST',
            url: url,
            data: {'_token':token, 'noteId':noteId, 'note': note, '_method': 'PUT'},
            success: function (response) {
                if(response.status == 'success') {
                    $('#applicant-notes').html(response.view);
                }
            }
        });
    });

    $('body').on('click', '.delete-note', function(){
        let noteId = $(this).data('note-id');
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
        }, function(isConfirm){
            if (isConfirm) {

                var url = "{{ route('admin.applicant-note.destroy', ':id') }}";
                url = url.replace(':id', noteId);

                var token = '{{ csrf_token() }}';

                $.easyAjax({
                    type: 'POST',
                    url: url,
                    data: {'_token':token, '_method': 'DELETE'},
                    success: function (response) {
                        if(response.status == 'success') {
                            $('#applicant-notes').html(response.view);
                        }
                    }
                });
            }
        });
    });
</script>
@if(!is_null($application->skype_id))
    <script src="https://swc.cdn.skype.com/sdk/v1/sdk.min.js"></script>
@endif