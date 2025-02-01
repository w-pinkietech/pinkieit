@extends('components.header', ['breadcrumbs' => $partNumber])

@section('title', __('pinkieit.target_edit', ['target' => __('pinkieit.part_number')]))

@section('content')
    <x-form-edit action="{{ route('part-number.update', ['partNumber' => $partNumber]) }}" back="{{ route('part-number.index') }}">
        <x-input name="part_number_name" value="{!! $partNumber->part_number_name !!}" label="{{ __('pinkieit.target_name', ['target' => __('pinkieit.part_number')]) }}"
            icon="hammer" required />
        <x-input name="barcode" value="{!! $partNumber->barcode !!}" label="{{ __('pinkieit.barcode') }}" icon="barcode" />
        <x-textarea name="remark" value="{!! $partNumber->remark !!}" label="{{ __('pinkieit.remark') }}" />
    </x-form-edit>
@endsection
