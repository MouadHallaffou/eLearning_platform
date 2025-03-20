<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Interfaces\UserRepositoryInterface;

class UserRepository implements UserRepositoryInterface
{
    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function createUser(array $data)
    {
        $data['password'] = Hash::make($data['password']);
        return $this->user->create($data);
    }

    public function findUserByEmail($email)
    {
        return $this->user->where('email', $email)->first();
    }

    public function updateUser($id, array $data)
    {
        $user = $this->user->find($id);

        if (!$user) {
            return null; 
        }
        if (isset($data['photo']) && $data['photo'] instanceof UploadedFile) {
            if ($user->photo) {
                Storage::disk('public')->delete($user->photo);
            }
                $path = $data['photo']->store('profiles', 'public');
            $data['photo'] = $path;
        } else {
            unset($data['photo']);
        }
    
        $user->update($data);
    
        return $user;
    }
}