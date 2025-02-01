@extends('components.header', ['breadcrumbs' => $process])

@section('title', __('pinkieit.target_edit', ['target' => __('pinkieit.process')]))

@section('content')
    <x-form-edit action="{{ route('process.update', ['process' => $process]) }}" back="{{ route('process.show', ['process' => $process]) }}">
        <x-input name="process_name" value="{!! $process->process_name !!}" label="{{ __('pinkieit.target_name', ['target' => __('pinkieit.process')]) }}"
            icon="industry" required />
        <x-input-color name="plan_color" value="{{ $process->plan_color }}" label="{{ __('pinkieit.plan_color') }}" init="{{ $process->plan_color }}"
            required />
        <x-textarea name="remark" value="{!! $process->remark !!}" label="{{ __('pinkieit.remark') }}" />
    </x-form-edit>
@endsection
