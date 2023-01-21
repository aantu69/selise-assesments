@php

@endphp
<option style="margin-left: 50px;" value="{{ $category->id }}">
    {!! $category->isChild() ? \App\Helpers\Helper::getLevel($category->level) . $category->name : $category->name !!}
</option>

<x-select-categories :categories="$category->children" />
