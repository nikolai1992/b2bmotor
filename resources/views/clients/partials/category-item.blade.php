<li class="category-tree--item category-tree--parent">
    {{--<a href="{{ route('catalog_inner', ['path' => $category->path]) }}">--}}
        @if($category->client($client->id))
            <input type="checkbox" name="exclude_categories[]" value="{{ $category->id }}" checked>
        @else
            <input type="checkbox" name="exclude_categories[]" value="{{ $category->id }}">
        @endif

        {{ $category->title }}
    {{--</a>--}}
    @if (!empty($category->children[0]))
        <ul>
            @foreach($category->children as $category)
                @include('clients/partials/category-item', compact('client','category'))
            @endforeach
        </ul>
    @endif
</li>
