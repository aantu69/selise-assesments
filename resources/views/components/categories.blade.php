@foreach ($categories as $category)
    <div style="margin-bottom:5px;{{ $category->isChild() ? 'margin-left:40px;' : '' }}">
        <x-category :category="$category" />
    </div>
@endforeach
