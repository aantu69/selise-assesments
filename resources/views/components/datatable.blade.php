@inject('ConversionTrait', 'App\Traits\ConversionTraitForBlade')
<div class="card card-primary card-outline">
    <div class="card-header">
        <h3 class="card-title">{{ $title }}</h3>
        {{-- @if (isset($selectPage))
            @if ($selectPage)
                <div class="col-md-10 mb-2">
                    @if ($selectAll)
                        <div>
                            You have selected all <strong>{{ $results->total() }}</strong> items.
    </div>
    @else
    <div>
        You have selected <strong>{{ count($checked) }}</strong> items, Do you want to Select All
        <strong>{{ $results->total() }}</strong>?
        <a href="#" class="ml-2" wire:click.prevent="selectAll">Select All</a>
    </div>
    @endif

</div>
@endif
@endif --}}

        <div class="card-tools">
            @if ($printButton)
                <a rel="noopener" wire:click="generateExcel()" class="btn btn-info btn-sm">
                    <i class="fas fa-file-excel"></i> Excel Applicants</a>&nbsp;
                <a rel="noopener" target="_blank" class="btn btn-info btn-sm"
                    href="{{ route($printRoute, ['certificate' => false]) }}">
                    <i class="fas fa-print"></i> Print Applicants</a>&nbsp;
                <a rel="noopener" target="_blank" class="btn btn-info btn-sm"
                    href="{{ route($printRoute, ['certificate' => true]) }}">
                    <i class="fas fa-print"></i> Print Applicants with Certificate</a>&nbsp;
            @endif
            @if ($mswordButton)
                <a rel="noopener" target="_blank" class="btn btn-info btn-sm" href="{{ route($mswordRoute) }}">
                    <i class="fas fa-file-word"></i> MSWord</a>&nbsp;
            @endif
            @if ($gazetteButton)
                <a rel="noopener" target="_blank" class="btn btn-info btn-sm" href="{{ route($gazetteRoute) }}">
                    <i class="fas fa-award"></i> Gazette</a>&nbsp;
            @endif
            @if ($approveButton)
                <button class="btn btn-success btn-sm" wire:click="confirmApprove()"> Approve</button>&nbsp;
            @endif
            @if ($createButton)
                <button class="btn btn-success btn-sm" wire:click="create()"> {{ __('label.Add New') }}</button>
            @endif
        </div>
    </div>
    <!-- /.card-header -->
    <div class="card-body table-responsive no-padding">
        <div class="dataTables_wrapper table-responsive no-padding">
            <div class="row">
                <div class="col-sm-12 col-md-6">
                    <div class="dataTables_length">
                        <label>Show
                            <select wire:model="perPage" class="custom-select custom-select-sm">
                                @foreach ($perPageOptions as $key => $value)
                                    <option value="{{ $value }}">{{ $key }}</option>
                                @endforeach
                            </select> entries
                        </label>
                    </div>
                </div>
                <div class="col-sm-12 col-md-6">
                    <div class="dataTables_filter">
                        <label>Search:
                            <input type="search" wire:model.debounce.500ms="search"
                                class="form-control form-control-sm" placeholder="">
                        </label>
                    </div>
                </div>
            </div>
            @if ($filterBy)
                <div class="row">
                    <div class="col-sm-12 col-md-12">
                        <div class="dataTables_length" style="text-align: center;">
                            <label>Filter by keywords:&nbsp;&nbsp;
                                {{-- <div> --}}
                                @foreach ($keywords as $keyword)
                                    <div class="icheck-primary d-inline">
                                        <input type="checkbox" id="keyword_{{ $keyword->id }}"
                                            name="keyword_{{ $keyword->id }}" value="{{ $keyword->id }}"
                                            wire:model.lazy='filteredKeywords'>
                                        <label for="keyword_{{ $keyword->id }}">{{ $keyword->name }}</label>
                                    </div>&nbsp;&nbsp;
                                @endforeach
                                {{-- </div> --}}
                            </label>
                        </div>
                    </div>
                </div>
            @endif
            <div class="row">
                <div class="col-sm-12 table-sticky-not">
                    <table class="table table-bordered table-hover data-table">
                        <thead>
                            <tr>
                                @foreach ($headers as $header)
                                    <th {{ isset($header['width']) ? 'width=' . $header['width'] : 'width=auto' }}
                                        style="cursor: pointer;"
                                        {{ $header['order'] ? 'wire:click=sortBy("' . $header['data'] . '")' : '' }}>
                                        @if ($header['order'] && $sortColumn == $header['data'])
                                            <span>{!! $sortDirection == 'asc' ? '&#8659;' : '&#8657;' !!}</span>
                                        @endif
                                        {!! isset($header['labelFunc']) ? $header['labelFunc']() : $header['label'] !!}
                                    </th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($results as $result)
                                <tr>
                                    @foreach ($headers as $header)
                                        <td style="{{ isset($header['style']) ? $header['style'] : '' }}">
                                            {!! $header['func']($result[$header['data']]) !!}
                                        </td>
                                    @endforeach
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ count($headers) }}" style="text-align: center; color: red;">
                                        <h2>No matching records found!</h2>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="row">
                @if ($results->total() > 0)
                    <div class="col-sm-12 col-md-5">
                        <div class="dataTables_info">
                            Showing {{ $results->firstItem() }} to {{ $results->lastItem() }} of
                            {{ $results->total() }}
                            entries
                        </div>
                    </div>
                @endif
                <div class="col-sm-12 col-md-7" style="text-align: right">
                    <div class="dataTables_paginate paging_simple_numbers">
                        {{ $results->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
