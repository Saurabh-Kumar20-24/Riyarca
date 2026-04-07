@include('layouts.header')
<link rel="stylesheet" href="{{ asset('assets/css/tableForm.css') }}">

<div class="page-header">
    <h2>✅ Accepted Candidates</h2>
    <div class="header-actions">
        <form method="GET" action="{{ route('hiring.accepted') }}" class="d-flex gap-2">
            <input type="text" name="search" class="search-input"
                   placeholder="Search name..."
                   value="{{ request('search') }}">
            <button type="submit" class="btn-search">Search</button>
            <a href="{{ route('hiring.accepted') }}"
               class="btn-search" style="background:#475569;">Reset</a>
        </form>
    </div>
</div>

{{-- Table --}}
<div class="table-card">
    <table>
        <thead>
            <tr>
                <th>S.No.</th>
                <th>Name</th>
                <th>Progress</th>
                <th>Resume</th>
            </tr>
        </thead>
        <tbody>
            @forelse($jobSeekers as $i => $seeker)
            <tr>
                {{-- S.No --}}
                <td>
                    {{ $jobSeekers->firstItem() + $i }}
                </td>

                {{-- Name + Position --}}
                <td>
                    <div>
                        {{ $seeker->name }}
                    </div>
                    <div>
                        {{ $seeker->position->title ?? '-' }}
                        @if($seeker->position->department ?? false)
                            &bull; {{ $seeker->position->department }}
                        @endif
                    </div>
                </td>

                {{-- Progress --}}
                <td style="min-width:280px;">
                    @php
                        $stages = ['applied', 'screening', 'interview', 'decision'];
                        $currentStage = $seeker->stage ?? 'applied';
                        $currentIndex = array_search($currentStage, $stages);
                        $labels = ['Applied', 'Screening', 'Interview', 'Decision'];
                    @endphp

                    <div class="progress-stages">
                        @foreach($stages as $idx => $stage)
                            @php
                                $isDone   = $idx < $currentIndex;
                                $isActive = $idx === $currentIndex;
                            @endphp
                            <div class="stage-item">
                                {{-- Dot --}}
                                <div class="stage-dot
                                    {{ $isDone   ? 'stage-done'   : '' }}
                                    {{ $isActive ? 'stage-active' : '' }}">
                                    @if($isDone)
                                        <i class="bi bi-check" style="font-size:10px;"></i>
                                    @endif
                                </div>
                                {{-- Label --}}
                                <div class="stage-label
                                    {{ $isDone   ? 'label-done'   : '' }}
                                    {{ $isActive ? 'label-active' : '' }}">
                                    {{ $labels[$idx] }}
                                </div>
                            </div>

                            {{-- Connector line between dots --}}
                            @if(!$loop->last)
                                <div class="stage-line {{ $isDone ? 'line-done' : '' }}"></div>
                            @endif
                        @endforeach
                    </div>
                </td>

                {{-- Resume --}}
                <td>
                    @if($seeker->resume_path)
                        <a href="{{ asset('' . $seeker->resume_path) }}"
                           target="_blank" class="resume-btn">
                            <i class="bi bi-file-earmark-text"></i> View
                        </a>
                    @else
                        <span>—</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td>
                    No accepted candidates found.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div style="margin-top:20px;">{{ $jobSeekers->links() }}</div>

<style>
/* ── Progress Stages ─────────────────────────────── */
.progress-stages {
    display: flex;
    align-items: center;
    gap: 0;
    padding: 6px 0;
}

/* Connector line */
.stage-line {
    flex: 1;
    height: 2px;
    background: rgba(255,255,255,.1);
    transition: background .3s;
}
.stage-line.line-done {
    background: #10b981;
}

/* Stage item */
.stage-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 5px;
}

/* Dot */
.stage-dot {
    width: 24px;
    height: 24px;
    border-radius: 50%;
    background: rgba(255,255,255,.08);
    border: 2px solid rgba(255,255,255,.15);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 10px;
    color: #fff;
    transition: all .3s;
    flex-shrink: 0;
}
.stage-dot.stage-done {
    background: #10b981;
    border-color: #10b981;
    color: #fff;
}
.stage-dot.stage-active {
    background: rgba(59,130,246,.3);
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59,130,246,.15);
}

/* Label */
.stage-label {
    font-size: 10px;
    font-weight: 600;
    letter-spacing: .3px;
    color: rgba(255,255,255,.25);
    white-space: nowrap;
}
.stage-label.label-done   { color: #10b981; }
.stage-label.label-active { color: #60a5fa; }

/* ── Resume Button ───────────────────────────────── */
.resume-btn {
    background: rgba(59,130,246,.15);
    color: #60a5fa;
    border: 1px solid rgba(59,130,246,.3);
    padding: 4px 12px;
    border-radius: 8px;
    font-size: 12px;
    font-weight: 600;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 5px;
    transition: background .2s;
}
.resume-btn:hover {
    background: rgba(59,130,246,.3);
    color: #fff;
}
</style>

@include('layouts.footer')