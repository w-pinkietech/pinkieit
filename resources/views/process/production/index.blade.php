@extends('components.header', ['breadcrumbs' => $process])

@section('title', $process->process_name . '：' . __('pinkieit.production_history'))

@section('content')
    <x-adminlte-card body-class="p-0">
        <table class="table">
            <thead>
                <tr>
                    <th class="border-bottom-0">{{ __('pinkieit.target_name', ['target' => __('pinkieit.part_number')]) }}</th>
                    <th class="border-bottom-0">{{ __('pinkieit.start') }}</th>
                    <th class="border-bottom-0">{{ __('pinkieit.end') }}</th>
                    <th class="border-bottom-0">{{ __('pinkieit.period') }}</th>
                    <th class="border-bottom-0">{{ __('pinkieit.number_of_production') }}</th>
                    <th class="border-bottom-0">CT {{ __('pinkieit.unit_sec') }}</th>
                    <th class="border-bottom-0 w-1"></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($histories as $history)
                    <tr>
                        <td class="align-middle">{{ $history->part_number_name }}</td>
                        <td class="align-middle">{{ $history->start }}</td>
                        <td class="align-middle">{{ $history->stop }}</td>
                        <td class="align-middle">{{ $history->period() }}</td>
                        <td class="align-middle">{{ $history->lastProductCount() }}</td>
                        <td class="align-middle">{{ $history->cycle_time }}</td>
                        <td class="text-nowrap align-middle">
                            <a class="btn btn-tool" href="{{ route('production.show', ['process' => $process, 'history' => $history]) }}">
                                <i class="fa-solid fa-lg fa-chart-line pr-1"></i>
                                {{ __('pinkieit.display') }}
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="d-flex justify-content-center mt-4">
            {{ $histories->links() }}
        </div>
    </x-adminlte-card>
@endsection
