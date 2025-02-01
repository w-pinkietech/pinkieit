@extends('components.header', ['breadcrumbs' => $process])

@section('title', __('pinkieit.switch_producer'))

@section('content')
    <x-form-edit action="#" back="{{ route('process.show', ['process' => $process]) }}">
        <table class="table">
            <thead>
                <tr>
                    <th class="border-top-0 border-bottom-0">{{ __('pinkieit.target_name', ['target' => __('pinkieit.line')]) }}</th>
                    <th class="border-top-0 border-bottom-0">{{ __('pinkieit.color') }}</th>
                    <th class="border-top-0 border-bottom-0">{{ __('pinkieit.worker') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($process->lines as $line)
                    <tr>
                        <td class="align-middle">{{ $line->line_name }}</td>
                        <td class="align-middle">
                            <i class="fa-solid fa-fw fa-square-full" style="color: {{ $line->chart_color }}"></i>
                        </td>
                        <td class="p-0">
                            <x-select name="worker_id[]" label="" :options="$workerOptions" selected="{{ $line->worker_id }}" />
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </x-form-edit>
@endsection
