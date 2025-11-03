@extends('layouts.app')

@section('title', 'Présences de ' . ($child->user->name ?? 'mon enfant'))

@section('content')
<div class="container-fluid px-4">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Accueil</a></li>
            <li class="breadcrumb-item active">Présences de {{ $child->user->name }}</li>
        </ol>
    </nav>

    <!-- Child Selector -->
    @if(isset($children) && $children->count() > 1)
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <label class="form-label"><i class="fas fa-child"></i> Sélectionner un enfant:</label>
                <select class="form-select" onchange="window.location.href='/parent/children/' + this.value + '/attendance'">
                    @foreach($children as $c)
                        <option value="{{ $c->id }}" {{ $child->id == $c->id ? 'selected' : '' }}>
                            {{ $c->user->name }} - {{ $c->classRoom->name ?? 'Non assigné' }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
    @endif

    <h1 class="h3 mb-4"><i class="fas fa-calendar-check"></i> Présences de {{ $child->user->name }}</h1>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-white shadow-sm" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);">
                <div class="card-body text-center">
                    <h2 class="display-4 mb-0">{{ number_format($attendanceRate, 1) }}%</h2>
                    <p class="mb-0">Taux de Présence</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-info shadow-sm">
                <div class="card-body text-center">
                    <h2 class="display-4 mb-0">{{ $attendance->count() }}</h2>
                    <p class="mb-0">Total Jours</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-success shadow-sm">
                <div class="card-body text-center">
                    <h2 class="display-4 mb-0">{{ $attendance->where('status', 'present')->count() }}</h2>
                    <p class="mb-0">Jours Présents</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-danger shadow-sm">
                <div class="card-body text-center">
                    <h2 class="display-4 mb-0">{{ $attendance->where('status', 'absent')->count() }}</h2>
                    <p class="mb-0">Jours Absents</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Attendance Table -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="fas fa-list"></i> Historique des Présences</h5>
        </div>
        <div class="card-body">
            @if($attendance->isNotEmpty())
                <div class="table-responsive">
                    <table class="table table-hover table-striped">
                        <thead class="table-dark">
                            <tr>
                                <th>Date</th>
                                <th>Matière</th>
                                <th>Statut</th>
                                <th>Raison</th>
                                <th>Justification Parent</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($attendance as $record)
                                <tr>
                                    <td>{{ $record->date->format('d/m/Y') }}</td>
                                    <td><strong>{{ $record->subject->name }}</strong></td>
                                    <td>
                                        <span class="badge fs-6 bg-{{ $record->status == 'present' ? 'success' : 'danger' }}">
                                            <i class="fas fa-{{ $record->status == 'present' ? 'check' : 'times' }}"></i>
                                            {{ $record->status == 'present' ? 'Présent' : 'Absent' }}
                                        </span>
                                    </td>
                                    <td>{{ $record->reason ?? '-' }}</td>
                                    <td>
                                        @if($record->parent_justification)
                                            <span class="text-success">
                                                <i class="fas fa-check-circle"></i> {{ $record->parent_justification }}
                                            </span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($record->status == 'absent' && !$record->parent_justification)
                                            <button class="btn btn-sm btn-warning" 
                                                    onclick="openJustificationModal({{ $record->id }})">
                                                <i class="fas fa-comment"></i> Justifier
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-calendar-times fa-4x text-muted mb-3"></i>
                    <p class="text-muted">Aucun enregistrement de présence</p>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Justification Modal -->
<div class="modal fade" id="justificationModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning text-white">
                <h5 class="modal-title">Ajouter une Justification</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="#" id="justificationForm">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label"><strong>Votre justification:</strong></label>
                        <textarea class="form-control" name="parent_justification" rows="4" 
                                  placeholder="Expliquez la raison de l'absence..." required></textarea>
                    </div>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> Cette justification sera visible par l'administration et l'enseignant.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-save"></i> Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function openJustificationModal(attendanceId) {
    const form = document.getElementById('justificationForm');
    form.action = `/parent/attendance/${attendanceId}/justify`;
    new bootstrap.Modal(document.getElementById('justificationModal')).show();
}
</script>
@endpush
@endsection
