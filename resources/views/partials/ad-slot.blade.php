@php
    $publisherId = trim((string) config('services.google.adsense_publisher_id'));
    $placement = str_contains($class ?? '', 'ad-sidebar')
        ? 'sidebar'
        : (str_contains($class ?? '', 'ad-top') ? 'top' : 'inline');
    $slotId = trim((string) config('services.google.adsense_slots.'.$placement));
@endphp

@if($publisherId !== '' && $slotId !== '')
    <aside class="ad-slot {{ $class ?? '' }}" aria-label="Advertisement">
        <span class="ad-label">Advertisement</span>
        <ins class="adsbygoogle"
             style="display:block"
             data-ad-client="{{ $publisherId }}"
             data-ad-slot="{{ $slotId }}"
             data-ad-format="auto"
             data-full-width-responsive="true"></ins>
    </aside>
    @once
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                document.querySelectorAll('.adsbygoogle').forEach(function () {
                    try { (window.adsbygoogle = window.adsbygoogle || []).push({}); } catch (error) {}
                });
            });
        </script>
    @endonce
@endif
