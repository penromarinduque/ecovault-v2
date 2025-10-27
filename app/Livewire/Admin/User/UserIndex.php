<?php

namespace App\Livewire\Admin\User;

use App\Models\EncoderDesignation;
use App\Models\MainFolder;
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
    public $mainFolders;
    public $editRoleModalVisibility = true;

    public function editRole(User $user)
    {
        Log::info('Editing role for user: ' . $user);
        $this->editRoleModalVisibility = true;
        $this->selectedUser = $user;
        $this->authorize('update', $this->selectedUser);
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
        $this->authorize('update', $this->selectedUser);
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

        Role::where('user_id', $this->selectedUser->id)->delete();
        Role::create([
            'user_id' => $this->selectedUser->id,
            'role_type_id' => $role_type_id,
        ]);
        notyf()->position('y', 'top')->success('Role assigned successfully!');

        $this->selectedUser = User::with('roles', 'roles.roleType')->find($this->selectedUser->id);
    }

    public function deleteUser(User $user)
    {
        // 
        $this->authorize('delete', $user);
        Role::where('user_id', $user->id)->delete();
        $user->delete();
        notyf()->position('y', 'top')->success('User deleted successfully!');
    }   

    public function render()
    {
        $this->authorize('view-any', User::class);
        $users = User::query()
        ->with('roles', 'roles.roleType')
            ->where('name', 'like', '%' . $this->search . '%')
            ->orWhere('email', 'like', '%' . $this->search . '%')
            ->paginate(10);
        return view('livewire.admin.user.user-index', compact('users'));
    }

    public function toggleEncoderDesignation($user_id, $main_folder_id) {
        $this->authorize('update', $this->selectedUser);
        $designation = EncoderDesignation::where('user_id', $user_id)->where('main_folder_id', $main_folder_id)->first();
        if($designation) {
            $designation->delete();
            notyf()->position('y', 'top')->success('Designation removed successfully!');
            return;
        }
        EncoderDesignation::create([
            'user_id' => $user_id,
            'main_folder_id' => $main_folder_id,
        ]);
        notyf()->position('y', 'top')->success('Designation added successfully!');
    }

    public function mount(){
        $this->roleTypes = RoleType::all();
        $this->mainFolders = MainFolder::all();
    }
}
