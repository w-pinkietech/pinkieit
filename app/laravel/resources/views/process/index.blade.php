@extends('components.header')

@section('title', __('pinkieit.target_list', ['target' => __('pinkieit.process')]))

@section('content')
    @include('adminlte::partials.common.preloader')
    <div class="row">
        @php
            $heads = ['#', __('pinkieit.target_name', ['target' => __('pinkieit.process')]), ['label' => __('pinkieit.status'), 'width' => 15], ['label' => '', 'no-export' => true, 'width' => 5]];
            $nonsearch = ['orderable' => false, 'searchable' => false];
            $config = [
                'columns' => [['visible' => false], [], $nonsearch, $nonsearch],
                'language' => ['url' => route('datatables')],
            ];
        @endphp
        <x-datatable-index href="{{ route('process.create') }}" add="{{ __('pinkieit.target_add', ['target' => __('pinkieit.process')]) }}" :heads="$heads"
            :config="$config">
            @foreach ($processes as $process)
                <tr>
                    <td></td>
                    <td class="align-middle">{{ $process->process_name }}</td>
                    <td class="align-middle">
                        @switch($process->status())
                            @case(\App\Enums\ProductionStatus::RUNNING())
                                <span class="badge badge-light" style="font-size: 100%;">
                                    {{ $process->status()->description }}
                                </span>
                            @break

                            @case(\App\Enums\ProductionStatus::CHANGEOVER())
                                <span class="badge badge-warning" style="font-size: 100%;">
                                    {{ $process->status()->description }}
                                </span>
                            @break

                            @case(\App\Enums\ProductionStatus::BREAKDOWN())
                                @if ($process->productionHistory?->inPlannedOutage() ?? false)
                                    <span class="badge badge-info" style="font-size: 100%;">
                                        {{ __('pinkieit.planned_outage') }}
                                    </span>
                                @else
                                    <span class="badge badge-danger" style="font-size: 100%;">
                                        {{ $process->status()->description }}
                                    </span>
                                @endif
                            @break

                            @case(\App\Enums\ProductionStatus::COMPLETE())
                                <span class="badge badge-default" style="font-size: 100%;">
                                    {{ $process->status()->description }}
                                </span>
                            @break

                            @default
                        @endswitch
                    </td>
                    <td class="text-nowrap text-right align-middle">
                        <a class="btn btn-tool" href="{{ route('process.show', ['process' => $process]) }}">
                            <i class="fa-solid fa-lg fa-share-from-square"></i>
                            {{ __('pinkieit.detail') }}
                        </a>
                        <a class="btn btn-tool" href="{{ route('production.index', ['process' => $process]) }}">
                            <i class="fa-solid fa-lg fa-history"></i>
                            {{ __('pinkieit.history') }}
                        </a>
                        <a class="btn btn-tool" href="{{ route('onoff.index', ['process' => $process]) }}">
                            <i class="fa-solid fa-lg fa-message"></i>
                            {{ __('pinkieit.notification') }}
                        </a>
                    </td>
                </tr>
            @endforeach
        </x-datatable-index>
    </div>
@endsection

@section('js')
    @include('components.toast')
@endsection
