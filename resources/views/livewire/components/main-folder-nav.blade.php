<ul class="sidebarnav">
    <li class="nav-small-cap">
        <i class="mdi mdi-dots-horizontal"></i>
        <span class="hide-menu">Folders</span>
    </li>
    @foreach ($mainFolders as $mainFolder)
        <li class="sidebar-item">
            <a href="{{ route('main.folders.show', ["main_folder_id" => $mainFolder->id]) }}" class="sidebar-link {{ request()->main_folder_id == $mainFolder->id ? 'active' : '' }}" wire:navigate>
                <i class="mdi mdi-folder"></i>
                <span class="hide-menu"> {{$mainFolder->name}} </span>
            </a>
        </li>
    @endforeach
</ul>