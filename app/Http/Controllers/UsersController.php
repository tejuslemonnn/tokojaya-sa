<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserInfo;
use Illuminate\Http\Request;
use App\DataTables\UserDataTable;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use App\Http\Requests\Account\SettingsInfoRequest;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(UserDataTable $datatable)
    {
        return $datatable->render('pages.users.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pages.users.create', [
            'roles' => Role::where('name', '<>', 'owner')->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|string|max:255',
            'password' => ['required', Password::defaults()],
            'role' => 'required|string|max:255',
        ]);

        $user = User::create([
            'username' => $validated['username'],
            'password' => Hash::make($validated['password']),
        ]);

        $user->syncRoles([$validated['role']]);

        UserInfo::create([
            'user_id' => $user->id,
            'phone' => $request->phone,
            'address' => $request->address,
        ]);

        return redirect()->intended('users')->with('success', 'Berhasil Menambahkan!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::find($id);
        return view('pages.users.detail', [
            'user' => $user,
            'roles' => Role::where('name', '<>', 'owner')->get(),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::find($id);
        return view('pages.users.edit', [
            'user' => $user,
            'roles' => Role::where('name', '<>', 'owner')->get(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update($id, SettingsInfoRequest $request)
    {
        $user = User::find($id);

        $validated = $request->validate([
            'username' => 'required|string|max:255',
            'password' => 'required|string|max:255',
            'role' => 'required|string|max:255',
        ]);

        $user->update([
            'username' => $validated['username'],
        ]);

        $user->syncRoles([$validated['role']]);

        $info = $user->info ?? new UserInfo();

        $info->fill($request->only($info->getFillable()));

        $info->user()->associate($user);

        $info->save();

        return redirect()->intended('users')->with('success', 'Berhasil Mengupdate!');
    }



    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::find($id);
        $user->delete();

        return redirect()->intended('users')->with('success', 'Berhasil Menghapus!');
    }
}
