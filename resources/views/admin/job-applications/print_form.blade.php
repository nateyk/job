<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Job Application Evaluation</title>
    <style>
        @page { size: A4 portrait; margin: 18mm 16mm; }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            font-family: Arial, Helvetica, sans-serif;
            color: #111827;
            background: #ffffff;
            font-size: 13px;
            line-height: 1.45;
        }

        /* Hide third-party injected containers/extensions in print context. */
        veepn-lock-screen,
        [class*="ssBtn"],
        [class*="vsNotification"],
        [class*="ssModal"] {
            display: none !important;
        }

        .page {
            width: 100%;
            margin: 0;
            background: #ffffff;
            padding: 0;
        }

        .print-bar {
            margin-bottom: 14px;
        }

        .print-btn {
            border: 1px solid #d1d5db;
            background: #ffffff;
            color: #111827;
            font-size: 12px;
            padding: 6px 12px;
            border-radius: 6px;
            cursor: pointer;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 16px;
            border-bottom: 1px solid #d1d5db;
            padding-bottom: 10px;
            margin-bottom: 14px;
        }

        .company-name {
            font-size: 14px;
            font-weight: 700;
            letter-spacing: 0.2px;
        }

        .doc-meta {
            text-align: right;
            font-size: 12px;
            color: #6b7280;
        }

        .title {
            margin: 0;
            font-size: 26px;
            font-weight: 700;
            color: #0f172a;
        }

        .section {
            margin-top: 18px;
        }

        .section-box {
            border: 1px solid #d1d5db;
            border-radius: 6px;
            padding: 12px 14px;
        }

        .section-title {
            margin: 0 0 10px;
            font-size: 15px;
            font-weight: 700;
            color: #111827;
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }

        .field-grid {
            display: grid;
            grid-template-columns: 190px 1fr;
            gap: 8px 12px;
            align-items: center;
        }

        .label {
            font-weight: 600;
            color: #374151;
        }

        .value {
            border-bottom: 1px solid #d1d5db;
            min-height: 28px;
            padding: 4px 0;
            color: #111827;
        }

        .subtle {
            color: #6b7280;
        }

        table.eval-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
            font-size: 13px;
        }

        .eval-table th,
        .eval-table td {
            border: 1px solid #d1d5db;
            padding: 8px 10px;
            vertical-align: top;
        }

        .eval-table th {
            background: #f9fafb;
            font-weight: 700;
            text-align: left;
            color: #1f2937;
        }

        .text-center { text-align: center; }

        .signature-wrap {
            margin-top: 24px;
            border-top: 1px solid #e5e7eb;
            padding-top: 14px;
        }

        .notes-box {
            margin-top: 14px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            min-height: 90px;
            padding: 10px;
        }

        .signature-line {
            margin-top: 18px;
            border-bottom: 1px dashed #111827;
            width: 320px;
            max-width: 100%;
        }

        @media print {
            body {
                background: #fff !important;
                font-size: 12pt;
            }

            body > :not(.page) {
                display: none !important;
            }

            .page {
                margin: 0;
                border: none;
                border-radius: 0;
                padding: 6mm 2mm;
            }

            .print-bar {
                display: none !important;
            }

            .title {
                font-size: 24pt;
            }

            .section-title {
                font-size: 12pt;
            }

            .company-name,
            .doc-meta {
                font-size: 10.5pt;
            }

            .eval-table {
                font-size: 11pt;
            }

            .eval-table th,
            .eval-table td {
                padding: 8px 10px;
            }
        }
    </style>
    <script>
        window.addEventListener('load', function () {
            window.print();
        });
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
    <div class="print-bar">
        <button type="button" onclick="window.print()" class="print-btn">Print</button>
    </div>

    <div class="header">
        <div>
            <div class="company-name">{{ $global->company_name ?? 'Company' }}</div>
            <h1 class="title">Job Application Evaluation</h1>
        </div>
        <div class="doc-meta">
            <div>Application ID: {{ $application->id ?? '-' }}</div>
            <div>Date: {{ now()->format('d M Y') }}</div>
        </div>
    </div>

    <section class="section">
        <h2 class="section-title">Applicant</h2>
        <div class="section-box">
            <div class="field-grid">
                <div class="label">Full Name</div>
                <div class="value">{{ ucwords($application->full_name ?? '') }}</div>
            </div>
        </div>
    </section>

    <section class="section">
        <h2 class="section-title">Evaluation</h2>
        @if(isset($latestEval) && $latestEval && $latestEval->group)
            <div class="section-box">
                <div class="field-grid">
                    <div class="label">Group</div>
                    <div class="value">{{ $latestEval->group->name }}</div>
                    <div class="label">Total Score</div>
                    <div class="value">{{ $latestEval->total_score }}/100</div>
                </div>

                <table class="eval-table">
                    <thead>
                    <tr>
                        <th style="width: 54%;">Criterion</th>
                        <th style="width: 12%;" class="text-center">Weight</th>
                        <th style="width: 14%;" class="text-center">Max Pts</th>
                        <th style="width: 20%;" class="text-center">Score</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($latestEval->group->criteria as $criterion)
                        @php
                            $savedScore = $scoresByCriterionId[(int) $criterion->id] ?? null;
                            $w = (int) $criterion->weight;
                            $m = (int) $criterion->max_score;
                            $maxPoints = $w > 0 ? min($w, max(1, $m)) : 0;
                        @endphp
                        <tr>
                            <td>{{ $criterion->name }}</td>
                            <td class="text-center">{{ $criterion->weight }}%</td>
                            <td class="text-center">{{ $maxPoints }}</td>
                            <td class="text-center">{{ ($savedScore === null || $savedScore === '') ? '-' : $savedScore }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="subtle text-center">No criteria configured.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        @else
            <div class="section-box">
                <div class="field-grid">
                    <div class="label">Group</div>
                    <div class="value">Pending Evaluation</div>
                    <div class="label">Total Score</div>
                    <div class="value">-</div>
                </div>

                <table class="eval-table">
                    <thead>
                    <tr>
                        <th style="width: 54%;">Criterion</th>
                        <th style="width: 12%;" class="text-center">Weight</th>
                        <th style="width: 14%;" class="text-center">Max Pts</th>
                        <th style="width: 20%;" class="text-center">Score</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr><td>&nbsp;</td><td class="text-center">-</td><td class="text-center">-</td><td class="text-center">-</td></tr>
                    <tr><td>&nbsp;</td><td class="text-center">-</td><td class="text-center">-</td><td class="text-center">-</td></tr>
                    <tr><td>&nbsp;</td><td class="text-center">-</td><td class="text-center">-</td><td class="text-center">-</td></tr>
                    </tbody>
                </table>
            </div>
        @endif
    </section>

    <section class="signature-wrap">
        <div><strong>Evaluator:</strong> {{ ucwords(optional($user)->name ?? '-') }}</div>
        <div><strong>Evaluator Role:</strong> {{ $adminRoleName ? $adminRoleName : '-' }}</div>
        <div class="notes-box"><span class="subtle">Evaluator Notes</span></div>
        <div class="signature-line"></div>
        <div class="subtle">Signature</div>
    </section>
</div>
</body>
</html>

