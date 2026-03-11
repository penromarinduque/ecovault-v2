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

    <canvas id="the-canvas" style="border: 1px solid black; direction: ltr"></canvas>
    {{-- <script src="//mozilla.github.io/pdf.js/build/pdf.mjs" type="module"></script> --}}
    <script type="module">

        import pdfjsDist from 'https://cdn.jsdelivr.net/npm/pdfjs-dist@5.5.207/+esm';
        
        $(document).ready(function() {
            var url = '{!! $fileUrl !!}';
            
            const loadingTask = pdfjsLib.getDocument(url);
            const pdf = await loadingTask.promise;
            const page = await pdf.getPage(1);
            const scale = 1.5;
            const viewport = page.getViewport({ scale });
            // Support HiDPI-screens.
            const outputScale = window.devicePixelRatio || 1;

            //
            // Prepare canvas using PDF page dimensions
            //
            const canvas = document.getElementById("the-canvas");
            const context = canvas.getContext("2d");

            canvas.width = Math.floor(viewport.width * outputScale);
            canvas.height = Math.floor(viewport.height * outputScale);
            canvas.style.width = Math.floor(viewport.width) + "px";
            canvas.style.height = Math.floor(viewport.height) + "px";

            const transform = outputScale !== 1 ? [outputScale, 0, 0, outputScale, 0, 0] : null;

            //
            // Render PDF page into canvas context
            //
            const renderContext = {
                canvasContext: context,
                transform,
                viewport,
            };
            page.render(renderContext);
        });
    </script>
</x-layouts.app>
