@extends('layouts.header')

@section('title', 'Payslip')
@section('page-title', 'Payslip')

@section('content')

<style>
    @media print {
        body * {
            visibility: hidden;
        }

        #printArea,
        #printArea * {
            visibility: visible;
        }

        #printArea {
            width: 100%;
            max-width: 100%;
        }

        .sidebar,
        .navbar,
        .page-header,
        nav,
        header,
        footer {
            display: none !important;
        }

        tr {
            page-break-inside: avoid;
        }

        table {
            page-break-inside: auto;
        }

        @page {
            size: A4 portrait;
            margin: 10mm;
        }
    }

    /* 🌈 Gradient strip */
    .header-strip {
        height: 4px;
        background: linear-gradient(to right, #1E5ED9, #6A2FE0, #D92BBF, #FF6A3D);
        margin-bottom: 10px;
    }

    /* Section headers */
    .section-title {
        background: #f2f5fb;
        color: #1F2A44;
        font-weight: 600;
        padding: 6px;
        border: 1px solid #e5e7eb;
    }

    /* Table styling */
    table {
        width: 100%;
        border-collapse: collapse;
    }

    table th {
        background: #f8fafc;
        color: #1F2A44;
        font-weight: 600;
    }

    table th,
    table td {
        border: 1px solid #e5e7eb;
        padding: 6px;
    }

    /* Net Pay */
    .net-pay {
        background: #f2f5fb;
        padding: 10px;
        border-left: 4px solid #6A2FE0;
    }
</style>

<div class="page-header">
    <h2>Payslip – {{ date('F', mktime(0,0,0,$payslip->month,1)) }} {{ $payslip->year }}</h2>

    <div class="header-actions">
        <a href="{{ route('payslip.previous', $employee->id) }}" class="btn-search">← Back</a>
        @if(Auth::user()->role_id!==9 && Auth::user()->role_id!==1)
        <button onclick="downloadPDF()" class="btn-add" id="downloadBtn">
            ⬇ Download PDF
        </button>
        @endif
    </div>
</div>

<div class="table-card" id="printArea" style="max-width:800px; margin:auto; padding:15px; font-size:13px; border:1px solid #e5e7eb;">

    <div class="header-strip"></div>

    <div style="border-bottom:1px solid #e5e7eb; padding-bottom:8px; margin-bottom:10px;">
        <table>
            <tr>
                <td style="width:20%;">
                    @php
                    $logoPath = public_path('assets/logo.png');
                    @endphp
                    <img src="data:image/png;base64,{{ base64_encode(file_get_contents($logoPath)) }}"
                        style="height:55px;">
                </td>

                <td style="width:80%; text-align:right;">
                    <h2 style="margin:0;">SEO MAGICS</h2>
                    <p style="margin:2px 0; font-size:12px;">
                        RIYAMA TECH SOLUTIONS LLP <br>
                        Rajiv Chowk, Hanumangarh, Rajasthan - 335512 <br>
                        <strong>E-mail:</strong> info@seo-magics.com, hello@seomagics.com <br>
                        <strong>Tel. No:</strong> +91 8000266391, +91 7891266391
                    </p>
                    <strong>
                        Payslip - {{ date('F Y', mktime(0,0,0,$payslip->month,1,$payslip->year)) }}
                    </strong>
                </td>
            </tr>
        </table>
    </div>

    <table style="margin-bottom:10px;">
        <tr>
            <td><strong>Employee Name:</strong> {{$payslip->employee->name }}</td>
            <td><strong>Employee Code:</strong> {{ $payslip->employee->employee_id }}</td>
        </tr>
        <tr>
            <td><strong>Department:</strong> {{ $payslip->employee->department ?? '-' }}</td>
            <td><strong>Joining Date:</strong> {{ $payslip->employee->joining_date ?? '-' }}</td>
        </tr>
        <tr>
            <td><strong>Bank:</strong> {{ $payslip->employee->bankDetail->bank_name ?? '-'  }}</td>
            <td><strong>Account No:</strong> {{ $payslip->employee->bankDetail->account_number }}</td>
        </tr>
        <tr>
            <td><strong>PAN:</strong> {{ $payslip->employee->bankDetail->pan_number }}</td>
            <td><strong>PF No:</strong> {{ $payslip->employee->bankDetail->pf_number }}</td>
        </tr>
    </table>

    <div class="section-title">Attendance Details</div>
    <table style="margin-bottom:10px;">
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
            <th style="text-align:right;">Amount (₹)</th>
        </tr>

        <tr>
            <td>Basic</td>
            <td style="text-align:right;">{{ number_format($payslip->basic,2) }}</td>
        </tr>
        <tr>
            <td>HRA</td>
            <td style="text-align:right;">{{ number_format($payslip->hra,2) }}</td>
        </tr>
        <tr>
            <td>Leave Travel Allowance</td>
            <td style="text-align:right;">{{ number_format($payslip->leave_travel_allowance,2) }}</td>
        </tr>
        <tr>
            <td>Mobile & Broadband</td>
            <td style="text-align:right;">{{ number_format($payslip->mobile_broadband_allowance,2) }}</td>
        </tr>
        <tr>
            <td>Research Allowance</td>
            <td style="text-align:right;">{{ number_format($payslip->research_allowance,2) }}</td>
        </tr>
        <tr>
            <td>Fuel Allowance</td>
            <td style="text-align:right;">{{ number_format($payslip->fuel_allowance,2) }}</td>
        </tr>

        @if($payslip->other_allowance > 0)
        <tr>
            <td>Other Allowance</td>
            <td style="text-align:right;">{{ number_format($payslip->other_allowance,2) }}</td>
        </tr>
        @endif

        <tr style="font-weight:bold;">
            <td>Total Earnings</td>
            <td style="text-align:right;">{{ number_format($payslip->gross_earnings,2) }}</td>
        </tr>
    </table>

    <div class="section-title" style="margin-top:10px;">Deductions</div>
    <table>
        <tr>
            <th>Description</th>
            <th style="text-align:right;">Amount (₹)</th>
        </tr>

        <tr>
            <td>PF Contribution</td>
            <td style="text-align:right;">{{ number_format($payslip->ee_pf_contribution,2) }}</td>
        </tr>
        <tr>
            <td>Professional Tax</td>
            <td style="text-align:right;">{{ number_format($payslip->prof_tax,2) }}</td>
        </tr>
        <tr>
            <td>Other Deduction</td>
            <td style="text-align:right;">{{ number_format($payslip->other_deduction,2) }}</td>
        </tr>

        <tr style="font-weight:bold;">
            <td>Total Deductions</td>
            <td style="text-align:right;">{{ number_format($payslip->total_deductions,2) }}</td>
        </tr>
    </table>

    <div class="net-pay" style="text-align:right; font-size:15px; font-weight:bold; margin-top:10px;">
        NET PAY: ₹{{ number_format($payslip->net_pay,2) }}
    </div>
    <!-- @php
    $amountWords = '';
    if (class_exists('NumberFormatter')) {
        $fmt = new NumberFormatter('en_IN', NumberFormatter::SPELLOUT);
        $amountWords = ucwords($fmt->format(round($payslip->net_pay))) . ' Rupees Only';
    } else {
        $amountWords = 'Amount in words not available';
    }
@endphp

<div style="margin-top:4px; font-size:11px; color:#555;">
    (In Words: {{ $amountWords }})
</div> -->

    <p style="margin-top:15px; font-size:11px; text-align:center; border-top:1px solid #e5e7eb; padding-top:8px;">
        This is a system generated payslip and does not require a signature.
    </p>

</div>

<script>
    function downloadPDF() {
        const btn = document.getElementById('downloadBtn');
        btn.textContent = '⏳ Generating...';
        btn.disabled = true;

        window.location.href = "{{ route('payslip.secure.download', $payslip->id) }}";

        setTimeout(() => {
            btn.textContent = '⬇ Download PDF';
            btn.disabled = false;
        }, 2000);
    }
</script>

@endsection