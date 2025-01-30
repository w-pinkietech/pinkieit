@extends('components.header', ['user' => $user])

@section('title', __('pinkieit.target_edit', ['target' => __('pinkieit.profile')]))

@section('content')
    <x-form-edit action="{{ route('user.update') }}" back="{{ route('user.show') }}">
        <x-input name="name" value="{!! $user->name !!}" label="{{ __('pinkieit.name') }}" icon="user" required />
        <x-input name="email" value="{!! $user->email !!}" label="{{ __('pinkieit.email') }}" icon="envelope" required />
    </x-form-edit>
@endsection
