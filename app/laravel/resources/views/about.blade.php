@extends('components.header')

@section('title', __('pinkieit.about'))

@section('content')
    <div class="row">
        <div class="col-md-12">
            <x-adminlte-card>
                <x-text-item label="{{ __('pinkieit.version') }}" text="{{ config('pinkieit.version') }}" />
                <hr>
                <x-text-item label="{{ __('pinkieit.copyright') }}" text="{{ config('pinkieit.copyright') }}" />
            </x-adminlte-card>
        </div>
    </div>
@endsection
