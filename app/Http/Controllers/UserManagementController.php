<?php

namespace App\Http\Controllers;

use App\Models\UserManagement;
use App\Http\Requests\UserManagement\StoreUserRequest;
use App\Http\Requests\UserManagement\UpdateUserRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class UserManagementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        $search = $request->get('search');
        if ($search) {
            $data['user_management'] = UserManagement::where('id', 'like', "%{$search}%")->get();
        } else {
            $data['user_management'] = UserManagement::all();
        }
        return view('layouts.user_management.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('layouts.user_management.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        $validated = $request->validated();
        $validated['password'] = Hash::make($validated['password']);

        UserManagement::create($validated);

        return redirect()
            ->route('user_management.index')
            ->with('message', 'User berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(UserManagement $user_management)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        //
        $user_management = UserManagement::find($id);
        return view('layouts.user_management.edit', compact('user_management'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, UserManagement $user_management)
    {
        $validated = $request->validated();

        // Only hash password if it's provided
        if (isset($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user_management->update($validated);

        return redirect()
            ->route('user_management.index')
            ->with('message', 'User berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(UserManagement $user_management)
    {
        //
        $user_management->delete();
    }
}
