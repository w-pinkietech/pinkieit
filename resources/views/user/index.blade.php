@extends('components.header')

@section('title', __('pinkieit.target_list', ['target' => __('pinkieit.user')]))

@section('content')
    @include('adminlte::partials.common.preloader')
    <div class="row">
        @php
            $heads = ['#', __('pinkieit.name'), __('pinkieit.email'), __('pinkieit.role'), ['label' => '', 'no-export' => true, 'width' => 5]];
            $colmns = [['visible' => false], null, null, null, ['orderable' => false, 'searchable' => false]];
            $config = [
                'columns' => $colmns,
                'language' => ['url' => route('datatables')],
            ];
        @endphp
        <x-datatable-index href="{{ route('user.create') }}" add="{{ __('pinkieit.target_add', ['target' => __('pinkieit.user')]) }}" :heads="$heads"
            :config="$config">
            @foreach ($users as $user)
                <tr>
                    <td></td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->role->description }}</td>
                    <td class="text-nowrap text-right align-middle">
                        <x-button-delete target="user_{{ $user->id }}" />
                    </td>
                    {{-- 削除ダイアログ --}}
                    <x-modal-delete id="user_{{ $user->id }}" action="{{ route('user.destroy', ['user' => $user]) }}">
                        <strong>{{ __('pinkieit.confirm_delete', ['target' => __('pinkieit.user')]) }}</strong>
                        <x-adminlte-card class="mt-4">
                            <strong>{{ __('pinkieit.name') }}</strong>
                            <p class="mt-1 ml-2">{{ $user->name }}</p>
                            <hr>
                            <strong>{{ __('pinkieit.email') }}</strong>
                            <p class="mt-1 ml-2 mb-0">{{ $user->email }}</p>
                            <hr>
                            <strong>{{ __('pinkieit.role') }}</strong>
                            <p class="mt-1 ml-2 mb-0">{{ $user->role->description }}</p>
                        </x-adminlte-card>
                    </x-modal-delete>
                </tr>
            @endforeach
        </x-datatable-index>
    </div>
@endsection

@section('js')
    @include('components.toast')
@endsection
