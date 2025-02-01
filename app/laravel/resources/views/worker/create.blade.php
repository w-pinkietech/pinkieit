@extends('components.header')

@section('title', __('pinkieit.target_add', ['target' => __('pinkieit.worker')]))

@section('content')
    <x-form-create action="{{ route('worker.store') }}" back="{{ route('worker.index') }}">
        <x-input name="identification_number" label="{{ __('pinkieit.identification_number') }}" icon="id-card" required />
        <x-input name="worker_name" label="{{ __('pinkieit.target_name', ['target' => __('pinkieit.worker')]) }}" icon="person" required />
        <x-input name="mac_address" label="{{ __('pinkieit.barcode_reader_mac_address') }}" icon="qrcode" />
    </x-form-create>
@endsection
