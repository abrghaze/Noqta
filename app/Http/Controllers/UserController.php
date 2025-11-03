<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\ParentProfile;
use App\Models\ClassRoom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = User::query();
        
        // Filter by role if provided
        if ($request->has('role') && $request->role) {
            $query->where('role', $request->role);
        }
        
        // Search functionality
        if ($request->has('search') && $request->search) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }
        
        $users = $query->latest()->paginate(15);
        
        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $classes = ClassRoom::all();
        $parents = ParentProfile::with('user')->get();
        
        return view('director.users.create', compact('classes', 'parents'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:etudiant,enseignant,parent,directeur',
            'phone' => 'nullable|string|max:20',
            // Student-specific
            'matricule' => 'required_if:role,etudiant|nullable|string|unique:students,matricule',
            'class_id' => 'required_if:role,etudiant|nullable|exists:class_rooms,id',
            'date_of_birth' => 'nullable|date',
            'address' => 'nullable|string',
            'parent_id' => 'nullable|exists:parent_profiles,id',
            // Parent-specific
            'relationship' => 'nullable|string|max:50',
        ]);

        // Create user
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'phone' => $validated['phone'] ?? null,
        ]);

        // Create role-specific profile
        if ($validated['role'] === 'etudiant') {
            Student::create([
                'user_id' => $user->id,
                'matricule' => $validated['matricule'],
                'class_id' => $validated['class_id'],
                'date_of_birth' => $validated['date_of_birth'] ?? null,
                'address' => $validated['address'] ?? null,
                'parent_id' => $validated['parent_id'] ?? null,
            ]);
        } elseif ($validated['role'] === 'parent') {
            ParentProfile::create([
                'user_id' => $user->id,
                'relationship' => $validated['relationship'] ?? null,
            ]);
        }

        return redirect()->route('directeur.users.index')
            ->with('success', 'Utilisateur créé avec succès!');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        $user->load(['student.class', 'student.grades.subject', 'student.attendance', 'parentProfile.students']);
        
        return view('director.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $classes = ClassRoom::all();
        $parents = ParentProfile::with('user')->get();
        
        return view('director.users.edit', compact('user', 'classes', 'parents'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|in:etudiant,enseignant,parent,directeur',
            'phone' => 'nullable|string|max:20',
            // Student-specific
            'matricule' => 'required_if:role,etudiant|nullable|string|unique:students,matricule,' . ($user->student->id ?? 'NULL'),
            'class_id' => 'required_if:role,etudiant|nullable|exists:class_rooms,id',
            'date_of_birth' => 'nullable|date',
            'address' => 'nullable|string',
            'parent_id' => 'nullable|exists:parent_profiles,id',
            // Parent-specific
            'relationship' => 'nullable|string|max:50',
        ]);

        // Update user
        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
            'phone' => $validated['phone'] ?? null,
        ]);

        // Update password if provided
        if (!empty($validated['password'])) {
            $user->update(['password' => Hash::make($validated['password'])]);
        }

        // Update or create role-specific profile
        if ($validated['role'] === 'etudiant') {
            Student::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'matricule' => $validated['matricule'],
                    'class_id' => $validated['class_id'],
                    'date_of_birth' => $validated['date_of_birth'] ?? null,
                    'address' => $validated['address'] ?? null,
                    'parent_id' => $validated['parent_id'] ?? null,
                ]
            );
        } elseif ($validated['role'] === 'parent') {
            ParentProfile::updateOrCreate(
                ['user_id' => $user->id],
                ['relationship' => $validated['relationship'] ?? null]
            );
        }

        return redirect()->route('directeur.users.index')
            ->with('success', 'Utilisateur mis à jour avec succès!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        // Prevent deleting own account
        if ($user->id === auth()->id()) {
            return redirect()->route('directeur.users.index')
                ->with('error', 'Vous ne pouvez pas supprimer votre propre compte!');
        }

        // Delete related profiles first
        if ($user->student) {
            $user->student->delete();
        }
        if ($user->parentProfile) {
            $user->parentProfile->delete();
        }

        $user->delete();

        return redirect()->route('directeur.users.index')
            ->with('success', 'Utilisateur supprimé avec succès!');
    }
}
