@extends('components.header')

@section('title', __('pinkieit.target_edit', ['target' => __('pinkieit.planned_outage')]))

@section('content')
    <x-form-edit action="{{ route('planned-outage.update', ['plannedOutage' => $plannedOutage]) }}" back="{{ route('planned-outage.index') }}">
        <x-input name="planned_outage_name" value="{!! $plannedOutage->planned_outage_name !!}"
            label="{{ __('pinkieit.target_name', ['target' => __('pinkieit.planned_outage')]) }}" icon="stop" required />
        <x-input-time id="start_itme" name="start_time" value="{{ $plannedOutage->formatStartTime() }}" label="{{ __('pinkieit.start_time') }}" />
        <x-input-time id="end_time" name="end_time" value="{{ $plannedOutage->formatEndTime() }}" label="{{ __('pinkieit.end_time') }}" />
    </x-form-edit>
@endsection
