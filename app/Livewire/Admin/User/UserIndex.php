<?php

namespace App\Livewire\Admin\User;

use App\Models\Role;
use App\Models\RoleType;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class UserIndex extends Component
{
    use WithPagination, WithoutUrlPagination;

    protected $paginationTheme = 'bootstrap';
    protected $listeners = [
        'user-created'
    ];

    public $search = '';
    public User $selectedUser;
    public $roleTypes;
    public $editRoleModalVisibility = true;

    public function editRole(User $user)
    {
        Log::info('Editing role for user: ' . $user);
        $this->editRoleModalVisibility = true;
        $this->selectedUser = $user;
        $this->dispatch('show-edit-role-modal');
    }

    public function closeEditModal()
    {
        $this->editRoleModalVisibility = false;
        $this->resetSelectedUser();
        $this->dispatch('hide-edit-role-modal');
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

    public function toggleRole($role_type_id)
    {
        if (!$this->selectedUser) {
            notyf()->position('y', 'top')->error('User not selected!');
            return;
        }
        $this->selectedUser = User::with('roles', 'roles.roleType')->find($this->selectedUser->id);
        $roleType = RoleType::find($role_type_id);
        if (!$roleType) {
            notyf()->error('Role not found!');
            return;
        }

        if ($this->selectedUser->roles->contains('role_type_id', $role_type_id)) {
            Role::where('user_id', $this->selectedUser->id)
                ->where('role_type_id', $role_type_id)
                ->delete();
            notyf()->position('y', 'top')->success('Role removed successfully!');
        } else {
            // Assign the role to the user
            Role::where('user_id', $this->selectedUser->id)
                ->where('role_type_id', $role_type_id)
                ->firstOrCreate([
                    'user_id' => $this->selectedUser->id,
                    'role_type_id' => $role_type_id,
                ]);
            notyf()->position('y', 'top')->success('Role assigned successfully!');
        }

        // Refresh the selected user to reflect changes
        $this->selectedUser = User::with('roles', 'roles.roleType')->find($this->selectedUser->id);
    }

     public function deleteUser(User $user){
        // 
        Role::where('user_id', $user->id)->delete();
        $user->delete();
        notyf()->position('y', 'top')->success('User deleted successfully!');
    }   

    public function render()
    {
        $users = User::query()
        ->with('roles', 'roles.roleType')
            ->where('name', 'like', '%' . $this->search . '%')
            ->orWhere('email', 'like', '%' . $this->search . '%')
            ->paginate(10);

        return view('livewire.admin.user.user-index', compact('users'));
    }

    public function mount(){
        $this->roleTypes = RoleType::all();
    }
}
