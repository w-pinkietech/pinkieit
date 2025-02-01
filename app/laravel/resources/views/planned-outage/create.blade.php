@extends('components.header')

@section('title', __('pinkieit.target_add', ['target' => __('pinkieit.planned_outage')]))

@section('content')
    <x-form-create action="{{ route('planned-outage.store') }}" back="{{ route('planned-outage.index') }}">
        <x-input name="planned_outage_name" label="{{ __('pinkieit.target_name', ['target' => __('pinkieit.planned_outage')]) }}" icon="stop" required />
        <x-input-time name="start_time" value="12:00" label="{{ __('pinkieit.start_time') }}" />
        <x-input-time name="end_time" value="13:00" label="{{ __('pinkieit.end_time') }}" />
    </x-form-create>
@endsection
