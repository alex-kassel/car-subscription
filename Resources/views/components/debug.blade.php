@push('debug')
    <li>
        {{ round(microtime(true) - LARAVEL_START, 4) }} - {{ $from }}<br>
        {{ $slot ?? '' }}
    </li>
@endpush
