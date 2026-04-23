<?php

namespace App\Http\Controllers;

use App\Models\Payslip;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Carbon\Carbon;
// use setasign\Fpdi\Fpdi;
use setasign\Fpdi\PdfReader;
use setasign\Fpdi\Fpdi as BaseFpdi;
use TCPDF;
use setasign\Fpdi\Tcpdf\Fpdi;
use Illuminate\Support\Facades\Auth;




class PayslipController extends Controller
{
    public function index(Request $request)
    {
       $user = Auth::user(); 
    if (!in_array($user->role_id, [1,  9])) {
        return redirect()->route('payslip.previous', $user->id);
    }

        $query = User::with('role')
            ->whereNotNull('employee_id')
            ->where('role_id', '!=', 1);

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('employee_id', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->role) {
            $query->where('role_id', $request->role);
        }

        $employees = $query->paginate(10)->withQueryString();
        $roles = \App\Models\Role::where('id', '!=', 1)->get();

        return view('payslip.index', compact('employees', 'roles'));
    }

   public function getEmployeeData($id)
{
    $employee = User::with('bankDetail')->findOrFail($id);

    return response()->json([
        'id' => $employee->id,
        'employee_id' => $employee->employee_id,
        'name' => $employee->name,
        'email' => $employee->email,
        'phone' => $employee->phone,
        'department' => $employee->department ?? null,
        'joining_date' => $employee->joining_date ?? null,

        'bank_name' => optional($employee->bankDetail)->bank_name,
        'bank_account' => optional($employee->bankDetail)->account_number,
        'ifsc_code' => optional($employee->bankDetail)->ifsc_code,
        'pan_number' => optional($employee->bankDetail)->pan_number,
        'pf_number' => optional($employee->bankDetail)->pf_number,
    ]);
}

    public function store(Request $request)
    {
        $request->validate([
            'employee_id'  => 'required|exists:users,id',
            'month'        => 'required|integer|min:1|max:12',
            'year'         => 'required|integer|min:2000|max:2100',
            'days_payable' => 'required|integer',
            'basic'        => 'required|numeric',
        ]);

        $grossEarnings = $request->basic + $request->hra
            + $request->leave_travel_allowance + $request->mobile_broadband_allowance
            + $request->research_allowance + $request->fuel_allowance
            + $request->other_allowance;

        $totalDeductions = $request->ee_pf_contribution
            + $request->prof_tax + $request->other_deduction;

        $netPay = $grossEarnings - $totalDeductions;

        Payslip::updateOrCreate(
            [
                'employee_id' => $request->employee_id,
                'month'       => $request->month,
                'year'        => $request->year,
            ],
            [
                'payment_mode'              => $request->payment_mode ?? 'Bank Transfer',
                'bank_name'                 => $request->bank_name,
                'bank_account'              => $request->bank_account,
                'pan_number'                => $request->pan_number,
                'pf_number'                 => $request->pf_number,
                'working_days'              => $request->working_days ?? 31,
                'days_payable'              => $request->days_payable,
                'days_lop'                  => $request->days_lop ?? 0,
                'basic'                     => $request->basic,
                'hra'                       => $request->hra ?? 0,
                'leave_travel_allowance'    => $request->leave_travel_allowance ?? 0,
                'mobile_broadband_allowance' => $request->mobile_broadband_allowance ?? 0,
                'research_allowance'        => $request->research_allowance ?? 0,
                'fuel_allowance'            => $request->fuel_allowance ?? 0,
                'other_allowance'           => $request->other_allowance ?? 0,
                'ee_pf_contribution'        => $request->ee_pf_contribution ?? 0,
                'prof_tax'                  => $request->prof_tax ?? 0,
                'other_deduction'           => $request->other_deduction ?? 0,
                'gross_earnings'            => $grossEarnings,
                'total_deductions'          => $totalDeductions,
                'net_pay'                   => $netPay,
                'fixed_annual_salary'       => $request->fixed_annual_salary ?? 0,
                'variable_annual_salary'    => $request->variable_annual_salary ?? 0,
                'ctc_effective_date'        => $request->ctc_effective_date,
                'created_by'                => auth()->id(),
            ]
        );

        return redirect()->route('payslip.index')
            ->with('success', 'Payslip generated successfully.');
    }

