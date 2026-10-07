@props(['name' => 'heart'])
@php
$paths = [
 'arrow' => 'M5 12h14m-6-6 6 6-6 6',
 'back' => 'M19 12H5m6-6-6 6 6 6',
 'chevron' => 'm9 5 7 7-7 7',
 'check' => 'm5 12 4 4L19 6',
 'heart' => 'M20.8 4.6a5.5 5.5 0 0 0-7.8 0L12 5.7l-1.1-1.1a5.5 5.5 0 0 0-7.8 7.8L12 21l8.8-8.6a5.5 5.5 0 0 0 0-7.8Z',
 'sprout' => 'M12 21v-9M12 15C4 15 3 11 3 5c7 0 9 4 9 10Zm0-4c0-6 3-9 9-9 0 6-3 9-9 9Z',
 'gift' => 'M3 8h18v4H3zM5 12v9h14v-9M12 8v13M12 8H8a3 3 0 1 1 3-3l1 3Zm0 0h4a3 3 0 1 0-3-3l-1 3Z',
 'hands' => 'm2 12 3 8 6 2v-7l-4-4-2 2 3 3M22 12l-3 8-6 2v-7l4-4 2 2-3 3M5 12V5a2 2 0 0 0-3 0v7m17 0V5a2 2 0 0 1 3 0v7M9 3l3 3 3-3',
 'dots' => 'M5 12h.01M12 12h.01M19 12h.01',
 'user' => 'M20 21v-2a6 6 0 0 0-6-6h-4a6 6 0 0 0-6 6v2M16 5a4 4 0 1 1-8 0 4 4 0 0 1 8 0Z',
 'id' => 'M3 5h18v14H3zM9 9a2 2 0 1 1 0 4 2 2 0 0 1 0-4ZM6 16c0-3 6-3 6 0m3-6h3m-3 4h3',
 'lock' => 'M5 10h14v11H5zM8 10V6a4 4 0 0 1 8 0v4M12 14v3',
 'copy' => 'M9 8h11v13H9zM15 8V3H4v13h5',
 'phone' => 'M7 2h10v20H7zM10 5h4m-2 13h.01',
 'bank' => 'm3 7 9-5 9 5H3Zm2 3v8m7-8v8m7-8v8M3 21h18M3 18h18',
 'calendar' => 'M3 5h18v16H3zM7 2v6m10-6v6M3 10h18M7 14h2m4 0h2m-8 3h2',
 'clock' => 'M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0ZM12 7v5l3 2',
 'receipt' => 'M6 2h8l4 4v16l-3-2-3 2-3-2-3 2V2Zm8 0v5h4M9 11h6m-6 4h6',
 'history' => 'M4 20v-6m8 6V4m8 16v-9',
 'info' => 'M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0ZM12 11v6m0-10h.01',
 'plus' => 'M12 5v14M5 12h14',
 'share' => 'M12 16V2m-4 4 4-4 4 4M5 10v11h14V10',
 'close' => 'm6 6 12 12M6 18 18 6',
 'people' => 'M16 21v-3a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v3M13 6a4 4 0 1 1-8 0 4 4 0 0 1 8 0ZM17 3a4 4 0 0 1 0 8m2 3a4 4 0 0 1 3 4v3',
 'book' => 'M12 5v16M12 5C8 2 3 3 2 4v16c4-2 7-1 10 1 3-2 6-3 10-1V4c-4-2-7-1-10 1Z',
 'hash' => 'M5 9h15M4 15h15M10 3 7 21M17 3l-3 18',
];
@endphp
<svg {{ $attributes->class(['icon']) }} viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.65" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="{{ $paths[$name] ?? $paths['heart'] }}"/></svg>
