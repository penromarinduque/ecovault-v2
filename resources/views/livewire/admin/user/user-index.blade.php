<div class="container-fluid py-4">
    <x-page-header title="Users" />

    <div class="card">
        <div class="card-body">
            <input wire:model.live.debounce.300ms="search" type="text" class="form-control mb-3" placeholder="Search users...">

            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="">
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($users as $user)
                            <tr>    
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    <!-- Example actions -->
                                    <button class="btn btn-sm btn-outline-primary" wire:click="editRole({{$user}})" onclick="$('#editRoleModal').modal('show');">Roles</button>
                                    <button class="btn btn-sm btn-outline-danger">Delete</button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center">No users found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination links -->
            <div class="d-flex justify-content-center mt-3">
                {{ $users->links() }}
            </div>
        </div>
    </div>

    @if($selectedUser)
    <div class="modal fade" id="editRoleModal" tabindex="-1" aria-labelledby="editRoleModalLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editRoleModalLabel">Edit Roles </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <span class="font-weight-bold">User :</span> <u class="underline">{{ $selectedUser->name ?? 'N/A' }}</u>
                    <h6 class="font-weight-bold">Roles</h6>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Role</th>
                                <th>Assigned</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($selectedUser->roles as $role)
                                <tr>
                                    <td>{{ $role->roleType->name }}</td>
                                    <td>
                                        {{-- <input type="checkbox" 
                                               wire:change="toggleRole('{{ $role->name }}')" 
                                               @if($selectedUser && $selectedUser->hasRole($role->name)) checked @endif> --}}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="text-center">No roles found for this user.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
