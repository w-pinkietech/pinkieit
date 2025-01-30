@extends('components.header', ['user' => $user])

@section('title', __('pinkieit.change_password'))

@section('content')
    <x-form-edit action="{{ route('user.password.change') }}" back="{{ route('user.show') }}">
        <x-input name="current_password" type="password" label="{{ __('pinkieit.current_password') }}" icon="lock" required />
        <x-input name="password" type="password" label="{{ __('pinkieit.new_password') }}" icon="lock" required />
        <x-input name="password_confirmation" type="password" label="{{ __('pinkieit.password_confirmation') }}" icon="lock" required />
    </x-form-edit>
@endsection
