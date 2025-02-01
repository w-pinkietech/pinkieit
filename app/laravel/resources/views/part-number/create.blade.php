@extends('components.header')

@section('title', __('pinkieit.target_add', ['target' => __('pinkieit.part_number')]))

@section('content')
    <x-form-create action="{{ route('part-number.store') }}" back="{{ route('part-number.index') }}">
        <x-input name="part_number_name" label="{{ __('pinkieit.target_name', ['target' => __('pinkieit.part_number')]) }}" icon="hammer" required />
        <x-input name="barcode" label="{{ __('pinkieit.barcode') }}" icon="barcode" />
        <x-textarea name="remark" label="{{ __('pinkieit.remark') }}" />
    </x-form-create>
@endsection
