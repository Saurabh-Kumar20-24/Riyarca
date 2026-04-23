<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">

    <style>
        body {
            font-family: DejaVu Sans, Arial, sans-serif;
            font-size: 12px;
            color: #1F2A44;
        }

        @page {
            margin: 12mm;
        }

        .container {
            border: 1px solid #e5e7eb;
            padding: 10px;
        }

        .header-strip {
            height: 4px;
            background: linear-gradient(to right, #1E5ED9, #6A2FE0, #D92BBF, #FF6A3D);
            margin-bottom: 8px;
        }

        .header {
            border-bottom: 1px solid #e5e7eb;
            padding-bottom: 8px;
            margin-bottom: 10px;
        }

        .company-name {
            font-size: 16px;
            font-weight: bold;
            margin: 0;
        }

        .sub-header {
            font-size: 11px;
            color: #555;
        }

        .section-title {
            background: #f2f5fb;
            font-weight: bold;
            padding: 5px;
            border: 1px solid #e5e7eb;
            margin-top: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
        }

        td,
        th {
            border: 1px solid #e5e7eb;
            padding: 5px;
        }

        th {
            background: #f8fafc;
            font-weight: bold;
            text-align: left;
        }

        .text-right {
            text-align: right;
        }

        .no-border td {
            border: none;
            padding: 3px 5px;
        }

        .netpay {
            background: #f2f5fb;
            border-left: 4px solid #6A2FE0;
            padding: 8px;
            font-size: 14px;
            font-weight: bold;
            text-align: right;
            margin-top: 10px;
        }

        .footer {
            text-align: center;
            font-size: 10px;
            margin-top: 10px;
            border-top: 1px solid #e5e7eb;
            padding-top: 5px;
        }
    </style>
</head>

<body>

    <div class="container">

        <div class="header-strip"></div>

        <div class="header">
            <table class="no-border">
                <tr>
                    <td style="width:20%;">
                        @php
                        $logoPath = public_path('assets/logo.png');
                        @endphp
                        <img src="data:image/png;base64,{{ base64_encode(file_get_contents($logoPath)) }}" style="height:50px;">
                    </td>

                    <td style="width:80%; text-align:right;">
                        <p class="company-name">SEO MAGICS</p>
                        <div class="sub-header">
                            RIYAMA TECH SOLUTIONS LLP<br>
                            Hanumangarh, Rajasthan - 335512
                        </div>
                        <div class="sub-header">
                            <strong>E-mail:</strong> info@seo-magics.com, hello@seomagics.com<br>
                            <strong>Tel. No:</strong> +91 8000266391, +91 7891266391<br>
                        </div>
                        <div class="sub-header">
                            Payslip for {{ date('F Y', mktime(0,0,0,$payslip->month,1,$payslip->year)) }}
                        </div>
                    </td>
                </tr>
            </table>
        </div>

        <table class="no-border">
            <tr>
                <td><strong>Employee Name:</strong> {{ $payslip->employee->name }}</td>
                <td><strong>Employee Code:</strong> {{ $payslip->employee->employee_id }}</td>
            </tr>
            <tr>
                <td><strong>Department:</strong> {{ $payslip->employee->department ?? '-' }}</td>
                <td><strong>Joining Date:</strong> {{ $payslip->employee->joining_date ?? '-' }}</td>
            </tr>
            <tr>
                <td><strong>Bank Name:</strong> {{ $payslip->employee->bankDetail->bank_name ?? '-' }}</td>
                <td><strong>Account No:</strong> {{ $payslip->employee->bankDetail->account_number }}</td>
            </tr>
            <tr>
                <td><strong>PAN:</strong> {{$payslip->employee->bankDetail->pan_number }}</td>
                <td><strong>PF No:</strong> {{ $payslip->employee->bankDetail->pf_number }}</td>
            </tr>
        </table>

        <div class="section-title">Attendance Details</div>
        <table>
            <tr>
                <th>Working Days</th>
                <th>Days Payable</th>
                <th>LOP</th>
            </tr>
            <tr>
                <td>{{ $payslip->working_days }}</td>
                <td>{{ $payslip->days_payable }}</td>
                <td>{{ $payslip->days_lop }}</td>
            </tr>
        </table>

        <div class="section-title">Earnings</div>
        <table>
            <tr>
                <th>Description</th>
                <th class="text-right">Amount (₹)</th>
            </tr>

            <tr>
                <td>Basic</td>
                <td class="text-right">{{ number_format($payslip->basic,2) }}</td>
            </tr>
            <tr>
                <td>HRA</td>
                <td class="text-right">{{ number_format($payslip->hra,2) }}</td>
            </tr>
            <tr>
                <td>Leave Travel Allowance</td>
                <td class="text-right">{{ number_format($payslip->leave_travel_allowance,2) }}</td>
            </tr>
            <tr>
                <td>Mobile & Broadband</td>
                <td class="text-right">{{ number_format($payslip->mobile_broadband_allowance,2) }}</td>
            </tr>
            <tr>
                <td>Research Allowance</td>
                <td class="text-right">{{ number_format($payslip->research_allowance,2) }}</td>
            </tr>
            <tr>
                <td>Fuel Allowance</td>
                <td class="text-right">{{ number_format($payslip->fuel_allowance,2) }}</td>
            </tr>

            @if($payslip->other_allowance > 0)
            <tr>
                <td>Other Allowance</td>
                <td class="text-right">{{ number_format($payslip->other_allowance,2) }}</td>
            </tr>
            @endif

            <tr>
                <td><strong>Total Earnings</strong></td>
                <td class="text-right"><strong>{{ number_format($payslip->gross_earnings,2) }}</strong></td>
            </tr>
        </table>

        <div class="section-title">Deductions</div>
        <table>
            <tr>
                <th>Description</th>
                <th class="text-right">Amount (₹)</th>
            </tr>

            <tr>
                <td>PF Contribution</td>
                <td class="text-right">{{ number_format($payslip->ee_pf_contribution,2) }}</td>
            </tr>
            <tr>
                <td>Professional Tax</td>
                <td class="text-right">{{ number_format($payslip->prof_tax,2) }}</td>
            </tr>
            <tr>
                <td>Other Deduction</td>
                <td class="text-right">{{ number_format($payslip->other_deduction,2) }}</td>
            </tr>

            <tr>
                <td><strong>Total Deductions</strong></td>
                <td class="text-right"><strong>{{ number_format($payslip->total_deductions,2) }}</strong></td>
            </tr>
        </table>

        <div class="netpay">
            NET PAY: ₹{{ number_format($payslip->net_pay,2) }}
        </div>


        <div class="footer">
            This is a system generated payslip and does not require signature.
        </div>

    </div>

</body>

</html>