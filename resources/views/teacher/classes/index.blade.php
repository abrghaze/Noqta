@extends('layouts.modern')

@section('title', 'Mes Classes')
@section('breadcrumb', 'Mes Classes')

@section('content')
<div class="px-4 sm:px-6 lg:px-8">
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Mes Classes</h1>
        <p class="mt-2 text-sm text-gray-600">Classes que j'enseigne</p>
    </div>

    <!-- Classes Grid -->
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
        @forelse($classes as $class)
            <a href="{{ route('enseignant.classes.show', $class) }}" class="group block">
                <div class="rounded-xl bg-white p-6 shadow-sm hover:shadow-xl transition-all duration-300">
                    <!-- Class Header -->
                    <div class="flex items-center justify-between mb-4">
                        <div class="rounded-full bg-purple-100 p-3">
                            <i class="fas fa-door-open text-2xl text-purple-600"></i>
                        </div>
                        <span class="inline-flex items-center rounded-full bg-indigo-100 px-3 py-1 text-xs font-semibold text-indigo-800">
                            {{ $class->students_count }} étudiants
                        </span>
                    </div>

                    <!-- Class Name -->
                    <h3 class="text-xl font-bold text-gray-900 group-hover:text-indigo-600 transition-colors mb-2">
                        {{ $class->name }}
                    </h3>

                    <!-- Class Stats -->
                    <div class="space-y-2 mb-4">
                        <div class="flex items-center text-sm text-gray-600">
                            <i class="fas fa-book w-5 text-green-600"></i>
                            <span class="ml-2">{{ $class->subjects->count() }} matières</span>
                        </div>
                        <div class="flex items-center text-sm text-gray-600">
                            <i class="fas fa-chart-line w-5 text-blue-600"></i>
                            <span class="ml-2">
                                Moyenne: {{ number_format($class->students->flatMap->grades->avg('grade_value') ?? 0, 2) }}/20
                            </span>
                        </div>
                        <div class="flex items-center text-sm text-gray-600">
                            <i class="fas fa-calendar-check w-5 text-purple-600"></i>
                            <span class="ml-2">
                                @php
                                    $totalAtt = $class->students->flatMap->attendance->count();
                                    $presentAtt = $class->students->flatMap->attendance->where('status', 'present')->count();
                                    $rate = $totalAtt > 0 ? ($presentAtt / $totalAtt) * 100 : 0;
                                @endphp
                                Présence: {{ number_format($rate, 1) }}%
                            </span>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="pt-4 border-t border-gray-200">
                        <div class="flex items-center justify-between text-sm">
                            <span class="font-medium text-indigo-600 group-hover:text-indigo-500">Voir détails</span>
                            <i class="fas fa-arrow-right text-indigo-600 group-hover:text-indigo-500"></i>
                        </div>
                    </div>
                </div>
            </a>
        @empty
            <div class="col-span-full">
                <div class="rounded-xl bg-white p-12 text-center shadow-sm">
                    <i class="fas fa-door-open text-6xl text-gray-300 mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-900">Aucune classe assignée</h3>
                    <p class="mt-2 text-sm text-gray-500">Vous n'avez pas encore de classes assignées</p>
                </div>
            </div>
        @endforelse
    </div>
</div>
@endsection
