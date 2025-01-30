@extends('components.header', ['breadcrumbs' => $process])

@section('title', __('pinkieit.target_add', ['target' => __('pinkieit.cycle_time')]))

@section('content')
    <x-form-create action="{{ route('cycle-time.store', ['process' => $process]) }}"
        back="{{ route('process.show', ['process' => $process, 'tab' => 'part-number']) }}">
        <x-select name="part_number_id" label="{{ __('pinkieit.part_number') }}" :options="$partNumbers->toArray()" icon="hammer" required />
        <x-input name="cycle_time" label="{{ __('pinkieit.standard_cycle_time') }}{{ __('pinkieit.unit_sec') }}" icon="stopwatch" required />
        <x-input name="over_time" label="{{ __('pinkieit.over_time') }}{{ __('pinkieit.unit_sec') }}" icon="stopwatch" required />
    </x-form-create>
@endsection
