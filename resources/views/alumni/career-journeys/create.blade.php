
@extends('layouts.dashboard')

@section('title', 'Tambah Perjalanan Karier')
@section('breadcrumb', 'Tambah Perjalanan Karier')

@section('content')

<div class="cj-container cj-form-container">

    <div class="cj-heading">
        <div>
            <h1>Tambah Perjalanan Karier</h1>
            <p>Isi informasi pengalaman kerja atau perjalanan karier kamu.</p>
        </div>
    </div>

    <section class="cj-card">

        @if ($errors->any())
            <div class="cj-error">
                <p>Periksa kembali data yang kamu masukkan.</p>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('alumni.career-journeys.store') }}"
              method="POST">
            @csrf

            <label for="nama_perusahaan">Nama Perusahaan/Instansi *</label>
            <input type="text"
                   id="nama_perusahaan"
                   name="nama_perusahaan"
                   value="{{ old('nama_perusahaan') }}"
                   required>

            <label for="jabatan">Jabatan *</label>
            <input type="text"
                   id="jabatan"
                   name="jabatan"
                   value="{{ old('jabatan') }}"
                   required>

            <label for="bidang_karier">Bidang Karier</label>
            <input type="text"
                   id="bidang_karier"
                   name="bidang_karier"
                   value="{{ old('bidang_karier') }}"
                   placeholder="Contoh: Teknologi Informasi">

            <label for="tanggal_mulai">Tanggal Mulai</label>
            <input type="date"
                   id="tanggal_mulai"
                   name="tanggal_mulai"
                   value="{{ old('tanggal_mulai') }}">

            <label for="tanggal_selesai">Tanggal Selesai</label>
            <input type="date"
                   id="tanggal_selesai"
                   name="tanggal_selesai"
                   value="{{ old('tanggal_selesai') }}">

            <div class="cj-checkbox">
                <input type="checkbox"
                       id="masih_bekerja"
                       name="masih_bekerja"
                       value="1"
                       @checked(old('masih_bekerja'))>

                <label for="masih_bekerja">
                    Saya masih bekerja di perusahaan/instansi ini
                </label>
            </div>

            <label for="deskripsi">Deskripsi Pengalaman</label>
            <textarea id="deskripsi"
                      name="deskripsi"
                      placeholder="Ceritakan tugas atau pengalaman kerja kamu...">{{ old('deskripsi') }}</textarea>

            <div class="cj-actions">
                <button type="submit" class="cj-button">
                    Simpan
                </button>

                <a href="{{ route('alumni.career-journeys.index') }}"
                   class="cj-button cj-cancel">
                    Batal
                </a>
            </div>
        </form>
    </section>

</div>

@endsection

@push('styles')
<style>
.cj-container {
    width: 100%;
    max-width: 1100px;
    margin: auto;
    color: #263b32;
}

.cj-form-container {
    max-width: 850px;
}

.cj-heading {
    margin-bottom: 22px;
}

.cj-heading h1 {
    margin: 0 0 8px;
    color: #244d37;
    font-size: 24px;
}

.cj-heading p {
    margin: 0;
    color: #849087;
    font-size: 13px;
}

.cj-card {
    padding: 25px;
    background: white;
    border: 1px solid #e4ece5;
    border-radius: 12px;
    box-shadow: 0 3px 12px rgba(35,65,45,.035);
}

.cj-card label {
    display: block;
    margin: 16px 0 7px;
    color: #344d3c;
    font-size: 13px;
    font-weight: 600;
}

.cj-card input:not([type="checkbox"]),
.cj-card textarea {
    width: 100%;
    padding: 11px 12px;
    border: 1px solid #dce9df;
    border-radius: 7px;
    font: inherit;
    font-size: 13px;
    color: #344d3c;
    background: #fff;
}

.cj-card input:focus,
.cj-card textarea:focus {
    outline: none;
    border-color: #639675;
    box-shadow: 0 0 0 3px rgba(99,150,117,.12);
}

.cj-card textarea {
    min-height: 110px;
    resize: vertical;
}

.cj-checkbox {
    display: flex;
    align-items: center;
    gap: 9px;
    margin-top: 18px;
}

.cj-checkbox input {
    width: auto;
}

.cj-checkbox label {
    margin: 0;
    font-weight: normal;
}

.cj-button {
    display: inline-block;
    padding: 11px 16px;
    border: 0;
    border-radius: 7px;
    background: #477b55;
    color: white;
    text-decoration: none;
    cursor: pointer;
    font-size: 13px;
    font-weight: 600;
}

.cj-cancel {
    background: #64748b;
}

.cj-actions {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
    margin-top: 22px;
}

.cj-error {
    padding: 13px 16px;
    margin-bottom: 18px;
    border-radius: 8px;
    background: #fff0ef;
    color: #b42318;
    font-size: 13px;
}

.cj-error p {
    margin-top: 0;
}

@media (max-width: 600px) {
    .cj-card {
        padding: 17px;
    }
}
</style>
@endpush
