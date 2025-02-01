@extends('components.header')

@section('title', __('pinkieit.target_add', ['target' => __('pinkieit.raspberry_pi')]))

@section('content')
    <x-form-create action="{{ route('raspberry-pi.store') }}" back="{{ route('raspberry-pi.index') }}">
        <x-input name="raspberry_pi_name" label="{{ __('pinkieit.target_name', ['target' => __('pinkieit.raspberry_pi')]) }}" icon="raspberry-pi"
            required />
        <x-input name="ip_address" label="{{ __('pinkieit.ip_address') }}" icon="at" required />
    </x-form-create>
@endsection
