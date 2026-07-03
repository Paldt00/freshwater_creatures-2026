@extends('layouts.public')

@section('title', 'Ajukan Data Ikan')

@section('content')
<div class="container">
    <div class="section-head">
        <div>
            <h2>Ajukan Data Ikan</h2>
            <p>Kirim usulan penambahan atau perubahan data ikan air tawar.</p>
        </div>

        <a href="{{ route('creature-requests.index') }}" class="btn secondary">Request Saya</a>
    </div>

    <div class="form-card">
        @if($errors->any())
            <div class="alert danger">
                Data belum valid. Silakan periksa kembali formulir yang diisi.
            </div>
        @endif

        <form action="{{ route('creature-requests.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <h2>Informasi Request</h2>

            <div class="form-grid">
                <div class="field">
                    <label for="request_type">Jenis Request</label>
                    <select name="request_type" id="request_type" required>
                        <option value="add" @selected(old('request_type', 'add') === 'add')>Tambah Ikan Baru</option>
                        <option value="update" @selected(old('request_type') === 'update')>Ubah Data Ikan Lama</option>
                    </select>
                    @error('request_type')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="field" id="fish_field">
                    <label for="fish_id">Ikan yang Ingin Diubah</label>
                    <select name="fish_id" id="fish_id">
                        <option value="">Pilih ikan</option>
                        @foreach($fishes as $fish)
                            <option
                                value="{{ $fish->id }}"
                                data-url="{{ route('creature-requests.fish-data', $fish->id) }}"
                                @selected(old('fish_id') == $fish->id)
                            >
                                {{ $fish->name }} - {{ $fish->region?->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('fish_id')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="field">
                    <label for="region_id">Wilayah</label>
                    <select name="region_id" id="region_id" required>
                        <option value="">Pilih wilayah</option>
                        @foreach($regions as $region)
                            <option value="{{ $region->id }}" @selected(old('region_id') == $region->id)>
                                {{ $region->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('region_id')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="field">
                    <label for="category_id">Kategori</label>
                    <select name="category_id" id="category_id" required>
                        <option value="">Pilih kategori</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" @selected(old('category_id') == $category->id)>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <h2 style="margin-top: 30px;">Identitas Ikan</h2>

            <div class="form-grid">
                <div class="field">
                    <label for="name">Nama Ikan</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required>
                    @error('name')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="field">
                    <label for="scientific_name">Nama Ilmiah</label>
                    <input type="text" name="scientific_name" id="scientific_name" value="{{ old('scientific_name') }}">
                    @error('scientific_name')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="field full">
                    <label for="image">Gambar Ikan</label>
                    <input type="file" name="image" id="image" accept="image/*">
                    @error('image')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <h2 style="margin-top: 30px;">Informasi Detail</h2>

            <div class="form-grid">
                <div class="field">
                    <label for="habitat">Habitat</label>
                    <input type="text" name="habitat" id="habitat" value="{{ old('habitat') }}">
                    @error('habitat')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="field">
                    <label for="food">Makanan</label>
                    <input type="text" name="food" id="food" value="{{ old('food') }}">
                    @error('food')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="field full">
                    <label for="characteristics">Ciri-ciri</label>
                    <textarea name="characteristics" id="characteristics">{{ old('characteristics') }}</textarea>
                    @error('characteristics')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="field full">
                    <label for="description">Deskripsi</label>
                    <textarea name="description" id="description">{{ old('description') }}</textarea>
                    @error('description')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="field full">
                    <label for="request_note">Catatan untuk Admin</label>
                    <textarea name="request_note" id="request_note">{{ old('request_note') }}</textarea>
                    @error('request_note')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div style="margin-top: 26px; display: flex; gap: 10px; flex-wrap: wrap;">
                <button type="submit" class="btn">Kirim Request</button>
                <a href="{{ route('home') }}" class="btn secondary">Kembali</a>
            </div>
        </form>
    </div>
</div>

<script>
    const requestType = document.getElementById('request_type');
    const fishField = document.getElementById('fish_field');
    const fishSelect = document.getElementById('fish_id');

    const regionInput = document.getElementById('region_id');
    const categoryInput = document.getElementById('category_id');
    const nameInput = document.getElementById('name');
    const scientificNameInput = document.getElementById('scientific_name');
    const habitatInput = document.getElementById('habitat');
    const foodInput = document.getElementById('food');
    const characteristicsInput = document.getElementById('characteristics');
    const descriptionInput = document.getElementById('description');

    function toggleFishField() {
        if (requestType.value === 'update') {
            fishField.classList.remove('hidden');
            fishSelect.setAttribute('required', 'required');
        } else {
            fishField.classList.add('hidden');
            fishSelect.removeAttribute('required');
            fishSelect.value = '';
        }
    }

    async function fillFishData() {
        const selected = fishSelect.options[fishSelect.selectedIndex];

        if (! selected || ! selected.dataset.url) {
            return;
        }

        const response = await fetch(selected.dataset.url);

        if (! response.ok) {
            return;
        }

        const fish = await response.json();

        regionInput.value = fish.region_id ?? '';
        categoryInput.value = fish.category_id ?? '';
        nameInput.value = fish.name ?? '';
        scientificNameInput.value = fish.scientific_name ?? '';
        habitatInput.value = fish.habitat ?? '';
        foodInput.value = fish.food ?? '';
        characteristicsInput.value = fish.characteristics ?? '';
        descriptionInput.value = fish.description ?? '';
    }

    requestType.addEventListener('change', toggleFishField);
    fishSelect.addEventListener('change', fillFishData);

    toggleFishField();

    @if(old('request_type') === 'update' && old('fish_id'))
        fillFishData();
    @endif
</script>
@endsection
