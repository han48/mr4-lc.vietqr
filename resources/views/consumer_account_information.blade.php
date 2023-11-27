@php
    if (!isset($id)) {
        $id = substr(md5(mt_rand()), 0, 7);
    }
    $prefix = $id;
    if (strlen($prefix) > 0) {
        $prefix = $prefix . "_";
    }
@endphp
<script src="{{ asset('vendor/mr4-lc/vietqr/js/viet-qr.js') }}"></script>
<link rel="stylesheet" href="{{ asset('vendor/mr4-lc/vietqr/css/viet-qr.css') }}">
<div class="mr4-lc-vietqr control-group {{ $className ?? '' }} {{ ($hideInput ?? false) ? 'hide-input' : '' }}">
    <div class="form-group">
        <label for="{{ $prefix }}consumer_account_information_qr">{{ __('mr4lc-vietqr.consumer_account_information') }}</label>
        <input type="file" name="consumer_account_information_qr" id="{{ $prefix }}consumer_account_information_qr" class="form-control" onchange="GetConsumerAccountInformation(this, '{{ $id }}', '{{ $prefix }}')" >
        <div class="mr4-lc-vietqr consumer_account_information_data {{ $className ?? '' }}" id="{{ $prefix }}consumer_account_information_data">
        </div>
    </div>
</div>
