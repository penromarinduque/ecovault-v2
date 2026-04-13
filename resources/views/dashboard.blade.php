<x-layouts.app :title="__('Dashboard')">
    <div class="row">
        <div class="col-lg-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-7">
                            <i class="fa-regular fa-folder font-20 text-info"></i>
                            <p class="font-16 m-b-5">Folders</p>
                        </div>
                        <div class="col-5">
                            <h1 class="font-light text-right mb-0">{{ $counts['folders'] }}</h1>
                        </div>
                    </div>
                </div>
            </div>                        
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-7">
                            <i class="fa-regular fa-file font-20 text-success"></i>
                            <p class="font-16 m-b-5">Files</p>
                        </div>
                        <div class="col-5">
                            <h1 class="font-light text-right mb-0">{{ $counts['files'] }}</h1>
                        </div>
                    </div>
                </div>
            </div>                        
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-7">
                            <i class="fa-regular fa-floppy-disk font-20 text-purple"></i>
                            <p class="font-16 m-b-5">Storage Used</p>
                        </div>
                        <div class="col-5">
                            <h1 class="font-light text-right mb-0">{{ $counts['storage_used'] }}</h1>
                        </div>
                    </div>
                </div>
            </div>                        
        </div>
        
        {{-- <div class="col-lg-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-7">
                            <i class="mdi mdi-poll font-20 text-danger"></i>
                            <p class="font-16 m-b-5">New Sales</p>
                        </div>
                        <div class="col-5">
                            <h1 class="font-light text-right mb-0">236</h1>
                        </div>
                    </div>
                </div>
            </div>                        
        </div> --}}
    </div>

</x-layouts.app>
