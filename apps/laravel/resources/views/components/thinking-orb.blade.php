@props([
    'state' => 'working',
    'size'  => null,
    'label' => null,
    'speed' => '1',
    'theme' => 'auto',
    'pill'  => false,
    'large' => false,
])

<thinking-orb
    state="{{ $state }}"
    @if($size) size="{{ $size }}" @endif
    @if($label) label="{{ $label }}" @endif
    speed="{{ $speed }}"
    theme="{{ $theme }}"
    @if($pill || $label) pill @endif
    @if($large) large @endif
    {{ $attributes }}
></thinking-orb>
