@foreach ($subcategories as $sub)
    <option value="{{ $sub->id }}" {{ ($sub->id === $document->category_id) ? 'selected' : '' }}> {{ $separator }} {{ truncateWords($sub->name, 35) }}</option>

    @if (count($sub->children) > 0)
        @php
            $separators = $separator . ' --- ';
        @endphp
        @include('admin.includes.subcategory', ['subcategories' => $sub->children, 'separator' => $separators])
    @endif
@endforeach
