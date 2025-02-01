@extends('components.header')

@section('title', __('pinkieit.target_list', ['target' => __('pinkieit.raspberry_pi')]))

@section('content')
    @include('adminlte::partials.common.preloader')
    <div class="row">
        @php
            $heads = ['#', __('pinkieit.target_name', ['target' => __('pinkieit.raspberry_pi')]), __('pinkieit.ip_address'), __('pinkieit.cpu_temperature') . __('pinkieit.unit_temperature'), __('pinkieit.cpu_utilization') . __('pinkieit.unit_rate'), __('pinkieit.update_date')];
            $colmns = [['visible' => false], null, null, null, null, null];
            if (Gate::allows('admin')) {
                array_push($heads, ['label' => '', 'no-export' => true, 'width' => 5]);
                array_push($colmns, ['orderable' => false, 'searchable' => false]);
            }
            $config = [
                'columns' => $colmns,
                'language' => ['url' => route('datatables')],
            ];
        @endphp
        <x-datatable-index href="{{ route('raspberry-pi.create') }}" add="{{ __('pinkieit.target_add', ['target' => __('pinkieit.raspberry_pi')]) }}"
            :heads="$heads" :config="$config">
            @foreach ($raspberryPis as $raspberryPi)
                <tr>
                    <td></td>
                    <td class="align-middle">{{ $raspberryPi->raspberry_pi_name }}</td>
                    <td class="align-middle">{{ $raspberryPi->ip_address }}</td>
                    <td class="align-middle">{{ $raspberryPi->cpu_temperature }}</td>
                    <td class="align-middle">{{ $raspberryPi->cpu_utilization }}</td>
                    <td class="align-middle">{{ $raspberryPi->updated_at }}</td>
                    @can('admin')
                        <td class="text-nowrap text-right align-middle">
                            <x-button-edit href="{{ route('raspberry-pi.edit', ['raspberryPi' => $raspberryPi]) }}" />
                            <x-button-delete target="raspberry_pi_{{ $raspberryPi->raspberry_pi_id }}" />
                        </td>
                        {{-- 削除ダイアログ --}}
                        <x-modal-delete id="raspberry_pi_{{ $raspberryPi->raspberry_pi_id }}"
                            action="{{ route('raspberry-pi.destroy', ['raspberryPi' => $raspberryPi]) }}">
                            <strong>{{ __('pinkieit.confirm_delete', ['target' => __('pinkieit.raspberry_pi')]) }}</strong>
                            <x-adminlte-card class="mt-4">
                                <strong>{{ __('pinkieit.ip_address') }}</strong>
                                <p class="mt-1 ml-2 mb-0">{{ $raspberryPi->ip_address }}</p>
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
