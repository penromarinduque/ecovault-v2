<?php

namespace App\Livewire\Admin\User;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class UserIndex extends Component
{
    use WithPagination;

    public function render()
    {
        return view('livewire.admin.user.user-index');
    }

    public function getUsersProperty()
    {
        return User::query()->paginate(10);
    }
}