   public function previous($employeeId)
{
    $user = Auth::user(); 

    if (!in_array($user->role_id, [1,  9])) {
        abort_if($user->id != $employeeId, 403);
    }

    $employee = User::findOrFail($employeeId);
    $payslips = Payslip::where('employee_id', $employeeId)
        ->orderBy('year', 'desc')
        ->orderBy('month', 'desc')
        ->get();

    return view('payslip.previous', compact('employee', 'payslips'));
}

    public function show($id)
    {
        $payslip  = Payslip::with('employee')->findOrFail($id);
        $employee = $payslip->employee;
    if (!in_array(Auth::user()->role_id, [1,  9])) {
        abort_if($payslip->employee_id !== Auth::id(), 403);
    }
        return view('payslip.show', compact('payslip', 'employee'));
    }





public function secureDownload($id)
{
    $payslip = Payslip::with(['employee.bankDetail'])->findOrFail($id);

    $password = strtoupper(trim(optional($payslip->employee->bankDetail)->pan_number));

    if (empty($password)) {
        abort(400, 'PAN number not found.');
    }

    $dompdf = Pdf::loadView('payslip.pdf', compact('payslip'))
        ->setPaper('A4', 'portrait');

    $tempFile = storage_path('app/temp_payslip.pdf');
    file_put_contents($tempFile, $dompdf->output());

    $pdf = new Fpdi();

    $pdf->setPrintHeader(false);
    $pdf->setPrintFooter(false);
    $pdf->SetMargins(0, 0, 0);

    $pdf->SetProtection(
        ['print'], 
        $password, 
        'OWNER_' . $password . '_SEOMAGICS',
        2 // AES 128-bit
    );

    $pageCount = $pdf->setSourceFile($tempFile);

    for ($i = 1; $i <= $pageCount; $i++) {
        $tpl = $pdf->importPage($i);
        $size = $pdf->getTemplateSize($tpl);

        $pdf->AddPage($size['orientation'], [$size['width'], $size['height']]);
        $pdf->useTemplate($tpl);
    }

    $filename = 'payslip_' . $payslip->month . '_' . $payslip->year . '.pdf';

    $output = $pdf->Output('', 'S');

    if (file_exists($tempFile)) {
        unlink($tempFile);
    }

    return response($output)
        ->header('Content-Type', 'application/pdf')
        ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
}

// public function secureDownload($id)
// {
//     $payslip = Payslip::with('employee')->findOrFail($id);

//     // dd($payslip->employee->pan_number);

//     // Debug: confirm PAN is not null
//     $password = strtoupper(trim($payslip->employee->pan_number));
    
//     if (empty($password)) {
//         abort(400, 'PAN number not found for this employee.');
//     }

//     $html = view('payslip.pdf', compact('payslip'))->render();

//     $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);

//     // 🔐 Must be before AddPage — user pass = PAN, owner pass = random strong string
//     $pdf->SetProtection(
//         ['print'],           // allowed permissions
//         $password,           // user password (employee must enter this to open)
//         'OWNER_' . $password . '_SEOMAGICS', // owner password (different from user pass)
//         2,                   // encryption: 2 = AES 128-bit
//         null
//     );

//     $pdf->SetCreator('SEO Magics');
//     $pdf->SetAuthor('SEO Magics');
//     $pdf->setPrintHeader(false);
//     $pdf->setPrintFooter(false);
//     $pdf->SetMargins(10, 10, 10);
//     $pdf->AddPage();
//     $pdf->writeHTML($html, true, false, true, false, '');

//     $filename = 'payslip_' . $payslip->month . '_' . $payslip->year . '.pdf';

//     return response($pdf->Output($filename, 'S'))
//         ->header('Content-Type', 'application/pdf')
//         ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
// }
}
