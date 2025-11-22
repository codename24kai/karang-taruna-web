@extends('layouts.admin')
@section('header', 'Pengaturan Website')

@section('content')
    @if(session('success'))
        <div style="background: #d1e7dd; color: #0f5132; padding: 15px; border-radius: 8px; margin-bottom: 20px;">{{ session('success') }}</div>
    @endif

    <div class="content-card">
        <div class="content-card-header">
            <h3 class="content-card-title">Informasi Umum</h3>
        </div>

        <form action="{{ url('/admin/pengaturan') }}" method="POST">
            @csrf
            <div class="form-group">
                <label>Nama Website</label>
                <input type="text" name="site_name" class="form-input" value="{{ $settings['site_name'] ?? '' }}">
            </div>
            <div class="form-group">
                <label>Alamat Sekretariat</label>
                <textarea name="site_address" class="form-input" rows="2">{{ $settings['site_address'] ?? '' }}</textarea>
            </div>
            <div class="form-group">
                <label>Email Resmi</label>
                <input type="email" name="site_email" class="form-input" value="{{ $settings['site_email'] ?? '' }}">
            </div>
            <div class="form-group">
                <label>Nomor Telepon / WA</label>
                <input type="text" name="site_phone" class="form-input" value="{{ $settings['site_phone'] ?? '' }}">
            </div>
            <div class="form-group">
                <label>Username Instagram</label>
                <input type="text" name="instagram" class="form-input" value="{{ $settings['instagram'] ?? '' }}">
            </div>

            <div style="text-align: right; margin-top: 20px;">
                <button type="submit" class="btn-primary">Simpan Perubahan</button>
            </div>
        </form>
    </div>
@endsection
