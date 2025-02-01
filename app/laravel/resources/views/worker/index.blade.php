@extends('components.header')

@section('title', __('pinkieit.target_list', ['target' => __('pinkieit.worker')]))

@section('content')
    @include('adminlte::partials.common.preloader')
    <div class="row">
        @php
            $heads = ['#', __('pinkieit.identification_number'), __('pinkieit.target_name', ['target' => __('pinkieit.worker')]), __('pinkieit.mac_address')];
            $colmns = [['visible' => false], null, null, null];
            if (Gate::allows('admin')) {
                array_push($heads, ['label' => '', 'no-export' => true, 'width' => 5]);
                array_push($colmns, ['orderable' => false, 'searchable' => false]);
            }
            $config = [
                'columns' => $colmns,
                'language' => ['url' => route('datatables')],
            ];
        @endphp
        <x-datatable-index href="{{ route('worker.create') }}" add="{{ __('pinkieit.target_add', ['target' => __('pinkieit.worker')]) }}" :heads="$heads"
            :config="$config">
            @foreach ($workers as $worker)
                <tr>
                    <td></td>
                    <td class="align-middle">{{ $worker->identification_number }}</td>
                    <td class="align-middle">{{ $worker->worker_name }}</td>
                    <td class="align-middle">{{ $worker->mac_address }}</td>
                    @can('admin')
                        <td class="text-nowrap text-right align-middle">
                            <x-button-edit href="{{ route('worker.edit', ['worker' => $worker]) }}" />
                            <x-button-delete target="worker_{{ $worker->worker_id }}" />
                        </td>
                        {{-- 削除ダイアログ --}}
                        <x-modal-delete id="worker_{{ $worker->worker_id }}" action="{{ route('worker.destroy', ['worker' => $worker]) }}">
                            <strong>{{ __('pinkieit.confirm_delete', ['target' => __('pinkieit.worker')]) }}</strong>
                            <x-adminlte-card class="mt-4">
                                <strong>{{ __('pinkieit.identification_number') }}</strong>
                                <p class="mt-1 ml-2">{{ $worker->identification_number }}</p>
                                <hr>
                                <strong>{{ __('pinkieit.target_name', ['target' => __('pinkieit.worker')]) }}</strong>
                                <p class="mt-1 ml-2 mb-0">{{ $worker->worker_name }}</p>
                            </x-adminlte-card>
                        </x-modal-delete>
                    @endcan
                </tr>
            @endforeach
        </x-datatable-index>
    </div>
@endsection

@section('js')
    @include('components.toast')
@endsection
