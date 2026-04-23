@extends('layouts.header')

@section('title', 'Payslip')
@section('page-title', 'Payslip')

@section('content')

@if(session('success'))
<div id="successAlert" class="alert-success-custom">{{ session('success') }}</div>
@endif

<div class="page-header">
    <h2>Payslip Management</h2>
    <div class="header-actions">
        <form method="GET" action="{{ route('payslip.index') }}" class="d-flex gap-2">
            <input type="text" name="search" class="search-input"
                placeholder="Search employee..." value="{{ request('search') }}">
            <button type="submit" class="btn-search">Search</button>
        </form>
        <form method="GET" action="{{ route('payslip.index') }}">
            <select name="role" class="filter-select" onchange="this.form.submit()">
                <option value="">All Roles</option>
                @foreach($roles as $role)
                <option value="{{ $role->id }}" {{ request('role') == $role->id ? 'selected' : '' }}>
                    {{ $role->role_name }}
                </option>
                @endforeach
            </select>
        </form>
    </div>
</div>

<div class="table-card">
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>S.No.</th>
                    <th>Employee ID</th>
                    <th>Name</th>
                    <th>Bank Name</th>
                    <th>Account No</th>
                    <th>IFSC code</th>
                    <th>PAN No</th>
                    <th>PF No</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($employees as $i => $emp)
                <tr>
                    <td>{{ $employees->firstItem() + $i }}</td>
                    <td>{{ $emp->employee_id }}</td>
                    <td>{{ $emp->name }}</td>
                    <td>{{ optional($emp->bankDetail)->bank_name ?? '-' }}</td>
                    <td>{{ optional($emp->bankDetail)->account_number ?? '-' }}</td>
                    <td>{{ optional($emp->bankDetail)->ifsc_code ?? '-' }}</td>
                    <td>{{ optional($emp->bankDetail)->pan_number ?? '-' }}</td>
                    <td>{{ optional($emp->bankDetail)->pf_number ?? '-' }}</td>
                    <td>
                        <div class="action-menu">
                            <button class="action-toggle" onclick="toggleMenu(this)" title="Actions">&#8942;</button>
                            <div class="action-dropdown">
                                <button type="button" class="btn-delete-action"
                                    onclick="openGenerateModal({{ $emp->id }})">
                                    <i class="bi bi-receipt"></i> Generate
                                </button>
                                <a href="{{ route('payslip.previous', $emp->id) }}">
                                    <i class="bi bi-folder2-open"></i> Previous
                                </a>
                            </div>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" style="text-align:center; color:#aaa; padding:2rem;">
                        No employees found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{ $employees->links() }}

