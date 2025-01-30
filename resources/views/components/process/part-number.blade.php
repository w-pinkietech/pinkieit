<div class="position-relative float-right pr-2" style="top:-2.75rem; height: 0;">
    @can('admin')
        @if ($process->isStopped())
            <x-button-add class="pt-0 pb-0" href="{{ route('cycle-time.create', ['process' => $process]) }}" />
        @endif
    @endcan
</div>
<table class="mt-2 table">
    <thead>
        <th class="border-top-0 border-bottom-0">
            {{ __('pinkieit.target_name', ['target' => __('pinkieit.part_number')]) }}
        </th>
        <th class="border-top-0 border-bottom-0">
            {{ __('pinkieit.barcode') }}
        </th>
        <th class="border-top-0 border-bottom-0">
            {{ __('pinkieit.standard_cycle_time') }}{{ __('pinkieit.unit_sec') }}
        </th>
        <th class="border-top-0 border-bottom-0">
            {{ __('pinkieit.over_time') }}{{ __('pinkieit.unit_sec') }}
        </th>
        @can('admin')
            @if ($process->isStopped())
                <th class="border-top-0 border-bottom-0 w-1"></th>
            @endif
        @endcan
    </thead>
    <tbody>
        @foreach ($process->partNumbers as $partNumber)
            <tr class="text-muted">
                <td class="align-middle">{{ $partNumber->part_number_name }}</td>
                <td class="align-middle">{{ $partNumber->barcode }}</td>
                <td class="align-middle">{{ $partNumber->pivot->cycle_time }}</td>
                <td class="align-middle">{{ $partNumber->pivot->over_time }}</td>
                @can('admin')
                    @if ($process->isStopped())
                        <td class="text-nowrap text-right align-middle">
                            {{-- サイクルタイム編集ボタン --}}
                            <x-button-edit href="{{ route('cycle-time.edit', ['process' => $process, 'cycleTime' => $partNumber->pivot]) }}" />
                            {{-- サイクルタイム削除ボタン --}}
                            <x-button-delete target="cycle_time_{{ $partNumber->pivot->cycle_time_id }}" />
                        </td>
                        {{-- サイクルタイム削除ダイアログ --}}
                        <x-modal-delete id="cycle_time_{{ $partNumber->pivot->cycle_time_id }}"
                            action="{{ route('cycle-time.destroy', ['process' => $process, 'cycleTime' => $partNumber->pivot]) }}">
                            <strong>{{ __('pinkieit.confirm_delete', ['target' => __('pinkieit.cycle_time')]) }}</strong>
                            <x-adminlte-card class="mt-4">
                                <strong>{{ __('pinkieit.target_name', ['target' => __('pinkieit.part_number')]) }}</strong>
                                <p class="mt-1 ml-2">{{ $partNumber->part_number_name }}</p>
                                <hr>
                                <strong>{{ __('pinkieit.standard_cycle_time') }}{{ __('pinkieit.unit_sec') }}</strong>
                                <p class="mt-1 ml-2">{{ $partNumber->pivot->cycle_time }}</p>
                                <hr>
                                <strong>{{ __('pinkieit.over_time') }}{{ __('pinkieit.unit_sec') }}</strong>
                                <p class="mt-1 ml-2 mb-0">{{ $partNumber->pivot->over_time }}</p>
                            </x-adminlte-card>
                        </x-modal-delete>
                    @endif
                @endcan
            </tr>
        @endforeach
    </tbody>
</table>
