@extends('layouts/app')

@section('title', 'Unggah Properti')

@section('add-property')
<section>
    <h1>Unggah Properti</h1>
    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif
    <form action="{{ route('add-property') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div>
            <label for="property_name">Nama Properti</label>
            <input type="text" id="property_name" name="property_name">
        </div>
        <div>
            <label for="property_files">Gambar/Video Properti</label>
            <input type="file" id="property_files" name="property_files[]" multiple onchange="previewFiles(event)">
            <div id="preview"></div>
        </div>
        <div>
            <label for="property_desc">Deskripsi Properti</label>
            <textarea id="property_desc" name="property_desc"></textarea>
        </div>
        <div>
            <label for="property_location">Lokasi Properti</label>
            <input type="text" id="property_location" name="property_location">
        </div>
        <div>
            <label for="property_price">Harga</label>
            <input type="text" id="property_price" name="property_price">
        </div>
        <div>
            <label for="property_files">Dokumen Pendukung</label>
            <input type="file" id="property_files" name="property_files[]" multiple>
        </div>
        <button type="submit">Unggah Properti</button>
    </form>
</section>

<script>
    let selectedFiles = [];

    function previewFiles(event) {
        const files = event.target.files;
        const preview = document.getElementById('preview');

        for (let i = 0; i < files.length; i++) {
            selectedFiles.push(files[i]);
            const file = files[i];
            const reader = new FileReader();

            reader.onload = (function(file, index) {
                return function(e) {
                    let fileType = file.type;
                    let elementContainer = document.createElement('div');
                    elementContainer.style.display = 'inline-block';
                    elementContainer.style.position = 'relative';
                    elementContainer.style.marginRight = '10px';
                    
                    let element;
                    if (fileType.startsWith('image/')) {
                        element = document.createElement('img');
                        element.src = e.target.result;
                        element.style.maxWidth = '200px';
                    } else if (fileType.startsWith('video/')) {
                        element = document.createElement('video');
                        element.src = e.target.result;
                        element.controls = true;
                        element.style.maxWidth = '200px';
                    }

                    if (element) {
                        let removeButton = document.createElement('button');
                        removeButton.innerHTML = 'Hapus';
                        removeButton.style.position = 'absolute';
                        removeButton.style.top = '0';
                        removeButton.style.right = '0';
                        removeButton.onclick = function() {
                            elementContainer.remove();
                            selectedFiles.splice(index, 1);
                            updateFileInput();
                        };
                        elementContainer.appendChild(element);
                        elementContainer.appendChild(removeButton);
                        preview.appendChild(elementContainer);
                    }
                };
            })(file, selectedFiles.length - 1);

            reader.readAsDataURL(file);
        }
    }

    function updateFileInput() {
        const dataTransfer = new DataTransfer();
        selectedFiles.forEach(file => {
            dataTransfer.items.add(file);
        });
        document.getElementById('property_files').files = dataTransfer.files;
    }

    document.getElementById('property_files').addEventListener('click', function() {
        this.value = null;
    });
</script>
@endsection
