<button {{ $attributes->merge(['class' => 'bg-'. $color .' text-white btn btn-'.$size. 'text-'.$textColor]) }}>
    {{ $slot }}
</button>