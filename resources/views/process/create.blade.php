@extends('components.header')

@section('title', __('pinkieit.target_add', ['target' => __('pinkieit.process')]))

@section('content')
    <x-form-create action="{{ route('process.store') }}" back="{{ route('process.index') }}">
        <x-input name="process_name" label="{{ __('pinkieit.target_name', ['target' => __('pinkieit.process')]) }}" icon="industry" required />
        <x-input-color name="plan_color" label="{{ __('pinkieit.plan_color') }}" init="#FFFFFF" required />
        <x-textarea name="remark" label="{{ __('pinkieit.remark') }}" />
    </x-form-create>
@endsection
