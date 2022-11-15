@if((new App\Services\CategoryService)->findProducts($category))
    <li>
        <a href="{{ route('catalog_inner', ['path' => $category->path]) }}">
            {{ $category->title }}
        </a>
        @if (!empty($category->children[0]))
            <ul>
                @each('layouts/partials/menu-item', $category->children, 'category')
            </ul>
        @endif
    </li>
@endif
