@if ($prices['final']['price'] < $prices['regular']['price'])
    <p
        class="final-price font-medium text-zinc-500 line-through max-sm:leading-4"
        aria-label="{{ $prices['regular']['formatted_price'] }}" style="margin-bottom: 10px; display: inline-block; color: #aaa; font-size: 13px; padding-right: 10px;">
        {{ $prices['regular']['formatted_price'] }}
    </p>

    <p class="font-semibold max-sm:leading-4 text-zinc-500" style="font-size: 13px; display: inline-block;">
        {{ $prices['final']['formatted_price'] }}
    </p>
@else
    <p class="final-price font-semibold max-sm:leading-4 text-zinc-500" style="font-size: 13px;">
        {{ $prices['regular']['formatted_price'] }}
    </p>
@endif