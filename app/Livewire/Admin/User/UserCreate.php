<?php

namespace App\Livewire\Admin\User;

use App\Models\User;
use App\Notifications\UserCreatedNotification;
use Illuminate\Support\Facades\Notification;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Illuminate\Support\Str;

class UserCreate extends Component
{
    #[Validate('required|max:255')]
    public $name = '';

    #[Validate('required|email|unique:users,email|max:255')]
    public $email = '';


    public function showCreateUserModal(){
        $this->dispatch('show-create-user-modal');
        $this->reset(['name', 'email']);
        $this->resetErrorBag();
    }

    public function hideCreateUserModal(){
        $this->dispatch('hide-create-user-modal');
        $this->reset(['name', 'email']);
        $this->resetErrorBag();
    }

    public function saveUser(){
        $this->validate();
        $password = Str::random(8);
        $user = User::create([
            "name" => $this->name,
            "email" => $this->email,
            "password" => bcrypt($password),
        ]);
        Notification::send($user, new UserCreatedNotification($password));
        notyf()->position('y', 'top')->success('User created successfully!');
        $this->dispatch('hide-create-user-modal');
        $this->dispatch('user-created');
    }

    public function render()
    {
        return view('livewire.admin.user.user-create');
    }
}
