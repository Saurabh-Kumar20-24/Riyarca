<?php

namespace App\Http\Controllers;

use App\Models\BirthdayCardShown;
use App\Models\EmployeeNotification;
use App\Models\NotificationRead;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class NotificationController extends Controller
{
   

    public function index(): JsonResponse
    {
        $userId     = Auth::id();
        $userRoleId = Auth::user()->role_id;

        $query = EmployeeNotification::with('triggeredBy')
            ->unreadFor($userId)
            ->where(function ($q) use ($userId) {
                $q->where('type', '!=', 'birthday')
                    ->orWhere('triggered_by', '!=', $userId);
            });

        if (!in_array($userRoleId, [1, 9, 2])) {
            $query->whereNotIn('type', ['leave', 'new_joinee']);
        }

        if ($userRoleId == 2) {
            $myEmployeeIds = User::where('assigned_manager', $userId)->pluck('id')->toArray();

            $query->where(function ($q) use ($myEmployeeIds) {
                $q->where('type', '!=', 'leave')
                    ->orWhere(function ($q2) use ($myEmployeeIds) {
                        $q2->where('type', 'leave')
                            ->whereIn('triggered_by', $myEmployeeIds);
                    });
            });
        }

        $notifications = $query->orderByDesc('created_at')
            ->limit(20)
            ->get()
            ->map(fn($n) => [
                'id'         => $n->id,
                'type'       => $n->type,
                'title'      => $n->title,
                'message'    => $n->message,
                'icon'       => $n->iconClass(),
                'color'      => $n->colorClass(),
                'created_at' => $n->created_at->diffForHumans(),
            ]);

        return response()->json([
            'notifications' => $notifications,
            'unread_count'  => $notifications->count(),
        ]);
    }

    public function markRead(int $id): JsonResponse
    {
        NotificationRead::updateOrCreate(
            ['notification_id' => $id, 'user_id' => Auth::id()],
            ['is_read' => true, 'read_at' => now()]
        );

        return response()->json(['success' => true]);
    }

    public function markAllRead(): JsonResponse
    {
        $userId = Auth::id();

        $ids = EmployeeNotification::unreadFor($userId)->pluck('id');

        foreach ($ids as $id) {
            NotificationRead::updateOrCreate(
                ['notification_id' => $id, 'user_id' => $userId],
                ['is_read' => true, 'read_at' => now()]
            );
        }

        return response()->json(['success' => true]);
    }


    public function checkBirthdayCard(): JsonResponse
    {
        $user  = Auth::user();
        $today = Carbon::today()->toDateString();

        $isBirthday = $user->dob
            && Carbon::parse($user->dob)->format('m-d') === now()->format('m-d');

        if (! $isBirthday) {
            return response()->json(['show_card' => false]);
        }

        $alreadyShown = DB::table('birthday_card_shown')
            ->where('user_id', $user->id)
            ->where('shown_date', $today)
            ->exists();

        if ($alreadyShown) {
            return response()->json(['show_card' => false]);
        }

        DB::table('birthday_card_shown')->insertOrIgnore([
            'user_id'    => $user->id,
            'shown_date' => $today,
        ]);

        return response()->json([
            'show_card' => true,
            'name'      => $user->name,
        ]);
    }


    public static function notifyNewJoinee($employee): void
    {
        EmployeeNotification::broadcast(
            type: 'new_joinee',
            title: 'New Team Member Joined! 🎉',
            message: "{$employee->name} has joined the team as {$employee->designation}. Give them a warm welcome!",
            triggeredBy: $employee->id,
            meta: ['department' => $employee->department ?? null],
        );
    }


    public static function notifyLeave($leave, $employee): void
    {
        $from = Carbon::parse($leave->from_date)->format('d M');
        $to   = Carbon::parse($leave->to_date)->format('d M');

        EmployeeNotification::broadcast(
            type: 'leave',
            title: 'Leave Applied',
            message: "{$employee->name} has applied for leave from {$from} to {$to} ({$leave->leave_type}).",
            triggeredBy: $employee->id,
            meta: [
                'from'       => $leave->from_date,
                'to'         => $leave->to_date,
                'leave_type' => $leave->leave_type,
            ],
        );
    }
}
