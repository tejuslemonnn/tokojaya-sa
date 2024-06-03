<?php

namespace App\Http\Controllers;

use DB;
use App\Models\User;
use App\Models\UserInfo;
use Illuminate\Http\Request;
use App\DataTables\UserDataTable;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Validation\Rules\Password;
use Yajra\DataTables\Contracts\DataTable;
use App\Http\Requests\Account\SettingsInfoRequest;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        return view('pages.users.index');
    }

    public function usersTable(UserDataTable $dataTable, Request $request)
    {
        $shift = $request->input('shift');

        return $dataTable->with([
            'shift' => $shift
        ])->ajax();
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
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255',
            'password' => ['required', Password::defaults()],
            'role' => 'required|string|max:255',
            'shift' => 'required',
        ]);

        $user = User::create([
            'name' => $request->name,
            'username' => $validated['username'],
            'password' => Hash::make($validated['password']),
        ]);

        $user->syncRoles([$validated['role']]);

        $info =        UserInfo::create([
            'user_id' => $user->id,
            'phone' => $request->phone,
            'address' => $request->address,
            'shift' => (int)$request->shift
        ]);

        return redirect()->intended('/users')->with('success', 'Berhasil Menambahkan!');
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

        $validated = [
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255',
            'role' => 'required|string|max:255',
            'shift' => 'required',
        ];

        if (!empty($request->password)) {
            $validated['password'] = ['required', Password::defaults()];
        }

        $validated = $request->validate($validated);

        $user->update([
            'name' => $validated['name'],
            'username' => $validated['username'],
        ]);

        if (!empty($request->password)) {
            $user->update([
                'password' => Hash::make($validated['password']),
            ]);
        }

        $user->syncRoles([$validated['role']]);

        $info = $user->info ?? new UserInfo();

        $info->update([
            'phone' => $request->phone,
            'address' => $request->address,
            'shift' => (int)$request->shift
        ]);

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
