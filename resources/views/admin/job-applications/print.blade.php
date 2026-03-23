<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Job Application</title>
    <style>
        @page {
            size: A4;
            margin: 10mm;
        }

        body {
            font-family: DejaVu Sans, Arial, Helvetica, sans-serif;
            font-size: 12px;
            color: #000;
            margin: 0;
            background: #fff;
        }

        .page {
            padding: 0;
            width: 210mm;
            min-height: 297mm;
            box-sizing: border-box;
            padding: 14mm 12mm;
        }

        .title {
            font-size: 16px;
            font-weight: 700;
            margin-bottom: 14px;
        }

        .meta {
            margin-bottom: 12px;
        }

        .row {
            margin-bottom: 6px;
            display: flex;
            gap: 10px;
        }

        .label {
            display: inline-block;
            width: 130px;
            font-weight: 700;
        }

        h4 {
            margin-top: 16px;
            margin-bottom: 8px;
            font-size: 13px;
            font-weight: 700;
        }

        .details {
            margin-top: 6px;
        }

        .detail-item {
            margin-bottom: 8px;
        }

        .signature-wrap {
            margin-top: 18px;
            padding-top: 10px;
        }

        .signature-dash {
            border-bottom: 2px dashed #000;
            width: 340px;
            margin-top: 8px;
        }

        .no-print {
            display: inline-block;
            margin-bottom: 14px;
        }

        .btn-print {
            border: 1px solid #d1d5db;
            background: #fff;
            color: #111;
            padding: 6px 12px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 12px;
        }

        @media print {
            .no-print {
                display: none !important;
            }
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid #e5e7eb;
            padding: 6px 8px;
            vertical-align: top;
        }

        th {
            background: #f3f4f6;
            font-weight: 700;
            text-align: center;
        }

        td {
            line-height: 1.25;
        }

        .subtle {
            color: #6b7280;
        }
    </style>
    <script>
        window.addEventListener('load', function () {
            // Auto-print this extracted page only.
            window.print();
        });
    </script>
</head>
<body>
<div class="page">
    <div class="title">Job Application</div>

    <div class="meta">
        <div class="row">
            <span class="label">Name:</span>
            <span style="flex: 1;">{{ ucwords($application->full_name) }}</span>
        </div>
        <div class="row">
            <span class="label">Applied For:</span>
            <span>
                {{ $application->job ? ucwords($application->job->title) : '-' }}
                @if($application->job && $application->job->location)
                    ({{ ucwords($application->job->location->location) }})
                @endif
            </span>
        </div>
        <div class="row">
            <span class="label">Email:</span>
            <span style="flex: 1;">{{ $application->email }}</span>
        </div>
        <div class="row">
            <span class="label">Phone:</span>
            <span style="flex: 1;">{{ $application->phone }}</span>
        </div>
        <div class="row">
            <span class="label">Applied at:</span>
            <span style="flex: 1;">{{ $application->created_at->timezone($global->timezone)->format('d M, Y H:i') }}</span>
        </div>
        @if(!is_null($application->cover_letter))
            <div class="row">
                <span class="label">Cover Letter:</span>
                <span style="flex: 1;">{{ $application->cover_letter }}</span>
            </div>
        @endif
    </div>

    @if(isset($latestEval) && $latestEval && $latestEval->total_score !== null)
        <div class="meta evaluation-group">
            <div class="row">
                <span class="label">Evaluation:</span>
                <span style="flex: 1;">
                    {{ $latestEval->group ? $latestEval->group->name : '' }}
                    <span class="subtle">({{ $latestEval->total_score }}/100)</span>
                </span>
            </div>
        </div>
    @endif

    <h4>Additional Details</h4>
    <div class="details">
        @forelse($answers as $answer)
            <div class="detail-item">
                <strong>{{ $answer->question ? $answer->question->question : '' }}</strong><br>
                @if($answer->question && $answer->question->type == 'text')
                    <span>{{ ucfirst($answer->answer) }}</span>
                @else
                    @if(!is_null($answer->file))
                        <a target="_blank" href="{{ $answer->file_url }}">View file</a>
                    @else
                        <span>-</span>
                    @endif
                @endif
            </div>
        @empty
            <div class="text-muted">No additional details.</div>
        @endforelse
    </div>

    @if(isset($latestEval) && $latestEval && $latestEval->group)
        @php
            $scoresByCriterionId = [];
            if (!empty($latestEval->scores)) {
                foreach ($latestEval->scores as $s) {
                    $scoresByCriterionId[(int) $s->evaluation_criterion_id] = $s->score;
                }
            }
        @endphp

        <h4>Evaluation</h4>
        <div class="meta">
            <div class="row">
                <span class="label">Group:</span>
                <span>{{ $latestEval->group->name }} ({{ $latestEval->total_score }}/100)</span>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th style="width: 60%;">Criterion</th>
                    <th style="width: 10%;">Weight</th>
                    <th style="width: 15%;">Max pts</th>
                    <th style="width: 15%;">Score</th>
                </tr>
            </thead>
            <tbody>
                @forelse($latestEval->group->criteria as $criterion)
                    @php
                        $savedScore = $scoresByCriterionId[(int)$criterion->id] ?? null;
                    @endphp
                    <tr>
                        <td>{{ $criterion->name }}</td>
                        <td style="text-align: center;">{{ $criterion->weight }}%</td>
                        <td style="text-align: center;">
                            @php
                                $w = (int) $criterion->weight;
                                $m = (int) $criterion->max_score;
                                $maxPoints = $w > 0 ? min($w, max(1, $m)) : 0;
                            @endphp
                            {{ $maxPoints }}
                        </td>
                        <td style="text-align: center;">
                            @if($savedScore === null || $savedScore === '')
                                -
                            @else
                                {{ $savedScore }}
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" style="text-align: center; color: #777;">No criteria configured.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if(!empty($latestEval->overall_comment))
            <h4>Overall Comment</h4>
            <div class="details">
                <div class="detail-item">
                    {{ $latestEval->overall_comment }}
                </div>
            </div>
        @endif
    @endif

    <div class="signature-wrap">
        <div class="no-print">
            <button type="button" class="btn-print" onclick="window.print()">Print</button>
        </div>
        <div><strong>Signed by:</strong> {{ ucwords(optional($user)->name ?? '-') }}</div>
        <div><strong>Role:</strong> {{ $adminRoleName ? $adminRoleName : '-' }}</div>
        <div class="signature-dash"></div>
        <div>Signature</div>
    </div>
</div>
</body>
</html>

