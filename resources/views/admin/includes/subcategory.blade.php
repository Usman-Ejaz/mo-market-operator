@foreach ($subcategories as $sub)
    <option value="{{ $sub->id }}" {{ ((old('category_id') !== null && old('category_id') == $sub->id) || $sub->id === $document->category_id) ? 'selected' : '' }}> {{ $separator }} {{ $sub->name }}</option>

    @if (count($sub->children) > 0)
        @php
            $separators = $separator . ' -- ';
        @endphp
        @include('admin.includes.subcategory', ['subcategories' => $sub->children, 'separator' => $separators])
    @endif
@endforeach
