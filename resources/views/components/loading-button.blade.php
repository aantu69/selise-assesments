<button {{ $attributes->merge(['class' => 'btn btn-sm']) }}>
    <div wire:loading wire:target="{{ $attributes['wire:click.prevent'] }}" style="margin: 0">
        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
            style="margin: 0; background: none; display: inline; shape-rendering: auto;" width="15px" height="15px"
            viewBox="0 0 100 100" preserveAspectRatio="xMidYMid">
            <path d="M10 50A40 40 0 0 0 90 50A40 44 0 0 1 10 50" fill="#ffffff" stroke="none">
                <animateTransform attributeName="transform" type="rotate" dur="1.8492753623188404s"
                    repeatCount="indefinite" keyTimes="0;1" values="0 50 52;360 50 52"></animateTransform>
            </path>
        </svg>
    </div>
    {{ $slot }}
</button>