{{-- Generate Payslip Modal --}}
<!-- <div class="modal-overlay" id="generateModal">
    <div class="modal-box" style="max-width:750px; width:95%; max-height:90vh; overflow-y:auto;">
        <div class="modal-icon">🧾</div>
        <h5>Generate Payslip</h5>

        <form method="POST" action="{{ route('payslip.store') }}" id="payslipForm">
            @csrf

            <input type="hidden" name="employee_id" id="form_employee_id">

            {{-- Employee Info (Auto-filled) --}}
            <div style="background:#f8f9fa; border-radius:8px; padding:14px; margin-bottom:16px; text-align:left;">
                <strong style="display:block; margin-bottom:10px; color:#374151;">Employee Details</strong>
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:10px;">
                    <div>
                        <label style="font-size:12px; color:#6b7280;">Employee ID</label>
                        <input type="text" id="show_emp_id" class="search-input" style="width:100%;" readonly>
                    </div>
                    <div>
                        <label style="font-size:12px; color:#6b7280;">Name</label>
                        <input type="text" id="show_name" class="search-input" style="width:100%;" readonly>
                    </div>
                    <div>
                        <label style="font-size:12px; color:#6b7280;">Email</label>
                        <input type="text" id="show_email" class="search-input" style="width:100%;" readonly>
                    </div>
                    <div>
                        <label style="font-size:12px; color:#6b7280;">Phone</label>
                        <input type="text" id="show_phone" class="search-input" style="width:100%;" readonly>
                    </div>
                    <div>
                        <label style="font-size:12px; color:#6b7280;">Department</label>
                        <input type="text" id="show_dept" class="search-input" style="width:100%;" readonly>
                    </div>
                    <div>
                        <label style="font-size:12px; color:#6b7280;">Date of Joining</label>
                        <input type="text" id="show_joining" class="search-input" style="width:100%;" readonly>
                    </div>
                </div>
            </div>

            {{-- Bank Details (Auto-filled) --}}
            <div style="background:#f8f9fa; border-radius:8px; padding:14px; margin-bottom:16px; text-align:left;">
                <strong style="display:block; margin-bottom:10px; color:#374151;">Bank & Statutory Details</strong>
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:10px;">
                    <div>
                        <label style="font-size:12px; color:#6b7280;">Bank Name</label>
                        <input type="text" name="bank_name" id="form_bank_name" class="search-input" style="width:100%;">
                    </div>
                    <div>
                        <label style="font-size:12px; color:#6b7280;">Bank Account</label>
                        <input type="text" name="bank_account" id="form_bank_account" class="search-input" style="width:100%;">
                    </div>
                    <div>
                        <label style="font-size:12px; color:#6b7280;">PAN Number</label>
                        <input type="text" name="pan_number" id="form_pan" class="search-input" style="width:100%;">
                    </div>
                    <div>
                        <label style="font-size:12px; color:#6b7280;">PF Number</label>
                        <input type="text" name="pf_number" id="form_pf" class="search-input" style="width:100%;">
                    </div>
                    <div>
                        <label style="font-size:12px; color:#6b7280;">Payment Mode</label>
                        <select name="payment_mode" class="filter-select" style="width:100%;">
                            <option value="Bank Transfer">Bank Transfer</option>
                            <option value="Cash">Cash</option>
                            <option value="Cheque">Cheque</option>
                        </select>
                    </div>
                </div>
            </div>

            {{-- Payslip Period --}}
            <div style="background:#f8f9fa; border-radius:8px; padding:14px; margin-bottom:16px; text-align:left;">
                <strong style="display:block; margin-bottom:10px; color:#374151;">Payslip Period & Days</strong>
                <div style="display:grid; grid-template-columns:1fr 1fr 1fr 1fr; gap:10px;">
                    <div>
                        <label style="font-size:12px; color:#6b7280;">Month *</label>
                        <select name="month" id="form_month" class="filter-select" style="width:100%;" required>
                            <option value="">Month</option>
                            @foreach(range(1,12) as $m)
                            <option value="{{ $m }}">{{ date('F', mktime(0,0,0,$m,1)) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label style="font-size:12px; color:#6b7280;">Year *</label>
                        <input type="number" name="year" class="search-input" style="width:100%;"
                            value="{{ date('Y') }}" min="2000" max="2100" required>
                    </div>
                    <div>
                        <label style="font-size:12px; color:#6b7280;">Working Days</label>
                        <input type="number" name="working_days" class="search-input" style="width:100%;" value="31">
                    </div>
                    <div>
                        <label style="font-size:12px; color:#6b7280;">Days Payable *</label>
                        <input type="number" name="days_payable" class="search-input" style="width:100%;" required>
                    </div>
                    <div>
                        <label style="font-size:12px; color:#6b7280;">Days LOP</label>
                        <input type="number" name="days_lop" class="search-input" style="width:100%;" value="0">
                    </div>
                </div>
            </div>

            {{-- Earnings --}}
            <div style="background:#f8f9fa; border-radius:8px; padding:14px; margin-bottom:16px; text-align:left;">
                <strong style="display:block; margin-bottom:10px; color:#374151;">Earnings</strong>
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:10px;">
                    <div>
                        <label style="font-size:12px; color:#6b7280;">Basic *</label>
                        <input type="number" step="0.01" name="basic" class="search-input earning-input"
                            style="width:100%;" placeholder="0.00" required oninput="calcTotal()">
                    </div>
                    <div>
                        <label style="font-size:12px; color:#6b7280;">HRA</label>
                        <input type="number" step="0.01" name="hra" class="search-input earning-input"
                            style="width:100%;" placeholder="0.00" oninput="calcTotal()">
                    </div>
                    <div>
                        <label style="font-size:12px; color:#6b7280;">Leave Travel Allowance</label>
                        <input type="number" step="0.01" name="leave_travel_allowance" class="search-input earning-input"
                            style="width:100%;" placeholder="0.00" oninput="calcTotal()">
                    </div>
                    <div>
                        <label style="font-size:12px; color:#6b7280;">Mobile & Broadband</label>
                        <input type="number" step="0.01" name="mobile_broadband_allowance" class="search-input earning-input"
                            style="width:100%;" placeholder="0.00" oninput="calcTotal()">
                    </div>
                    <div>
                        <label style="font-size:12px; color:#6b7280;">Research Allowance</label>
                        <input type="number" step="0.01" name="research_allowance" class="search-input earning-input"
                            style="width:100%;" placeholder="0.00" oninput="calcTotal()">
                    </div>
                    <div>
                        <label style="font-size:12px; color:#6b7280;">Fuel Allowance</label>
                        <input type="number" step="0.01" name="fuel_allowance" class="search-input earning-input"
                            style="width:100%;" placeholder="0.00" oninput="calcTotal()">
                    </div>
                    <div>
                        <label style="font-size:12px; color:#6b7280;">Other Allowance</label>
                        <input type="number" step="0.01" name="other_allowance" class="search-input earning-input"
                            style="width:100%;" placeholder="0.00" oninput="calcTotal()">
                    </div>
                    <div>
                        <label style="font-size:12px; color:#6b7280; font-weight:600;">Gross Earnings</label>
                        <input type="text" id="gross_earnings_display" class="search-input"
                            style="width:100%; font-weight:600; background:#e9f5ff;" readonly>
                    </div>
                </div>
            </div>

            {{-- Deductions --}}
            <div style="background:#f8f9fa; border-radius:8px; padding:14px; margin-bottom:16px; text-align:left;">
                <strong style="display:block; margin-bottom:10px; color:#374151;">Deductions</strong>
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:10px;">
                    <div>
                        <label style="font-size:12px; color:#6b7280;">EE PF Contribution</label>
                        <input type="number" step="0.01" name="ee_pf_contribution" class="search-input deduction-input"
                            style="width:100%;" placeholder="0.00" oninput="calcTotal()">
                    </div>
                    <div>
                        <label style="font-size:12px; color:#6b7280;">Professional Tax</label>
                        <input type="number" step="0.01" name="prof_tax" class="search-input deduction-input"
                            style="width:100%;" placeholder="0.00" oninput="calcTotal()">
                    </div>
                    <div>
                        <label style="font-size:12px; color:#6b7280;">Other Deduction</label>
                        <input type="number" step="0.01" name="other_deduction" class="search-input deduction-input"
                            style="width:100%;" placeholder="0.00" oninput="calcTotal()">
                    </div>
                    <div>
                        <label style="font-size:12px; color:#6b7280; font-weight:600;">Total Deductions</label>
                        <input type="text" id="total_deductions_display" class="search-input"
                            style="width:100%; font-weight:600; background:#fff0f0;" readonly>
                    </div>
                </div>
            </div>

            {{-- Net Pay & CTC --}}
            <div style="background:#f0fdf4; border-radius:8px; padding:14px; margin-bottom:16px; text-align:left;">
                <strong style="display:block; margin-bottom:10px; color:#374151;">Net Pay & CTC</strong>
                <div style="display:grid; grid-template-columns:1fr 1fr 1fr; gap:10px;">
                    <div>
                        <label style="font-size:12px; color:#6b7280; font-weight:700;">Net Pay</label>
                        <input type="text" id="net_pay_display" class="search-input"
                            style="width:100%; font-weight:700; font-size:15px; background:#bbf7d0;" readonly>
                    </div>
                    <div>
                        <label style="font-size:12px; color:#6b7280;">Fixed Annual Salary</label>
                        <input type="number" step="0.01" name="fixed_annual_salary" class="search-input"
                            style="width:100%;" placeholder="0.00">
                    </div>
                    <div>
                        <label style="font-size:12px; color:#6b7280;">Variable Annual Salary</label>
                        <input type="number" step="0.01" name="variable_annual_salary" class="search-input"
                            style="width:100%;" placeholder="0.00">
                    </div>
                    <div>
                        <label style="font-size:12px; color:#6b7280;">CTC Effective Date</label>
                        <input type="date" name="ctc_effective_date" class="search-input" style="width:100%;">
                    </div>
                </div>
            </div>

            <div class="modal-actions">
                <button type="button" class="btn-cancel-modal" onclick="closeGenerateModal()">Cancel</button>
                <button type="submit" class="btn-add">Generate Payslip</button>
            </div>
        </form>
    </div>
</div> -->

<div class="modal-overlay" id="generateModal">
    <div class="modal-box" style="max-width:900px; width:95%; max-height:90vh; overflow-y:auto; text-align:left;">

        <div class="modal-icon">🧾</div>
        <h5>Generate Payslip</h5>

        <form method="POST" action="{{ route('payslip.store') }}" id="payslipForm">
            @csrf
            <input type="hidden" name="employee_id" id="form_employee_id">

            <div class="form-card">

                <h3><i class="bi bi-person"></i> Employee Details</h3>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Employee ID</label>
                        <input type="text" id="show_emp_id" class="form-input" readonly>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Name</label>
                        <input type="text" id="show_name" class="form-input" readonly>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Email</label>
                        <input type="text" id="show_email" class="form-input" readonly>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Phone</label>
                        <input type="text" id="show_phone" class="form-input" readonly>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Department</label>
                        <input type="text" id="show_dept" class="form-input" readonly>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Joining Date</label>
                        <input type="text" id="show_joining" class="form-input" readonly>
                    </div>
                </div>

                <h3><i class="bi bi-bank"></i> Bank & Statutory</h3>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Bank Name</label>
                        <input type="text" name="bank_name" id="form_bank_name" class="form-input">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Account No</label>
                        <input type="text" name="bank_account" id="form_bank_account" class="form-input">
                    </div>
                    <div class="form-group">
                        <label class="form-label">IFSC code</label>
                        <input type="text" name="ifsc_code" id="form_ifsc_code" class="form-input">
                    </div>
                    <div class="form-group">
                        <label class="form-label">PAN</label>
                        <input type="text" name="pan_number" id="form_pan" class="form-input">
                    </div>
                    <div class="form-group">
                        <label class="form-label">PF No</label>
                        <input type="text" name="pf_number" id="form_pf" class="form-input">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Payment Mode</label>
                        <select name="payment_mode" class="form-select-custom">
                            <option>Bank Transfer</option>
                            <option>Cash</option>
                            <option>Cheque</option>
                        </select>
                    </div>
                </div>

                <h3><i class="bi bi-calendar"></i> Payslip Period</h3>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Month</label>
                        <select name="month" class="form-select-custom" required>
                            <option value="">Select</option>
                            @foreach(range(1,12) as $m)
                            <option value="{{ $m }}">{{ date('F', mktime(0,0,0,$m,1)) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Year</label>
                        <input type="number" name="year" class="form-input" value="{{ date('Y') }}" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Working Days</label>
                        <input type="number" name="working_days" class="form-input">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Days Payable</label>
                        <input type="number" name="days_payable" class="form-input" required>
                    </div>
                </div>

                <h3><i class="bi bi-cash-stack"></i> Earnings</h3>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Basic</label>
                        <input type="number" name="basic" class="form-input earning-input" oninput="calcTotal()" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">HRA</label>
                        <input type="number" name="hra" class="form-input earning-input" oninput="calcTotal()">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Other Allowance</label>
                        <input type="number" name="other_allowance" class="form-input earning-input" oninput="calcTotal()">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Gross</label>
                        <input type="text" id="gross_earnings_display" class="form-input" readonly>
                    </div>
                </div>

                <h3><i class="bi bi-dash-circle"></i> Deductions</h3>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">PF</label>
                        <input type="number" name="ee_pf_contribution" class="form-input deduction-input" oninput="calcTotal()">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Tax</label>
                        <input type="number" name="prof_tax" class="form-input deduction-input" oninput="calcTotal()">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Total Deduction</label>
                        <input type="text" id="total_deductions_display" class="form-input" readonly>
                    </div>
                </div>

                <h3><i class="bi bi-wallet2"></i> Net Pay</h3>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Net Pay</label>
                        <input type="text" id="net_pay_display" class="form-input" readonly>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="button" class="btn-back" onclick="closeGenerateModal()">Cancel</button>
                    <button type="submit" class="btn-submit">Generate Payslip</button>
                </div>

            </div>
        </form>
    </div>
</div>

<script>
    setTimeout(function() {
        let a = document.getElementById('successAlert');
        if (a) a.style.display = 'none';
    }, 3000);

    function toggleMenu(btn) {
        document.querySelectorAll('.action-dropdown.show').forEach(function(d) {
            if (d !== btn.nextElementSibling) d.classList.remove('show');
        });
        btn.nextElementSibling.classList.toggle('show');
    }
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.action-menu')) {
            document.querySelectorAll('.action-dropdown.show').forEach(d => d.classList.remove('show'));
        }
    });

    function openGenerateModal(empId) {
        document.querySelectorAll('.action-dropdown.show').forEach(d => d.classList.remove('show'));

        document.getElementById('payslipForm').reset();
        document.getElementById('gross_earnings_display').value = '0.00';
        document.getElementById('total_deductions_display').value = '0.00';
        document.getElementById('net_pay_display').value = '0.00';

        fetch('{{ url("payslip/employee-data") }}/' + empId)
            .then(r => r.json())
            .then(data => {
                document.getElementById('form_employee_id').value = data.id;
                document.getElementById('show_emp_id').value = data.employee_id;
                document.getElementById('show_name').value = data.name;
                document.getElementById('show_email').value = data.email;
                document.getElementById('show_phone').value = data.phone ?? '-';
                document.getElementById('show_dept').value = data.department ?? '-';
                document.getElementById('show_joining').value = data.joining_date ?? '-';
                document.getElementById('form_bank_name').value = data.bank_name ?? '';
                document.getElementById('form_bank_account').value = data.bank_account ?? '';
                document.getElementById('form_ifsc_code').value = data.ifsc_code ?? '';
                document.getElementById('form_pan').value = data.pan_number ?? '';
                document.getElementById('form_pf').value = data.pf_number ?? '';
            });

        document.getElementById('generateModal').classList.add('show');
    }

    function closeGenerateModal() {
        document.getElementById('generateModal').classList.remove('show');
    }

    document.getElementById('generateModal').addEventListener('click', function(e) {
        if (e.target === this) closeGenerateModal();
    });

    function calcTotal() {
        let earnings = 0;
        document.querySelectorAll('.earning-input').forEach(i => {
            earnings += parseFloat(i.value) || 0;
        });
        let deductions = 0;
        document.querySelectorAll('.deduction-input').forEach(i => {
            deductions += parseFloat(i.value) || 0;
        });
        document.getElementById('gross_earnings_display').value = earnings.toFixed(2);
        document.getElementById('total_deductions_display').value = deductions.toFixed(2);
        document.getElementById('net_pay_display').value = (earnings - deductions).toFixed(2);
    }
</script>

@endsection