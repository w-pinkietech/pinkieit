@extends('components.header')

@section('title', __('pinkieit.target_list', ['target' => __('pinkieit.planned_outage')]))

@section('content')
    @include('adminlte::partials.common.preloader')
    <div class="row">
        @php
            $heads = ['#', __('pinkieit.target_name', ['target' => __('pinkieit.planned_outage')]), __('pinkieit.start_time'), __('pinkieit.end_time')];
            $colmns = [['visible' => false], null, null, null];
            if (Gate::allows('admin')) {
                array_push($heads, ['label' => '', 'no-export' => true, 'width' => 5]);
                array_push($colmns, ['orderable' => false, 'searchable' => false]);
            }
            $config = [
                'order' => [[2, 'asc']],
                'columns' => $colmns,
                'language' => ['url' => route('datatables')],
            ];
        @endphp
        <x-datatable-index href="{{ route('planned-outage.create') }}" add="{{ __('pinkieit.target_add', ['target' => __('pinkieit.planned_outage')]) }}"
            :heads="$heads" :config="$config">
            @foreach ($plannedOutages as $plannedOutage)
                <tr>
                    <td></td>
                    <td class="align-middle">{{ $plannedOutage->planned_outage_name }}</td>
                    <td class="align-middle">{{ $plannedOutage->formatStartTime() }}</td>
                    <td class="align-middle">{{ $plannedOutage->formatEndTime() }}</td>
                    @can('admin')
                        <td class="text-nowrap text-right align-middle">
                            <x-button-edit href="{{ route('planned-outage.edit', ['plannedOutage' => $plannedOutage]) }}" />
                            <x-button-delete target="planned_outage_{{ $plannedOutage->planned_outage_id }}" />
                        </td>
                        {{-- 削除ダイアログ --}}
                        <x-modal-delete id="planned_outage_{{ $plannedOutage->planned_outage_id }}"
                            action="{{ route('planned-outage.destroy', ['plannedOutage' => $plannedOutage]) }}">
                            <strong>{{ __('pinkieit.confirm_delete', ['target' => __('pinkieit.planned_outage')]) }}</strong>
                            <x-adminlte-card class="mt-4">
                                <strong>{{ __('pinkieit.target_name', ['target' => __('pinkieit.planned_outage')]) }}</strong>
                                <p class="mt-1 ml-2 mb-0">{{ $plannedOutage->planned_outage_name }}</p>
                                <hr>
                                <strong>{{ __('pinkieit.start_time') }}</strong>
                                <p class="mt-1 ml-2">{{ $plannedOutage->formatStartTime() }}</p>
                                <hr>
                                <strong>{{ __('pinkieit.end_time') }}</strong>
                                <p class="mt-1 ml-2 mb-0">{{ $plannedOutage->formatEndTime() }}</p>
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
