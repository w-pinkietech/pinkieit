<div class="position-relative float-right pr-2" style="top:-2.75rem; height: 0;">
    @can('admin')
        @if ($process->isStopped())
            <x-button-add class="pt-0 pb-0" href="{{ route('process.planned-outage.create', ['process' => $process]) }}" />
        @endif
    @endcan
</div>
<table class="mt-2 table">
    <thead>
        <tr>
            <th class="border-top-0 border-bottom-0">{{ __('pinkieit.name') }}</th>
            <th class="border-top-0 border-bottom-0">{{ __('pinkieit.start') }}</th>
            <th class="border-top-0 border-bottom-0">{{ __('pinkieit.end') }}</th>
            @can('admin')
                @if ($process->isStopped())
                    <th class="border-top-0 border-bottom-0 w-1"></th>
                @endif
            @endcan
        </tr>
    </thead>
    <tbody>
        @foreach ($process->plannedOutages as $plannedOutage)
            <tr class="text-muted">
                <td class="align-middle">{{ $plannedOutage->planned_outage_name }}</td>
                <td class="align-middle">{{ $plannedOutage->formatStartTime() }}</td>
                <td class="align-middle">{{ $plannedOutage->formatEndTime() }}</td>
                @can('admin')
                    @if ($process->isStopped())
                        <td class="text-nowrap text-right">
                            {{-- 計画停止時間削除ボタン --}}
                            <x-button-delete target="planned_outage_{{ $plannedOutage->planned_outage_id }}" />
                        </td>
                        {{-- 計画停止時間削除ダイアログ --}}
                        <x-modal-delete id="planned_outage_{{ $plannedOutage->planned_outage_id }}"
                            action="{{ route('process.planned-outage.destroy', ['process' => $process, 'processPlannedOutage' => $plannedOutage->pivot]) }}">
                            <strong>{{ __('pinkieit.confirm_delete', ['target' => __('pinkieit.process_planned_outage')]) }}</strong>
                            <x-adminlte-card class="mt-4">
                                <strong>{{ __('pinkieit.target_name', ['target' => __('pinkieit.planned_outage')]) }}</strong>
                                <p class="mt-1 ml-2">{{ $plannedOutage->planned_outage_name }}</p>
                                <hr>
                                <strong>{{ __('pinkieit.start_time') }}</strong>
                                <p class="mt-1 ml-2">{{ $plannedOutage->formatStartTime() }}</p>
                                <hr>
                                <strong>{{ __('pinkieit.end_time') }}</strong>
                                <p class="mt-1 ml-2 mb-0">{{ $plannedOutage->formatEndTime() }}</p>
                            </x-adminlte-card>
                        </x-modal-delete>
                    @endif
                @endcan
            </tr>
        @endforeach
    </tbody>
</table>
