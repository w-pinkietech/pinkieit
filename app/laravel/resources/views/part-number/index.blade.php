@extends('components.header')

@section('title', __('pinkieit.target_list', ['target' => __('pinkieit.part_number')]))

@section('content')
    @include('adminlte::partials.common.preloader')
    <div class="row">
        @php
            $heads = ['#', __('pinkieit.target_name', ['target' => __('pinkieit.part_number')]), __('pinkieit.barcode')];
            $columns = [['visible' => false], null, null];
            if (Gate::allows('admin')) {
                array_push($heads, ['label' => '', 'no-export' => true, 'width' => 5]);
                array_push($columns, ['orderable' => false, 'searchable' => false]);
            }
            $config = [
                'columns' => $columns,
                'language' => ['url' => route('datatables')],
            ];
        @endphp
        <x-datatable-index href="{{ route('part-number.create') }}" add="{{ __('pinkieit.target_add', ['target' => __('pinkieit.part_number')]) }}"
            :heads="$heads" :config="$config">
            @foreach ($partNumbers as $partNumber)
                <tr>
                    <td></td>
                    <td class="align-middle">{{ $partNumber->part_number_name }}</td>
                    <td class="align-middle">{{ $partNumber->barcode }}</td>
                    @can('admin')
                        <td class="text-nowrap text-right align-middle">
                            <x-button-edit href="{{ route('part-number.edit', ['partNumber' => $partNumber]) }}" />
                            <x-button-delete target="part_number_{{ $partNumber->part_number_id }}" />
                        </td>
                        {{-- 削除ダイアログ --}}
                        <x-modal-delete id="part_number_{{ $partNumber->part_number_id }}"
                            action="{{ route('part-number.destroy', ['partNumber' => $partNumber]) }}">
                            <strong>{{ __('pinkieit.confirm_delete', ['target' => __('pinkieit.part_number')]) }}</strong>
                            <x-adminlte-card class="mt-4">
                                <strong>{{ __('pinkieit.target_name', ['target' => __('pinkieit.part_number')]) }}</strong>
                                <p class="mt-1 ml-2 mb-0">{{ $partNumber->part_number_name }}</p>
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
