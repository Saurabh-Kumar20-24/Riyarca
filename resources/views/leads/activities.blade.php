@forelse($activities as $activity)

<div style="border-bottom:1px solid #eee;padding:10px 0">

    <strong>{{ $activity->user->name }}</strong>

    <div>
        {{ ucfirst($activity->contact_type) }}
        • {{ $activity->duration }} min
    </div>

    <div style="color:#666">
        {{ $activity->discussion }}
    </div>

    <small>
        {{ $activity->created_at->format('d M Y h:i A') }}
    </small>

</div>

@empty

<div style="text-align:center;color:#999">
    No activity yet
</div>

@endforelse