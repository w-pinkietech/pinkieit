<div class="position-relative float-right pr-2" style="top:-2.75rem; height: 0;">
    <a class="btn btn-tool" href="{{ route('production.index', ['process' => $process]) }}" role="button">
        <i class="fa-solid fa-lg fa-history"></i>
    </a>
    @can('admin')
        @if ($process->isStopped())
            <a class="btn btn-tool" href="{{ route('process.edit', ['process' => $process]) }}" role="button">
                <i class="fa-solid fa-lg fa-edit"></i>
            </a>
            <x-adminlte-button data-toggle="modal" data-target="#delete-modal" theme="tool" icon="fa-solid fa-lg fa-trash" />
            <x-modal-delete id="delete-modal" action="{{ route('process.destroy', ['process' => $process]) }}">
                <strong>{{ __('pinkieit.confirm_delete', ['target' => __('pinkieit.process')]) }}</strong>
                <x-adminlte-card class="mt-4">
                    <strong>{{ __('pinkieit.target_name', ['target' => __('pinkieit.process')]) }}</strong>
                    <p class="mt-1 ml-2 mb-0">{{ $process->process_name }}</p>
                </x-adminlte-card>
            </x-modal-delete>
        @endif
    @endcan
</div>
<div class="mt-2 p-4">
    <strong>{{ __('pinkieit.target_name', ['target' => __('pinkieit.process')]) }}</strong>
    <p class="text-muted mt-1 ml-2">{{ $process->process_name }}</p>
    <hr>
    <strong>{{ __('pinkieit.plan_color') }}</strong>
    <p class="text-muted mt-1 ml-2">
        <i class="fa-solid fa-fw fa-square-full" style="padding-top:1px; color: {{ $process->plan_color }}"></i>
    </p>
    <hr>
    <div class="float-right mt-4">
        @if ($process->raspberryPis->count() !== 0)
            {{-- 品番切り替えボタン --}}
            <a class="btn btn-sm btn-default mr-2" href="{{ route('production.create', ['process' => $process]) }}" role="button">
                <i class="fa-solid fa-fw fa-rotate"></i>
                {{ __('pinkieit.switch_part_number') }}
            </a>
        @endif
        @if (!$process->isStopped())
            @if ($process->isChangeover())
                {{-- 生産開始ボタン --}}
                <x-adminlte-button class="btn-sm mr-2" data-toggle="modal" data-target="#stop-changeover"
                    label="{{ __('pinkieit.start_production') }}" icon="fa-solid fa-fw fa-play" />
            @else
                {{-- 段取り替えボタン --}}
                <x-adminlte-button class="btn-sm mr-2" data-toggle="modal" data-target="#start-changeover" label="{{ __('pinkieit.changeover') }}"
                    icon="fa-solid fa-fw fa-pause" />
            @endif
            {{-- 停止ボタン --}}
            <x-adminlte-button class="btn-sm" data-toggle="modal" data-target="#stop" label="{{ __('pinkieit.stop') }}"
                icon="fa-solid fa-fw fa-stop" />
        @endif
    </div>
    {{-- 工程ステータス --}}
    <strong>{{ __('pinkieit.status') }}</strong>
    <p class="text-muted mt-1 ml-2">
        @if ($process->isStopped())
            {{ __('pinkieit.stop') }}
        @else
            {{ \App\Enums\ProductionStatus::getDescription($process->productionHistory->status) }} :
            {{ $process->productionHistory->part_number_name }}
        @endif
    </p>
    <hr>
    <strong>{{ __('pinkieit.remark') }}</strong>
    <p class="text-muted text-area mt-1 ml-2">{{ $process->remark }}</p>
</div>
