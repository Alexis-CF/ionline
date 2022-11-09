<?php

namespace App\Http\Controllers\Rem;

use App\Models\Rem\UserRem;
use App\Http\Controllers\Controller;
use App\Establishment;
use App\User;
use Illuminate\Http\Request;

class UserRemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $usersRem = UserRem::All();
        return view('rem.user.index', compact('usersRem'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $establishments = Establishment::orderBy('name', 'ASC')->get();
        $users = User::orderBy('name', 'ASC')->get();
        return view('rem.user.create', compact('establishments','users'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreUserRemRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $userRem = new UserRem($request->all());
        $userRem->save();
        //$user = UserRem
        $userRem->user->givePermissionTo('Rem: user');
        session()->flash('info', 'El usuario ' . $userRem->fullname . ' ha sido creado como usuario REM');
        return redirect()->route('rem.users.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Rem\UserRem  $userRem
     * @return \Illuminate\Http\Response
     */
    public function show(UserRem $userRem)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Rem\UserRem  $userRem
     * @return \Illuminate\Http\Response
     */
    public function edit(UserRem $userRem)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateUserRemRequest  $request
     * @param  \App\Models\Rem\UserRem  $userRem
     * @return \Illuminate\Http\Response
     */
    public function update(UserRem $request, UserRem $userRem)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Rem\UserRem  $userRem
     * @return \Illuminate\Http\Response
     */
    public function destroy(UserRem $userRem)
    {
        //
        $userRem->delete();
        $userRem->user->revokePermissionTo(['Rem: user']);        
        session()->flash('success', 'Usuario Eliminado de sus funciones como REM');
        return redirect()->route('rem.users.index');
    }
}
