<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Job Application Evaluation</title>
    <style>
        @page { size: A4; margin: 10mm; }
        body {
            font-family: DejaVu Sans, Arial, Helvetica, sans-serif;
            font-size: 12px;
            color: #000;
            margin: 0;
            background: #fff;
        }

        .page { width: 210mm; min-height: 297mm; box-sizing: border-box; padding: 0; }
        .no-print { display: inline-block; margin-bottom: 12px; }
        .topbar {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            margin-bottom: 12px;
            gap: 12px;
        }
        .company {
            font-weight: 700;
            letter-spacing: 0.2px;
        }
        .doc-meta { text-align: right; }
        .title {
            font-size: 18px;
            font-weight: 800;
            margin: 6px 0 10px;
        }

        .section-title {
            margin-top: 10px;
            margin-bottom: 6px;
            font-weight: 800;
            font-size: 13px;
            text-transform: none;
        }

        .field {
            margin: 4px 0 8px;
        }
        .field-row {
            display: flex;
            align-items: flex-start;
            gap: 12px;
        }
        .field-label {
            width: 44%;
            font-weight: 700;
        }
        .field-value {
            flex: 1;
            min-height: 18px;
            border-bottom: 1px dashed #000;
            padding-bottom: 2px;
            white-space: pre-wrap;
        }
        .field-block {
            border: 1px solid transparent;
        }

        .checkbox {
            display: inline-block;
            margin-right: 10px;
        }
        .box {
            display: inline-block;
            width: 12px;
            height: 12px;
            border: 1px solid #000;
            margin-right: 6px;
            vertical-align: -2px;
        }

        .grid-lines {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 6px 14px;
            margin-top: 6px;
        }
        .line {
            border-bottom: 1px dashed #000;
            min-height: 16px;
            padding-bottom: 2px;
        }

        table.eval-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
        }
        table.eval-table th, table.eval-table td {
            border: 1px solid #e5e7eb;
            padding: 6px 8px;
            vertical-align: top;
        }
        table.eval-table th {
            background: #f3f4f6;
            font-weight: 800;
            text-align: center;
        }
        .signature-wrap {
            margin-top: 18px;
            padding-top: 10px;
        }
        .signature-dash {
            border-bottom: 2px dashed #000;
            width: 360px;
            margin-top: 8px;
        }

        .subtle { color: #6b7280; }

        @media print {
            .no-print { display: none !important; }
        }
    </style>
    <script>
        window.addEventListener('load', function () { window.print(); });
    </script>
</head>
<body>
@php
    $scoresByCriterionId = [];
    if (isset($latestEval) && $latestEval && !empty($latestEval->scores)) {
        foreach ($latestEval->scores as $s) {
            $scoresByCriterionId[(int) $s->evaluation_criterion_id] = $s->score;
        }
    }
@endphp
<div class="page">
    <div class="no-print">
        <button type="button" onclick="window.print()" class="btn">Print</button>
    </div>

    @if(false)
    <div class="topbar">
        <div class="company">
            {{ $global->company_name ?? '' }}
        </div>
        <div class="doc-meta">
            <div>Job Application Form</div>
            <div class="subtle">Page 1 of 1</div>
        </div>
    </div>

    <div class="title">Job Application Form</div>

    <div class="section-title">Personal Information</div>

    <div class="field">
        <div class="field-row">
            <div class="field-label">1. Full Name (including Father’s and Grandfather’s Name)</div>
            <div class="field-value">{{ ucwords($application->full_name ?? '') }}</div>
        </div>
    </div>

    <div class="field">
        <div class="field-row">
            <div class="field-label">Mobile Phone Number</div>
            <div class="field-value">{{ $application->phone ?? '' }}</div>
        </div>
    </div>

    <div class="field">
        <div class="field-row">
            <div class="field-label">Email</div>
            <div class="field-value">{{ $application->email ?? '' }}</div>
        </div>
    </div>

    <div class="section-title">Birth Information</div>
    @php
        $req = $requiredColumns ?? [];
    @endphp

    @if(!empty($req['gender']))
        <div class="field">
            <div class="field-row">
                <div class="field-label">2. Gender</div>
                <div class="field-value">
                    {{ $application->gender ? ucwords(str_replace('_', ' ', (string) $application->gender)) : '' }}
                </div>
            </div>
        </div>
    @endif

    <div class="field">
        <div class="field-row">
            <div class="field-label">2. Date of Birth (Day / Month / Year)</div>
            <div class="field-value">
                {{ !empty($req['dob']) && !empty($application->dob) ? $application->dob->format('d M, Y') : '' }}
            </div>
        </div>
    </div>

    <div class="field-block">
        <div class="field">
            <div class="field-row">
                <div class="field-label">Place of Birth</div>
                <div class="field-value"></div>
            </div>
        </div>
        <div class="grid-lines">
            <div class="line">Region: {{ $req ? '' : '' }}</div>
            <div class="line">Zone: </div>
            <div class="line">Woreda: </div>
            <div class="line">Kebele: </div>
            <div class="line" style="grid-column: 1 / span 2;">Specific Location: </div>
        </div>
    </div>

    <div class="section-title">Current Address</div>

    @if(!empty($req['country']))
        <div class="field">
            <div class="field-row">
                <div class="field-label">3. Residential Address (Country/State/City)</div>
                <div class="field-value">{{ $application->country ?? '' }}</div>
            </div>
        </div>
        <div class="grid-lines">
            <div class="line">State: {{ $application->state ?? '' }}</div>
            <div class="line">City: {{ $application->city ?? '' }}</div>
            <div class="line" style="grid-column: 1 / span 2;">ZIP Code: {{ $application->zip_code ?? '' }}</div>
        </div>
    @endif

    @if(!empty($req['address']))
        <div class="field">
            <div class="field-row">
                <div class="field-label">Residential Address (House No. & Specific Location)</div>
                <div class="field-value">{{ $application->address ?? '' }}</div>
            </div>
        </div>
        <div class="grid-lines">
            <div class="line">Sub-city:</div>
            <div class="line">Woreda:</div>
            <div class="line">Kebele:</div>
            <div class="line">House No.:</div>
            <div class="line" style="grid-column: 1 / span 2;">Specific Location:</div>
        </div>
    @endif

    <div class="section-title">Education</div>
    <div class="field">
        <div class="field-row">
            <div class="field-label">4. Educational Level</div>
            <div class="field-value"></div>
        </div>
    </div>
    <div class="field">
        <div class="field-row">
            <div class="field-label">Field of Study</div>
            <div class="field-value"></div>
        </div>
    </div>
    <div class="field">
        <div class="field-row">
            <div class="field-label">5. Are you currently studying to improve your education?</div>
            <div class="field-value" style="border-bottom: none;">
                <label class="checkbox"><span class="box"></span> Yes</label>
                <label class="checkbox"><span class="box"></span> No</label>
            </div>
        </div>
    </div>

    <div class="section-title">Work Experience</div>
    <div class="field">
        <div class="field-row">
            <div class="field-label">6. Total Work Experience (in years)</div>
            <div class="field-value">
                {{ !empty($req['work_experience']) ? ($application->total_work_experience_years ?? '') : '' }}
            </div>
        </div>
    </div>
    <div class="field">
        <div class="field-row">
            <div class="field-label">7. Most Recent / Current Employer</div>
            <div class="field-value">
                {{ !empty($req['work_experience']) ? ($application->employer_name ?? '') : '' }}
            </div>
        </div>
    </div>
    <div class="grid-lines">
        <div class="line">Organization Name: {{ !empty($req['work_experience']) ? ($application->employer_name ?? '') : '' }}</div>
        <div class="line">Job Position: {{ !empty($req['work_experience']) ? ($application->job_position ?? '') : '' }}</div>
        <div class="line">Address: {{ !empty($req['work_experience']) ? ($application->employer_address ?? '') : '' }}</div>
        <div class="line">Salary: {{ !empty($req['work_experience']) ? ($application->employer_salary ?? '') : '' }}</div>
        <div class="line">Immediate Supervisor’s Name: {{ !empty($req['work_experience']) ? ($application->supervisor_name ?? '') : '' }}</div>
        <div class="line">Supervisor’s Mobile Number: {{ !empty($req['work_experience']) ? ($application->supervisor_mobile ?? '') : '' }}</div>
    </div>

    <div class="section-title">Connections & Availability</div>
    <div class="field">
        <div class="field-row">
            <div class="field-label">10. Do you know any employee in our organization?</div>
            <div class="field-value" style="border-bottom: none;">
                <label class="checkbox"><span class="box"></span> No</label>
                <label class="checkbox"><span class="box"></span> Yes → Name: __________________</label>
            </div>
        </div>
    </div>
    <div class="field">
        <div class="field-row">
            <div class="field-label">11. If selected, how soon can you start working?</div>
            <div class="field-value"></div>
        </div>
    </div>
    <div class="field">
        <div class="field-row">
            <div class="field-label">9. Expected Monthly Salary</div>
            <div class="field-value">
                {{ !empty($req['work_experience']) ? ($application->expected_monthly_salary ?? '') : '' }}
            </div>
        </div>
    </div>

    <div class="section-title">Requirements & Permissions</div>
    <div class="field">
        <div class="field-row">
            <div class="field-label">12. Can you provide two reliable guarantors (references)?</div>
            <div class="field-value" style="border-bottom: none;">
                <label class="checkbox"><span class="box"></span> Yes</label>
                <label class="checkbox"><span class="box"></span> No</label>
            </div>
        </div>
    </div>
    <div class="field">
        <div class="field-row">
            <div class="field-label">13. Can you provide a clearance letter from your previous/current employer?</div>
            <div class="field-value" style="border-bottom: none;">
                <label class="checkbox"><span class="box"></span> Yes</label>
                <label class="checkbox"><span class="box"></span> No</label>
            </div>
        </div>
    </div>
    <div class="field">
        <div class="field-row">
            <div class="field-label">14. Do you allow us to contact your previous/current employer?</div>
            <div class="field-value" style="border-bottom: none;">
                <label class="checkbox"><span class="box"></span> Yes</label>
                <label class="checkbox"><span class="box"></span> No</label>
            </div>
        </div>
    </div>

    <div class="section-title">Additional Details (Job Questions)</div>
    @forelse($answers as $answer)
        <div class="field">
            <div class="field-row">
                <div class="field-label">{{ $answer->question ? $answer->question->question : '' }}</div>
                <div class="field-value">
                    @if($answer->question && $answer->question->type == 'text')
                        {{ ucfirst($answer->answer) }}
                    @else
                        {{ $answer->file ? 'File attached' : '-' }}
                    @endif
                </div>
            </div>
        </div>
    @empty
        <div class="field"><div class="subtle">No additional details.</div></div>
    @endforelse
    @endif

    <div class="title">Job Application Evaluation</div>
    <div class="section-title">Applicant</div>
    <div class="field">
        <div class="field-row">
            <div class="field-label">Full Name</div>
            <div class="field-value">{{ ucwords($application->full_name ?? '') }}</div>
        </div>
    </div>

    @if(isset($latestEval) && $latestEval && $latestEval->group)
        <div class="section-title">Evaluation</div>

        <div class="field" style="margin-top: 0;">
            <div class="field-row">
                <div class="field-label">Group</div>
                <div class="field-value">
                    {{ $latestEval->group->name }} <span class="subtle">({{ $latestEval->total_score }}/100)</span>
                </div>
            </div>
        </div>

        <table class="eval-table">
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
                    $w = (int) $criterion->weight;
                    $m = (int) $criterion->max_score;
                    $maxPoints = $w > 0 ? min($w, max(1, $m)) : 0;
                @endphp
                <tr>
                    <td>{{ $criterion->name }}</td>
                    <td style="text-align:center;">{{ $criterion->weight }}%</td>
                    <td style="text-align:center;">{{ $maxPoints }}</td>
                    <td style="text-align:center;">
                        @if($savedScore === null || $savedScore === '') - @else {{ $savedScore }} @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="4" class="subtle" style="text-align:center;">No criteria configured.</td></tr>
            @endforelse
            </tbody>
        </table>
    @else
        <div class="section-title">Evaluation</div>
        <div class="field"><div class="subtle">No evaluation has been recorded yet.</div></div>
    @endif

    <div class="signature-wrap">
        <div><strong>Evaluator:</strong> {{ ucwords(optional($user)->name ?? '-') }}</div>
        <div><strong>Evaluator Role:</strong> {{ $adminRoleName ? $adminRoleName : '-' }}</div>
        <div class="signature-dash"></div>
        <div>Signature</div>
    </div>
</div>
</body>
</html>

