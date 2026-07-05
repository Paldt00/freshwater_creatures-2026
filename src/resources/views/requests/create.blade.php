@extends('layouts.public')

@section('title', 'Ajukan Data Ikan')

@section('content')
@php
    $selectedRequestType = old(
        'request_type',
        request('request_type', 'add')
    );

    $selectedFishId = old(
        'fish_id',
        request('fish_id')
    );
@endphp

<div class="container">
    <div class="section-head">
        <div>
            <h2>Ajukan Data Ikan</h2>

            <p>
                Kirim usulan penambahan atau perubahan data ikan air tawar.
            </p>
        </div>

        <a
            href="{{ route('creature-requests.index') }}"
            class="btn secondary"
        >
            Pengajuan Saya
        </a>
    </div>

    <div class="form-card">
        @if($errors->any())
            <div class="alert danger">
                Data belum valid. Silakan periksa kembali formulir yang diisi.
            </div>
        @endif

        <form
            action="{{ route('creature-requests.store') }}"
            method="POST"
            enctype="multipart/form-data"
        >
            @csrf

            <h2>Informasi Pengajuan</h2>

            <div class="form-grid">
                <div class="field">
                    <label for="request_type">
                        Jenis Pengajuan
                    </label>

                    <select
                        name="request_type"
                        id="request_type"
                        required
                    >
                        <option
                            value="add"
                            @selected($selectedRequestType === 'add')
                        >
                            Tambah Ikan Baru
                        </option>

                        <option
                            value="update"
                            @selected($selectedRequestType === 'update')
                        >
                            Ubah Data Ikan Lama
                        </option>
                    </select>

                    @error('request_type')
                        <span class="error">
                            {{ $message }}
                        </span>
                    @enderror
                </div>

                <div class="field" id="fish_field">
                    <label for="fish_id">
                        Ikan yang Ingin Diubah
                    </label>

                    <select
                        name="fish_id"
                        id="fish_id"
                    >
                        <option value="">
                            Pilih ikan
                        </option>

                        @foreach($fishes as $fish)
                            <option
                                value="{{ $fish->id }}"
                                data-url="{{ route(
                                    'creature-requests.fish-data',
                                    $fish->id
                                ) }}"
                                @selected(
                                    (string) $selectedFishId
                                    === (string) $fish->id
                                )
                            >
                                {{ $fish->name }}
                                -
                                {{ $fish->region?->name ?? '-' }}
                            </option>
                        @endforeach
                    </select>

                    <small
                        id="fish_helper"
                        style="color: var(--muted);"
                    >
                        Pilih ikan lama agar seluruh data otomatis dimasukkan ke formulir.
                    </small>

                    @error('fish_id')
                        <span class="error">
                            {{ $message }}
                        </span>
                    @enderror
                </div>

                <div class="field">
                    <label for="region_id">
                        Wilayah
                    </label>

                    <select
                        name="region_id"
                        id="region_id"
                        required
                    >
                        <option value="">
                            Pilih wilayah
                        </option>

                        @foreach($regions as $region)
                            <option
                                value="{{ $region->id }}"
                                @selected(
                                    old('region_id') == $region->id
                                )
                            >
                                {{ $region->name }}
                            </option>
                        @endforeach
                    </select>

                    @error('region_id')
                        <span class="error">
                            {{ $message }}
                        </span>
                    @enderror
                </div>

                <div class="field">
                    <label for="category_id">
                        Kategori
                    </label>

                    <select
                        name="category_id"
                        id="category_id"
                        required
                    >
                        <option value="">
                            Pilih kategori
                        </option>

                        @foreach($categories as $category)
                            <option
                                value="{{ $category->id }}"
                                @selected(
                                    old('category_id') == $category->id
                                )
                            >
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>

                    @error('category_id')
                        <span class="error">
                            {{ $message }}
                        </span>
                    @enderror
                </div>
            </div>

            <h2 style="margin-top: 30px;">
                Identitas Ikan
            </h2>

            <div class="form-grid">
                <div class="field">
                    <label for="name">
                        Nama Ikan
                    </label>

                    <input
                        type="text"
                        name="name"
                        id="name"
                        value="{{ old('name') }}"
                        required
                    >

                    @error('name')
                        <span class="error">
                            {{ $message }}
                        </span>
                    @enderror
                </div>

                <div class="field">
                    <label for="scientific_name">
                        Nama Ilmiah
                    </label>

                    <input
                        type="text"
                        name="scientific_name"
                        id="scientific_name"
                        value="{{ old('scientific_name') }}"
                    >

                    @error('scientific_name')
                        <span class="error">
                            {{ $message }}
                        </span>
                    @enderror
                </div>

                <div class="field full">
                    <label for="image">
                        Gambar Ikan
                    </label>

                    <input
                        type="file"
                        name="image"
                        id="image"
                        accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp"
                    >

                    <small style="color: var(--muted);">
                        Maksimal 2 MB. Untuk perubahan data, kosongkan apabila gambar lama tidak ingin diganti.
                    </small>

                    @error('image')
                        <span class="error">
                            {{ $message }}
                        </span>
                    @enderror
                </div>
            </div>

            <h2 style="margin-top: 30px;">
                Informasi Detail
            </h2>

            <div class="form-grid">
                <div class="field">
                    <label for="habitat">
                        Habitat
                    </label>

                    <input
                        type="text"
                        name="habitat"
                        id="habitat"
                        value="{{ old('habitat') }}"
                        placeholder="Contoh: Danau Toba/Sungai Musi"
                    >

                    @error('habitat')
                        <span class="error">
                            {{ $message }}
                        </span>
                    @enderror
                </div>

                <div class="field">
                    <label for="food">
                        Makanan
                    </label>

                    <input
                        type="text"
                        name="food"
                        id="food"
                        value="{{ old('food') }}"
                    >

                    @error('food')
                        <span class="error">
                            {{ $message }}
                        </span>
                    @enderror
                </div>

                <div class="field">
                    <label for="conservation_status">
                        Status Kelestarian
                    </label>

                    <select
                        name="conservation_status"
                        id="conservation_status"
                        required
                    >
                        <option value="">
                            Pilih status kelestarian
                        </option>

                        @foreach($conservationStatuses as $value => $label)
                            <option
                                value="{{ $value }}"
                                @selected(
                                    old('conservation_status') === $value
                                )
                            >
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>

                    @error('conservation_status')
                        <span class="error">
                            {{ $message }}
                        </span>
                    @enderror
                </div>

                <div class="field">
                    <label for="biogeography">
                        Biogeografi
                    </label>

                    <select
                        name="biogeography"
                        id="biogeography"
                        required
                    >
                        <option value="">
                            Pilih biogeografi ikan
                        </option>

                        @foreach($biogeographyTypes as $value => $label)
                            <option
                                value="{{ $value }}"
                                @selected(
                                    old('biogeography') === $value
                                )
                            >
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>

                    @error('biogeography')
                        <span class="error">
                            {{ $message }}
                        </span>
                    @enderror
                </div>

                <div class="field full">
                    <label for="characteristics">
                        Ciri-ciri
                    </label>

                    <textarea
                        name="characteristics"
                        id="characteristics"
                        rows="5"
                    >{{ old('characteristics') }}</textarea>

                    @error('characteristics')
                        <span class="error">
                            {{ $message }}
                        </span>
                    @enderror
                </div>

                <div class="field full">
                    <label for="description">
                        Deskripsi
                    </label>

                    <textarea
                        name="description"
                        id="description"
                        rows="6"
                    >{{ old('description') }}</textarea>

                    @error('description')
                        <span class="error">
                            {{ $message }}
                        </span>
                    @enderror
                </div>

                <div class="field full">
                    <label for="request_note">
                        Catatan untuk Admin
                    </label>

                    <textarea
                        name="request_note"
                        id="request_note"
                        rows="4"
                        placeholder="Jelaskan bagian data yang ditambahkan atau diubah."
                    >{{ old('request_note') }}</textarea>

                    @error('request_note')
                        <span class="error">
                            {{ $message }}
                        </span>
                    @enderror
                </div>
            </div>

            <div
                style="
                    margin-top: 26px;
                    display: flex;
                    gap: 10px;
                    flex-wrap: wrap;
                "
            >
                <button
                    type="submit"
                    class="btn"
                >
                    Kirim Pengajuan
                </button>

                <a
                    href="{{ route('home') }}"
                    class="btn secondary"
                >
                    Kembali
                </a>
            </div>
        </form>
    </div>
