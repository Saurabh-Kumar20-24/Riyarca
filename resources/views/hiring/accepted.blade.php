@extends('layouts.header')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')

<div class="page-header">
    <h2>Accepted Candidates</h2>
    <div class="header-actions">
        <form method="GET" action="{{ route('hiring.accepted') }}" class="d-flex gap-2">
            <input type="text" name="search" class="search-input"
                   placeholder="Search name..."
                   value="{{ request('search') }}">
            <button type="submit" class="btn-search">Search</button>
            <a href="{{ route('hiring.accepted') }}" class="btn-search btn-search-reset">Reset</a>
        </form>
    </div>
</div>

<div class="table-card">
    <table>
        <thead>
            <tr>
                <th>S.No.</th>
                <th>Name</th>
                <th>Progress</th>
                <th>Resume</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($jobSeekers as $i => $seeker)
            <tr>

                <td>{{ $jobSeekers->firstItem() + $i }}</td>

                {{-- Name + Position --}}
                <td>
                    <div>{{ $seeker->name }}</div>
                    <div class="table-sub-text">
                        {{ $seeker->position->title ?? '-' }}
                        @if($seeker->position->department ?? false)
                            &bull; {{ $seeker->position->department }}
                        @endif
                    </div>
                </td>

                <td class="td-progress">
                    @php
                        $stages       = ['applied', 'screening', 'interview', 'decision'];
                        $currentStage = $seeker->stage ?? 'applied';
                        $currentIndex = array_search($currentStage, $stages);
                        $labels       = ['Applied', 'Screening', 'Interview', 'Decision'];
                    @endphp

                    <div class="progress-stages">
                        @foreach($stages as $idx => $stage)
                            @php
                                $isDone   = $idx < $currentIndex;
                                $isActive = $idx === $currentIndex;
                            @endphp

                            <div class="stage-item">
                                <div class="stage-dot {{ $isDone ? 'stage-done' : '' }} {{ $isActive ? 'stage-active' : '' }}">
                                    @if($isDone)
                                        <i class="bi bi-check"></i>
                                    @endif
                                </div>
                                <div class="stage-label {{ $isDone ? 'label-done' : '' }} {{ $isActive ? 'label-active' : '' }}">
                                    {{ $labels[$idx] }}
                                </div>
                            </div>

                            @if(!$loop->last)
                                <div class="stage-line {{ $isDone ? 'line-done' : '' }}"></div>
                            @endif

                        @endforeach
                    </div>
                </td>

                {{-- Resume --}}
                <td>
                    @if($seeker->resume_path)
                        <a href="{{ asset('storage/' . $seeker->resume_path) }}" target="_blank" class="resume-btn">
                            <i class="bi bi-file-earmark-text"></i> View
                        </a>
                    @else
                        <span>—</span>
                    @endif
                </td>

                {{-- Action --}}
                <td>
                    <div class="action-menu">
                        <button class="action-toggle" onclick="toggleMenu({{ $seeker->id }})">⋯</button>

                        <div class="action-dropdown" id="menu-{{ $seeker->id }}">
                            <form method="POST" action="{{ route('hiring.updateStage', $seeker->id) }}">
                                @csrf
                                @method('PATCH')
                                <button name="stage" value="applied"    class="dropdown-item">Applied</button>
                                <button name="stage" value="screening"  class="dropdown-item">Screening</button>
                                <button name="stage" value="interview"  class="dropdown-item">Interview</button>
                                <button name="stage" value="decision"   class="dropdown-item">Decision</button>
                            </form>
                        </div>
                    </div>
                </td>

            </tr>
            @empty
            <tr>
                <td colspan="5" class="table-empty">No accepted candidates found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="pagination-wrap">{{ $jobSeekers->links() }}</div>

@endsection

@push('scripts')
<script>
    function toggleMenu(id) {
        document.querySelectorAll('.action-dropdown').forEach(d => {
            if (d.id !== 'menu-' + id) d.classList.remove('show');
        });
        document.getElementById('menu-' + id).classList.toggle('show');
    }

    document.addEventListener('click', function(e) {
        if (!e.target.closest('.action-menu')) {
            document.querySelectorAll('.action-dropdown').forEach(d => d.classList.remove('show'));
        }
    });
</script>
@endpush