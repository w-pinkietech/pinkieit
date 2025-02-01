@extends('components.header', ['breadcrumbs' => $process])

@section('title', __('pinkieit.target_edit', ['target' => __('pinkieit.line')]))

@section('content')
    <x-form-edit action="{{ route('line.update', ['process' => $process, 'line' => $line]) }}"
        back="{{ route('process.show', ['process' => $process, 'tab' => 'line']) }}">
        <x-input name="line_name" value="{!! $line->line_name !!}" label="{{ __('pinkieit.target_name', ['target' => __('pinkieit.line')]) }}"
            icon="people-line" required />
        <x-input-color name="chart_color" value="{{ $line->chart_color }}" label="{{ __('pinkieit.color') }}" init="{{ $line->chart_color }}" required />
        <x-select name="raspberry_pi_id" label="{{ __('pinkieit.raspberry_pi') }}" :options="$raspberryPiOptions" icon="raspberry-pi"
            selected="{{ $line->raspberry_pi_id }}" required />
        <x-select name="pin_number" label="{{ __('pinkieit.pin_number') }}" :options="$pinOptions" icon="map-pin" selected="{{ $line->pin_number }}" required />
        <x-input-switch name="defective" label="{{ __('pinkieit.defective') }}" checked="{{ $line->defective }}" />
        <x-select name="worker_id" label="{{ __('pinkieit.worker') }}" :options="$workerOptions" icon="person" hide="true"
            selected="{{ $line->worker?->worker_id }}" />
        <x-select name="parent_id" label="{{ __('pinkieit.target_line') }}" :options="$nonDefectiveLines" icon="people-line" hide="true"
            selected="{{ $line->parentLine?->line_id }}" required />
    </x-form-edit>
@endsection

@section('js')
    @include('process.line.toggle')
@endsection
