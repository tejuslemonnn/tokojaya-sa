<select class="form-select form-select-solid form-select-lg fw-bold satuan" name="satuan_id"
    data-item-id="{{ $item->id }}" id="satuanSelect">
    @foreach ($satuans as $satuan)
        {{-- @php
            $firstChar = strtolower(substr($satuan->nama, 0, 1));
            $isKgOrG = $firstChar === 'k' || $firstChar === 'g';
            $isML = $firstChar === 'm' || $firstChar === 'l';
        @endphp

        @if ($isKgOrG || $isML)
            <option value="{{ $satuan->nama }}" {{ $item->satuan == $satuan->nama ? 'selected' : '' }}>
                {{ $satuan->nama }}
            </option>
        @else
            <option value="{{ $satuan->nama }}" {{ $item->satuan == $satuan->nama ? 'selected' : '' }}>
                {{ $satuan->nama }}
            </option>
        @endif --}}

        <option value="{{ $satuan->nama }}" {{ $item->satuan == $satuan->nama ? 'selected' : '' }}
            @if ($item->promoBundle && $satuan->nama !== 'pcs') disabled @endif>
            {{ $satuan->nama }}
        </option>
    @endforeach

</select>
