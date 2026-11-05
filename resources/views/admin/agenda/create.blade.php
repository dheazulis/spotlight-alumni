@extends('layouts.dashboard')

@section('title', 'Tambah Newsletter')
@section('breadcrumb', 'Kelola Newsletter / Tambah Newsletter')

@push('styles')
<style>
    .agenda-form-page,
    .agenda-form-page * {
        box-sizing:border-box;
    }

    .agenda-form-page {
        color:var(--sd-ink,#23342b);
    }

    .agenda-form-heading {
        margin-bottom:22px;
    }

    .agenda-form-heading h1 {
        margin:0;
        color:var(--sd-green-950,#123d2d);
        font-size:26px;
        line-height:1.2;
        font-weight:750;
    }

    .agenda-form-heading p {
        margin:9px 0 0;
        color:var(--sd-muted,#758279);
        font-size:14px;
        line-height:1.6;
    }

    .agenda-back {
        display:inline-flex;
        align-items:center;
        margin-bottom:16px;
        color:var(--sd-green-800,#1d6045);
        font-size:13px;
        font-weight:650;
        text-decoration:none;
    }

    .agenda-back:hover {
        color:var(--sd-green-950,#123d2d);
    }

    .agenda-form-card {
        max-width:900px;
        background:#fff;
        border:1px solid var(--sd-line,#e3eae3);
        border-radius:13px;
        box-shadow:var(--sd-shadow,0 8px 24px rgba(24,59,42,.06));
        overflow:hidden;
    }

    .agenda-form-card-head {
        padding:19px 22px;
        border-bottom:1px solid var(--sd-line,#e3eae3);
    }

    .agenda-form-card-head h2 {
        margin:0;
        color:var(--sd-green-950,#123d2d);
        font-size:15px;
        font-weight:750;
    }

    .agenda-form-card-head p {
        margin:5px 0 0;
        color:var(--sd-muted,#758279);
        font-size:13px;
        line-height:1.5;
    }

    .agenda-form-body { padding:22px; }

    .agenda-form-page .form-label {
        display:block;
        margin-bottom:7px;
        color:#394b40;
        font-size:13px;
        font-weight:650;
    }

    .agenda-form-page .form-control,
    .agenda-form-page .form-select {
        width:100%;
        min-height:41px;
        padding:9px 11px;
        border:1px solid #dfe7df;
        border-radius:8px;
        color:var(--sd-ink,#23342b);
        background:#fff;
        font:400 13px/1.5 Inter,ui-sans-serif,system-ui,-apple-system,"Segoe UI",sans-serif;
        box-shadow:none;
    }

    .agenda-form-page textarea.form-control {
        min-height:115px;
        resize:vertical;
    }

    .agenda-form-page .form-control:focus,
    .agenda-form-page .form-select:focus {
        border-color:#8bb69a;
        outline:0;
        box-shadow:0 0 0 3px rgba(40,118,83,.10);
    }

    .agenda-form-page .form-text {
        margin-top:6px;
        color:#87938a;
        font-size:11px;
    }

    .agenda-image-preview {
        display:none;
        max-width:100%;
        width:380px;
        max-height:230px;
        object-fit:contain;
        margin-top:12px;
        padding:5px;
        border:1px solid var(--sd-line,#e3eae3);
        border-radius:9px;
        background:#f8faf7;
    }

    .agenda-form-actions {
        display:flex;
        justify-content:flex-end;
        flex-wrap:wrap;
        gap:8px;
        margin-top:8px;
    }

    .agenda-form-btn {
        display:inline-flex;
        align-items:center;
        justify-content:center;
        min-height:39px;
        padding:9px 15px;
        border-radius:8px;
        font:650 13px Inter,ui-sans-serif,system-ui,sans-serif;
        text-decoration:none;
        cursor:pointer;
    }

    .agenda-form-save {
        color:#fff;
        background:var(--sd-green-800,#1d6045);
        border:1px solid var(--sd-green-800,#1d6045);
    }

    .agenda-form-save:hover {
        color:#fff;
        background:var(--sd-green-900,#174b37);
    }

    .agenda-form-cancel {
        color:#59675e;
        background:#fff;
        border:1px solid #dfe7df;
    }

    .agenda-form-cancel:hover {
        color:var(--sd-green-900,#174b37);
        background:#f7faf7;
    }

    .agenda-form-error {
        margin-bottom:17px;
        padding:12px 15px;
        border:1px solid #f2d6d6;
        border-radius:9px;
        background:#fff1f1;
        color:#b83232;
        font-size:13px;
    }

    @media(max-width:680px) {
        .agenda-form-heading h1 { font-size:22px; }
        .agenda-form-body { padding:16px; }
        .agenda-form-card-head { padding:16px; }
    }
</style>
@endpush

@section('content')
<div class="agenda-form-page">

    <a href="{{ route('admin.agenda.index') }}" class="agenda-back">
        ← Kembali ke Kelola Newsletter
    </a>

    <header class="agenda-form-heading">
        <p class="sd-eyebrow">Publikasi Fakultas</p>
        <h1>Tambah Newsletter</h1>
        <p>Tambahkan informasi atau berita terbaru seputar alumni.</p>
    </header>

    <section class="agenda-form-card">

        <div class="agenda-form-card-head">
            <h2>Informasi Newsletter</h2>
            <p>Lengkapi informasi berikut untuk menambahkan newsletter.</p>
        </div>

        <div class="agenda-form-body">

            @if ($errors->any())
                <div class="agenda-form-error">
                    <strong>Periksa kembali data yang kamu masukkan:</strong>

                    <ul class="mb-0 mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form
                action="{{ route('admin.agenda.store') }}"
                method="POST"
                enctype="multipart/form-data"
            >
                @csrf

                <div class="mb-3">
                    <label for="judul" class="form-label">
                        Judul Newsletter
                    </label>

                    <input
                        type="text"
                        name="judul"
                        id="judul"
                        class="form-control"
                        value="{{ old('judul') }}"
                        placeholder="Masukkan judul newsletter"
                        required
                    >
                </div>

                <div class="mb-3">
                    <label for="deskripsi" class="form-label">
                        Deskripsi
                    </label>

                    <textarea
                        name="deskripsi"
                        id="deskripsi"
                        rows="5"
                        class="form-control"
                        placeholder="Tuliskan informasi newsletter..."
                    >{{ old('deskripsi') }}</textarea>
                </div>

                <div class="mb-3">
                    <label for="lokasi" class="form-label">
                        Lokasi
                    </label>

                    <input
                        type="text"
                        name="lokasi"
                        id="lokasi"
                        class="form-control"
                        value="{{ old('lokasi') }}"
                        placeholder="Masukkan lokasi jika ada"
                    >
                </div>

                <div class="row">

                    <div class="col-md-6 mb-3">
                        <label for="tanggal_mulai" class="form-label">
                            Tanggal Mulai
                        </label>

                        <input
                            type="date"
                            name="tanggal_mulai"
                            id="tanggal_mulai"
                            class="form-control"
                            value="{{ old('tanggal_mulai') }}"
                        >
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="tanggal_selesai" class="form-label">
                            Tanggal Selesai
                        </label>

                        <input
                            type="date"
                            name="tanggal_selesai"
                            id="tanggal_selesai"
                            class="form-control"
                            value="{{ old('tanggal_selesai') }}"
                        >
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="waktu_mulai" class="form-label">
                            Waktu Mulai
                        </label>

                        <input
                            type="time"
                            name="waktu_mulai"
                            id="waktu_mulai"
                            class="form-control"
                            value="{{ old('waktu_mulai') }}"
                        >
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="waktu_selesai" class="form-label">
                            Waktu Selesai
                        </label>

                        <input
                            type="time"
                            name="waktu_selesai"
                            id="waktu_selesai"
                            class="form-control"
                            value="{{ old('waktu_selesai') }}"
                        >
                    </div>

                </div>

                <div class="mb-4">
                    <label for="gambar" class="form-label">
                        Gambar Newsletter
                    </label>

                    <input
                        type="file"
                        name="gambar"
                        id="gambar"
                        class="form-control"
                        accept=".jpg,.jpeg,.png,.webp"
                    >

                    <div class="form-text">
                        Format JPG, JPEG, PNG, atau WEBP. Maksimal 2 MB.
                    </div>

                    <img
                        id="preview"
                        class="agenda-image-preview"
                        alt="Preview gambar newsletter"
                    >
                </div>

                <div class="mb-4">
                    <label for="status" class="form-label">
                        Status
                    </label>

                    <select name="status" id="status" class="form-select" required>
                        <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>
                            Draft
                        </option>

                        <option value="publikasi" {{ old('status') == 'publikasi' ? 'selected' : '' }}>
                            Publikasi
                        </option>
                    </select>
                </div>

                <div class="agenda-form-actions">
                    <a
                        href="{{ route('admin.agenda.index') }}"
                        class="agenda-form-btn agenda-form-cancel"
                    >
                        Batal
                    </a>

                    <button
                        type="submit"
                        class="agenda-form-btn agenda-form-save"
                    >
                        Simpan Newsletter
                    </button>
                </div>

            </form>
        </div>
    </section>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('gambar')?.addEventListener('change', function (event) {
    const preview = document.getElementById('preview');
    const file = event.target.files[0];

    if (file) {
        if (preview.dataset.objectUrl) {
            URL.revokeObjectURL(preview.dataset.objectUrl);
        }

        preview.dataset.objectUrl = URL.createObjectURL(file);
        preview.src = preview.dataset.objectUrl;
        preview.style.display = 'block';
    } else {
        preview.src = '';
        preview.style.display = 'none';
    }
});
</script>
@endpush