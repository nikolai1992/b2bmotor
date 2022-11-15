<ul class="categories-list">
    @foreach($categories as $category)
        @include('clients/partials/category-item', compact('client','category'))
    @endforeach
</ul>
