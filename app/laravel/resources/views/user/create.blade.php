@extends('components.header')

@section('title', __('pinkieit.target_add', ['target' => __('pinkieit.user')]))

@section('content')
    <x-form-create action="{{ route('user.store') }}" back="{{ route('user.index') }}">
        <x-input name="name" label="{{ __('pinkieit.name') }}" icon="user" required />
        <x-input name="email" label="{{ __('pinkieit.email') }}" icon="envelope" required />
        <x-select name="role" label="{{ __('pinkieit.role') }}" :options="$roles" icon="toolbox" required />
        <x-input name="password" type="password" label="{{ __('pinkieit.password') }}" icon="lock" required />
        <x-input name="password_confirmation" type="password" label="{{ __('pinkieit.password_confirmation') }}" icon="lock" required />
    </x-form-create>
@endsection
