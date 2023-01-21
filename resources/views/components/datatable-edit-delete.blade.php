@can($editGate)
    <a class="btn btn-primary action-btn" href="{{ route($crudRoutePart . '.edit', $row->id) }}">
        {{ trans('global.edit') }}
    </a>
@endcan

@can($deleteGate)
    <form action="{{ route($crudRoutePart . '.destroy', $row->id) }}" method="POST"
        onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
        <input type="hidden" name="_method" value="DELETE">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="submit" class="btn btn-danger action-btn" value="{{ trans('global.delete') }}">
    </form>
@endcan

@can($showGate)
    <a class="btn btn-primary action-btn" href="{{ route($crudRoutePart . '.show', $row->id) }}">Show</a>
@endcan

@can($acknowledgementGate)
    <a class="btn btn-primary action-btn btnprnt" href="{{ route('prints.acknowledgement', $row->id) }}">Print</a>
@endcan
