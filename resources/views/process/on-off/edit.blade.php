@extends('components.header', ['breadcrumbs' => $process])

@section('title', __('pinkieit.target_edit', ['target' => __('pinkieit.notification')]))

@section('content')
    <x-form-edit action="{{ route('onoff.update', ['process' => $process, 'onOff' => $onOff]) }}"
        back="{{ route('process.show', ['process' => $process, 'tab' => 'on-off']) }}">
        <x-input name="event_name" value="{!! $onOff->event_name !!}" label="{{ __('pinkieit.target_name', ['target' => __('pinkieit.event')]) }}" icon="font"
            required />
        <x-input name="on_message" value="{!! $onOff->on_message !!}" label="{{ __('pinkieit.target_message', ['target' => 'ON']) }}" icon="toggle-on"
            required />
        <x-input name="off_message" value="{!! $onOff->off_message !!}" label="{{ __('pinkieit.target_message', ['target' => 'OFF']) }}" icon="toggle-off" />
        <x-select name="raspberry_pi_id" selected="{{ $onOff->raspberry_pi_id }}" label="{{ __('pinkieit.raspberry_pi') }}" :options="$raspberryPiOptions"
            icon="raspberry-pi" required />
        <x-select name="pin_number" selected="{{ $onOff->pin_number }}" label="{{ __('pinkieit.pin_number') }}" :options="$pinOptions" icon="map-pin"
            required />
    </x-form-edit>
@endsection