</div>

<script>
    const requestType = document.getElementById('request_type');
    const fishField = document.getElementById('fish_field');
    const fishSelect = document.getElementById('fish_id');
    const fishHelper = document.getElementById('fish_helper');

    const regionInput = document.getElementById('region_id');
    const categoryInput = document.getElementById('category_id');
    const nameInput = document.getElementById('name');
    const scientificNameInput = document.getElementById('scientific_name');
    const habitatInput = document.getElementById('habitat');
    const foodInput = document.getElementById('food');
    const conservationStatusInput =
        document.getElementById('conservation_status');
    const biogeographyInput =
        document.getElementById('biogeography');
    const characteristicsInput =
        document.getElementById('characteristics');
    const descriptionInput =
        document.getElementById('description');

    const hasOldInput = @json(
        old('name') !== null
        || old('scientific_name') !== null
        || old('habitat') !== null
        || old('food') !== null
        || old('conservation_status') !== null
        || old('biogeography') !== null
        || old('characteristics') !== null
        || old('description') !== null
        || old('request_note') !== null
    );

    function toggleFishField() {
        const isUpdate = requestType.value === 'update';

        fishField.classList.toggle('hidden', !isUpdate);
        fishSelect.required = isUpdate;

        if (isUpdate) {
            fishHelper.textContent =
                'Pilih ikan lama agar seluruh data otomatis dimasukkan ke formulir.';
            return;
        }

        fishSelect.value = '';
        fishHelper.textContent =
            'Field ini hanya digunakan untuk perubahan data ikan lama.';
    }

    async function fillFishData() {
        const selectedOption =
            fishSelect.options[fishSelect.selectedIndex];

        if (
            !selectedOption
            || !selectedOption.value
            || !selectedOption.dataset.url
        ) {
            return;
        }

        try {
            fishSelect.disabled = true;
            fishHelper.textContent = 'Memuat data ikan...';

            const response = await fetch(
                selectedOption.dataset.url,
                {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                }
            );

            if (!response.ok) {
                throw new Error(
                    'Data ikan gagal dimuat. Silakan coba kembali.'
                );
            }

            const fish = await response.json();

            regionInput.value = fish.region_id ?? '';
            categoryInput.value = fish.category_id ?? '';
            nameInput.value = fish.name ?? '';
            scientificNameInput.value =
                fish.scientific_name ?? '';
            habitatInput.value = fish.habitat ?? '';
            foodInput.value = fish.food ?? '';
            conservationStatusInput.value =
                fish.conservation_status ?? '';
            biogeographyInput.value =
                fish.biogeography ?? '';
            characteristicsInput.value =
                fish.characteristics ?? '';
            descriptionInput.value =
                fish.description ?? '';

            fishHelper.textContent =
                'Data ikan berhasil dimuat. Ubah bagian yang perlu diperbarui.';
        } catch (error) {
            fishHelper.textContent = error.message;
            alert(error.message);
        } finally {
            fishSelect.disabled = false;
        }
    }

    requestType.addEventListener(
        'change',
        function () {
            toggleFishField();

            if (
                requestType.value === 'update'
                && fishSelect.value
            ) {
                fillFishData();
            }
        }
    );

    fishSelect.addEventListener(
        'change',
        fillFishData
    );

    toggleFishField();

    if (
        requestType.value === 'update'
        && fishSelect.value
        && !hasOldInput
    ) {
        fillFishData();
    }
</script>
@endsection
