<div class="position-relative float-right pr-2" style="top:-2.75rem; height: 0;">
    @can('admin')
        @if ($process->isStopped())
            <a class="btn btn-tool pt-0 pb-0" href="{{ route('line.sorting', ['process' => $process]) }}" role="button">
                <i class="fa-solid fa-lg fa-sort"></i>
            </a>
            <x-button-add class="pt-0 pb-0" href="{{ route('line.create', ['process' => $process]) }}" />
        @endif
    @endcan
</div>
<table class="mt-2 table">
    <thead>
        <th class="border-top-0 border-bottom-0">
            {{ __('pinkieit.target_name', ['target' => __('pinkieit.line')]) }}
        </th>
        <th class="border-top-0 border-bottom-0">{{ __('pinkieit.color') }}</th>
        <th class="border-top-0 border-bottom-0">{{ __('pinkieit.worker') }}</th>
        <th class="border-top-0 border-bottom-0">{{ __('pinkieit.raspberry_pi') }}</th>
        <th class="border-top-0 border-bottom-0">{{ __('pinkieit.pin_number') }}</th>
        <th class="border-top-0 border-bottom-0">{{ __('pinkieit.failure') }}</th>
        @can('admin')
            @if ($process->isStopped())
                <th class="border-top-0 border-bottom-0 w-1"></th>
            @endif
        @endcan
    </thead>
    <tbody>
        @foreach ($process->raspberryPis as $raspberryPi)
            <tr class="text-muted">
                <td class="align-middle">{{ $raspberryPi->pivot->line_name }}</td>
                <td class="align-middle">
                    <i class="fa-solid fa-fw fa-square-full" style="color: {{ $raspberryPi->pivot->chart_color }}"></i>
                </td>
                @if ($raspberryPi->pivot->defective)
                    <td class="align-middle">{{ $raspberryPi->pivot->parentLine->worker?->worker_name }}</td>
                @else
                    <td class="align-middle">{{ $raspberryPi->pivot->worker?->worker_name }}</td>
                @endif
                <td class="align-middle">{{ $raspberryPi->raspberry_pi_name }}</td>
                <td class="align-middle">{{ $raspberryPi->pivot->pinNumber() }}</td>
                <td class="align-middle">
                    @if ($raspberryPi->pivot->defective)
                        <i class="fa-solid fa-fw fa-check text-danger"></i>
                    @endif
                </td>
                @can('admin')
                    @if ($process->isStopped())
                        <td class="text-nowrap text-right align-middle">
                            {{-- ライン編集ボタン --}}
                            <x-button-edit href="{{ route('line.edit', ['process' => $process, 'line' => $raspberryPi->pivot]) }}" />
                            {{-- ライン削除ボタン --}}
                            <x-button-delete target="line_{{ $raspberryPi->pivot->line_id }}" />
                        </td>
                        {{-- ライン削除ダイアログ --}}
                        <x-modal-delete id="line_{{ $raspberryPi->pivot->line_id }}"
                            action="{{ route('line.destroy', ['process' => $process, 'line' => $raspberryPi->pivot]) }}">
                            <strong>{{ __('pinkieit.confirm_delete', ['target' => __('pinkieit.line')]) }}</strong>
                            <x-adminlte-card class="mt-4">
                                <strong>{{ __('pinkieit.target_name', ['target' => __('pinkieit.line')]) }}</strong>
                                <p class="mt-1 ml-2">{{ $raspberryPi->pivot->line_name }}</p>
                                <hr>
                                <strong>{{ __('pinkieit.color') }}</strong>
                                <p class="mt-1 ml-2">
                                    {{ $raspberryPi->pivot->chart_color }}
                                    <i class="fa-solid fa-fw fa-square-full" style="padding-top:1px; color: {{ $raspberryPi->pivot->chart_color }}"></i>
                                </p>
                                <hr>
                                <strong>{{ __('pinkieit.ip_address') }}</strong>
                                <p class="mt-1 ml-2">{{ $raspberryPi->ip_address }}</p>
                                <hr>
                                <strong>{{ __('pinkieit.pin_number') }}</strong>
                                <p class="mt-1 ml-2 mb-0">{{ $raspberryPi->pivot->pinNumber() }}</p>
                            </x-adminlte-card>
                        </x-modal-delete>
                    @endif
                @endcan
            </tr>
        @endforeach
    </tbody>
</table>
