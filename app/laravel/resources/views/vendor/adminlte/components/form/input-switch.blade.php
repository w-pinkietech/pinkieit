@extends('adminlte::components.form.input-group-component')

@section('input_group_item')

    {{-- Bootstrap 5 Native Switch --}}
    <div class="form-check form-switch">
        <input type="checkbox" class="form-check-input {{ $makeItemClass() }}" 
            id="{{ $id }}" name="{{ $name }}"
            {{ $attributes }} 
            @if($errors->any() && $enableOldSupport && $getOldValue($errorKey))
                checked
            @endif>
    </div>

@overwrite

{{-- Add custom styling for AdminLTE integration --}}

@once
@push('css')
<style type="text/css">

    {{-- Integrate Bootstrap 5 switch with AdminLTE input groups --}}
    .input-group .form-switch {
        display: flex;
        align-items: center;
        padding: 0;
        margin: 0;
    }

    .input-group .form-switch .form-check-input {
        margin-left: 0.5rem;
        margin-top: 0;
        float: none;
    }

    {{-- Size adjustments --}}
    .input-group-lg .form-switch .form-check-input {
        width: 3rem;
        height: 1.5rem;
    }

    .input-group-sm .form-switch .form-check-input {
        width: 2rem;
        height: 1rem;
    }

    {{-- Custom invalid style setup --}}
    .adminlte-invalid-iswgroup > .form-switch > .form-check-input,
    .adminlte-invalid-iswgroup > .input-group-prepend > *,
    .adminlte-invalid-iswgroup > .input-group-append > * {
        box-shadow: 0 .25rem 0.5rem rgba(255,0,0,.25);
    }

</style>
@endpush
@endonce
