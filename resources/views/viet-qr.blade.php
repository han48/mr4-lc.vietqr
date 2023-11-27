@php
    $prefix = $id ?? '';
    if (strlen($prefix) > 0) {
        $prefix = $prefix . "_";
    }
@endphp
<script src="{{ asset('vendor/mr4-lc/vietqr/js/viet-qr.js') }}"></script>
<link rel="stylesheet" href="{{ asset('vendor/mr4-lc/vietqr/css/viet-qr.css') }}">
<div class="mr4-lc-vietqr control-group {{ $className ?? '' }} {{ ($hideInput ?? false) ? 'hide-input' : '' }}">
    <div class="form-group">
        <label for="{{ $prefix }}account_id">{{ __('mr4lc-vietqr.account_id') }}</label>
        <select name="account_id" id="{{ $prefix }}account_id" class="form-control" onselect="LoadQrCode('{{ $prefix }}')">
            @php
                $items = \Mr4Lc\VietQr\Models\VietqrInformation::where('status', 1)->get();
            @endphp
            @foreach ($items as $item)
                <option value="{{ $item->id }}">{{ $item->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group">
        <label for="{{ $prefix }}transaction_currency">{{ __('mr4lc-vietqr.transaction_currency') }}</label>
        <select name="transaction_currency" id="{{ $prefix }}transaction_currency" class="form-control" onselect="LoadQrCode('{{ $prefix }}')">
            @php
                $items = __('mr4lc-vietqr.transaction_currencies');
            @endphp
            @foreach ($items as $key => $value)
                <option value="{{ $key }}" {{ $key . "" === config('mr4vietqr.defaut.transaction_currency') ? 'selected' : '' }}>{{ $value }}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group">
        <label for="{{ $prefix }}country_code">{{ __('mr4lc-vietqr.country_code') }}</label>
        <select name="country_code" id="{{ $prefix }}country_code" class="form-control" onselect="LoadQrCode('{{ $prefix }}')">
            @php
                $items = __('mr4lc-vietqr.country_codes');
            @endphp
            @foreach ($items as $key => $value)
                <option value="{{ $key }}" {{ $key . "" === config('mr4vietqr.defaut.country_code') ? 'selected' : '' }}>{{ $value }}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group">
        <label for="{{ $prefix }}transaction_amount">{{ __('mr4lc-vietqr.transaction_amount') }}</label>
        <input type="number" name="transaction_amount" id="{{ $prefix }}transaction_amount" class="form-control" onchange="LoadQRCode('{{ $prefix }}')">
    </div>
    <div class="form-group">
        <label for="{{ $prefix }}message">{{ __('mr4lc-vietqr.message') }}</label>
        <input type="text" name="message" id="{{ $prefix }}message" class="form-control" onchange="LoadQRCode('{{ $prefix }}')">
    </div>
</div>
<div class="mr4-lc-vietqr qr-image {{ $className ?? '' }}" id="{{ $prefix }}vietqr-image">
</div>
