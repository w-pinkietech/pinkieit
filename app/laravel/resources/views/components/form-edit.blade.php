@props(['action', 'back', 'title' => ''])

<div class="row">
    <div class="col-md-12">
        <form action="{{ $action }}" method="POST" autocomplete="off">
            @csrf
            @method('PUT')
            <x-adminlte-card title="{{ $title }}">
                {{ $slot }}
                <x-slot name="footerSlot">
                    <x-adminlte-button type="submit" theme="info" label="{{ __('pinkieit.update') }}" icon="fa-solid fa-fw fa-paper-plane" />
                    <x-button-back href="{{ $back }}" />
                </x-slot>
            </x-adminlte-card>
        </form>
    </div>
</div>
