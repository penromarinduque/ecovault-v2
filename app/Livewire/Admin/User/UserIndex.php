<?php

namespace App\Livewire\Admin\User;

use App\Models\User;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class UserIndex extends Component
{
    use WithPagination, WithoutUrlPagination;

    protected $paginationTheme = 'bootstrap';

    public $search = '';
    public User $selectedUser;

    public function editRole(User $user)
    {
        Log::info('Editing role for user: ' . $user);
        $this->selectedUser = $user;
    }

    public function resetSelectedUser()
    {
        $this->selectedUser = new User();
    }

    // Reset pagination when search changes
    public function updatingSearch()
    {
        Log::info('Search updated: ' . $this->search);
        $this->resetPage();
    }


    public function render()
    {
        $users = User::query()
        ->with('roles', 'roles.roleType')
            ->where('name', 'like', '%' . $this->search . '%')
            ->orWhere('email', 'like', '%' . $this->search . '%')
            ->paginate(5);

        return view('livewire.admin.user.user-index', compact('users'));
    }
}
