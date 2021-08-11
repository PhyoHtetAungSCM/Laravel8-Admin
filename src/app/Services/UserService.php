<?php

namespace App\Services;

use App\Models\User;
use Throwable;

class UserService
{
    /**
     * Get all users
     *
     * @return User
     */
    public function index($request)
    {
        $users = User::when($search = request('search'), function ($query) use ($search) {
            $query->where('email', 'like', '%' . $search . '%');
        })->paginate(25);

        return compact('users');
    }

    public function store(array $attributes)
    {
        try {
            User::create($attributes);
        } catch (Throwable $th) {
            abort(500);
        }
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return compact('user');
    }

    public function update(array $attributes, $id)
    {
        $user = User::findOrFail($id);
        $user->update($attributes);
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
    }
}
