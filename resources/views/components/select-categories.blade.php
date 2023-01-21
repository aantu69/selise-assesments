@foreach ($categories as $category)
    <x-select-category :category="$category" />
@endforeach
