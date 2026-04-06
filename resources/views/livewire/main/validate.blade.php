<div class="py-5">
    
    <h1 class="text-center">{{ env('APP_NAME') }}</h1>
    @if ($file)
    <div class="card border-2">
        <div class="card-body">
            <div class="row justify-content-center align-items-center">
                <div class="col col-sm-3 col-md-3 col-lg-2 ">
                    <img style="width: 100%" src="{{ asset('assets/images/undraw_document-ready_o5d5.png')}}" alt="">
                </div>
            </div>
            <h4>Document Details</h4>
            <div class="row">
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="">
                        <h6 class="mb-0 small">Document Name</h6>
                        <p>{{ $file->name }}</p>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="">
                        <h6 class="mb-0 small">Barcode No.</h6>
                        <p>{{ $file->barcode_no }}</p>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="">
                        <h6 class="mb-0 small">Date Released</h6>
                        <p>{{ $file->date_released->format('M d, Y') }}</p>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="">
                        <h6 class="mb-0 small">Office Source</h6>
                        <p>{{ $file->office_source }}</p>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="">
                        <h6 class="mb-0 small">Released To</h6>
                        <p>{{ $file->released_to }}</p>
                    </div>
                </div>
            </div>
            <hr>
            <h4>Document Preview</h4>
            <div class="">
                <div class="" style="overflow-x: scroll">
                    <iframe style="width: 100%;; height: 1000px; min-width: 800px" src="{{ route("preview", ["id" => $file->id])}}" frameborder="0"></iframe>
                </div>
            </div>
        </div>
    </div>
    @else
    <div class="card">
        <div class="card-body">
            <div class="row justify-content-center align-items-center">
                <div class="col col-md-6 ">
                    <img style="width: 100%" src="{{ asset("assets/images/undraw_page-not-found_6wni.png") }}" alt="">
                    Document with barcode #{{ $barcode_no }} not found. Please check the barcode or contact our support team if you need further assistance.
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
