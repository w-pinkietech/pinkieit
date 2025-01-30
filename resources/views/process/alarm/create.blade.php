@extends('components.header', ['breadcrumbs' => $process])

@section('title', __('pinkieit.target_add', ['target' => __('pinkieit.alarm')]))

@section('content')
    <x-form-create action="{{ route('alarm.store', ['process' => $process]) }}"
        back="{{ route('process.show', ['process' => $process, 'tab' => 'alarm']) }}">
        <x-input name="alarm_text" label="{{ __('pinkieit.alarm_text') }}" icon="comment" required />
        <x-input name="identification_number" label="{{ __('pinkieit.identification_number') }}" icon="0" required />
        <x-select name="raspberry_pi_id" label="{{ __('pinkieit.raspberry_pi') }}" :options="$raspberryPiOptions" icon="raspberry-pi" required />
        <x-select name="sensor_type" label="{{ __('pinkieit.sensor_type') }}" :options="$sensorTypes" icon="temperature-three-quarters" required />
        <x-input-switch name="trigger" label="{{ __('pinkieit.trigger') }}" on="HIGH" off="LOW" />
    </x-form-create>
@endsection
