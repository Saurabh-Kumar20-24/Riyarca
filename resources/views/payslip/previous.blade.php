@extends('layouts.header')

@section('title', 'Previous Payslips')
@section('page-title', 'Previous Payslips')

@section('content')

<div class="page-header">
    <h2>Previous Payslips – {{ $employee->name }}</h2>
    <div class="header-actions">
        <a href="{{ route('payslip.index') }}" class="btn-add">← Back</a>
    </div>
</div>

<div class="table-card">
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>S.No.</th>
                    <th>Month</th>
                    <th>Year</th>
                    <th>Gross Earnings</th>
                    <th>Total Deductions</th>
                    <th>Net Pay</th>
                    <th>Generated On</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($payslips as $i => $ps)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ date('F', mktime(0,0,0,$ps->month,1)) }}</td>
                    <td>{{ $ps->year }}</td>
                    <td>₹{{ number_format($ps->gross_earnings, 2) }}</td>
                    <td>₹{{ number_format($ps->total_deductions, 2) }}</td>
                    <td><strong>₹{{ number_format($ps->net_pay, 2) }}</strong></td>
                    <td>{{ $ps->created_at->format('d M Y') }}</td>
                    <td>
                        <a href="{{ route('payslip.show', $ps->id) }}" class="btn-view"
                            style="font-size:12px; padding:5px 12px;">View</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" style="text-align:center; color:#aaa; padding:2rem;">
                        No payslips generated yet.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection