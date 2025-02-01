@extends('components.header', ['breadcrumbs' => $raspberryPi])

@section('title', __('pinkieit.target_edit', ['target' => __('pinkieit.raspberry_pi')]))

@section('content')
    <x-form-edit action="{{ route('raspberry-pi.update', ['raspberryPi' => $raspberryPi]) }}" back="{{ route('raspberry-pi.index') }}">
        <x-input name="raspberry_pi_name" value="{!! $raspberryPi->raspberry_pi_name !!}"
            label="{{ __('pinkieit.target_name', ['target' => __('pinkieit.raspberry_pi')]) }}" icon="raspberry-pi" required />
        <x-input name="ip_address" value="{{ $raspberryPi->ip_address }}" label="{{ __('pinkieit.ip_address') }}" icon="at" required />
    </x-form-edit>
@endsection
