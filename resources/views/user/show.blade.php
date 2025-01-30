@extends('components.header')

@section('title', __('pinkieit.profile'))

@section('content')
    <div class="row">
        <div class="col-md-12">
            <x-adminlte-card>
                <strong>{{ __('pinkieit.name') }}</strong>
                <p class="text-muted mt-1 ml-2">{{ $user->name }}</p>
                <hr>
                <strong>{{ __('pinkieit.email') }}</strong>
                <p class="text-muted mt-1 ml-2">{{ $user->email }}</p>

                <a class="btn btn-primary" href="{{ route('user.edit') }}" role="button">
                    {{ __('pinkieit.target_edit', ['target' => __('pinkieit.profile')]) }}
                </a>
                <a class="btn btn-primary ml-2" href="{{ route('user.password') }}" role="button">
                    {{ __('pinkieit.change_password') }}
                </a>
            </x-adminlte-card>
            @can('admin')
                <x-adminlte-card>
                    <form action="{{ route('user.token') }}" method="POST" autocomplete="off">
                        @csrf
                        <x-input name="token" value="{{ session('token') }}" label="{{ __('pinkieit.webapi_token') }}" />
                        <x-adminlte-button class="btn-primary" type="submit" label="{{ __('pinkieit.generate_token') }}" />
                    </form>
                @endcan
            </x-adminlte-card>
        </div>
    </div>
@endsection

@section('js')
    @include('components.toast')
@endsection
