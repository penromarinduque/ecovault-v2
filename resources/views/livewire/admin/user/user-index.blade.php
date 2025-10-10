<div>
    <x-page-header title="Users" />
    <div class="d-flex justify-content-end mb-2">
        @livewire('admin.user.user-create')
    </div>
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
                                <td> <img src="https://api.dicebear.com/9.x/identicon/svg?seed={{ $user->name }}" class="rounded-circle mr-2" width="25px" alt="" >{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    <!-- Example actions -->
                                    <button class="btn btn-sm btn-outline-primary" wire:click="editRole({{$user}})" >Roles</button>
                                    <button class="btn btn-sm btn-outline-danger" wire:click="deleteUser({{$user}})" wire:confirm="Are you sure you want to delete this post?">Delete</button>
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

    {{-- @if($selectedUser) --}}
    <div class="modal fade " id="editRoleModal" tabindex="-1" aria-labelledby="editRoleModalLabel" aria-hidden="true" wire:ignore.self >
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editRoleModalLabel">Edit Roles </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <span class="font-weight-bold">User :</span> <u class="underline">{{ $selectedUser->name ?? 'N/A' }}</u><br>
                    <div class="table-responsive">
                        <div class="d-flex justify-content-end mb-2">
                            <button class="btn btn-primary">Add Role</button>
                        </div>
                        <h6 class="font-weight-bold">Roles : </h6>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th width="30px"></th>
                                    <th>Role</th>
                                    <th>Assigned</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($roleTypes as $roleType)
                                    <tr>
                                        <td>
                                            <input type="checkbox" 
                                                wire:change="toggleRole('{{ $roleType->id }}')" @if($selectedUser && $selectedUser->roles->contains('role_type_id', $roleType->id)) checked @endif>
                                        </td>
                                        <td>{{ $roleType->name }}</td>
                                        <td>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center">No roles found for this user.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    {{-- @endif --}}

    @script
    <script>
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('show-edit-role-modal', () => {
                $('#editRoleModal').modal('show');
            });

            Livewire.on('hide-edit-role-modal', () => {
                $('#editRoleModal').modal('hide');
            });
        });

        $wire.on('show-toast', (message) => {
            console.log(message);
            showToast(message.type, message.message);
        })
    </script>
    @endscript
</div>
