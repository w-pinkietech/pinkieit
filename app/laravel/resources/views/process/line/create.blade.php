@extends('components.header', ['breadcrumbs' => $process])

@section('title', __('pinkieit.target_add', ['target' => __('pinkieit.line')]))

@section('content')
    <x-form-create action="{{ route('line.store', ['process' => $process]) }}"
        back="{{ route('process.show', ['process' => $process, 'tab' => 'line']) }}">
        <x-input name="line_name" label="{{ __('pinkieit.target_name', ['target' => __('pinkieit.line')]) }}" icon="people-line" required />
        <x-input-color name="chart_color" label="{{ __('pinkieit.color') }}" required />
        <x-select name="raspberry_pi_id" label="{{ __('pinkieit.raspberry_pi') }}" :options="$raspberryPiOptions" icon="raspberry-pi" required />
        <x-select name="pin_number" label="{{ __('pinkieit.pin_number') }}" :options="$pinOptions" icon="map-pin" required />
        <x-input-switch name="defective" label="{{ __('pinkieit.defective') }}" />
        <x-select name="worker_id" label="{{ __('pinkieit.worker') }}" :options="$workerOptions" icon="person" hide="true" />
        <x-select name="parent_id" label="{{ __('pinkieit.target_line') }}" :options="$nonDefectiveLines" icon="people-line" hide="true" required />
    </x-form-create>
@endsection

@section('js')
    @include('process.line.toggle')
@endsection
