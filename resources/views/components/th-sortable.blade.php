@php
    $name = 'sortBy' . ucfirst($name);
    $currentSortDirection = request()->query($name);
    $sortDirection = isset($currentSortDirection) ? ($currentSortDirection === 'desc' ? 'asc' : 'desc') : 'desc';
@endphp

<th>
    <a href="?{{$name}}={{$sortDirection}}">
        @if(!isset($currentSortDirection))
            <i class="fa-solid fa-sort"></i>
        @elseif($sortDirection === 'asc')
            <i class="fa-solid fa-sort-down"></i>
        @elseif($sortDirection === 'desc')
            <i class="fa-solid fa-sort-up"></i>
        @endif
        {{$slot}}
    </a>
</th>
