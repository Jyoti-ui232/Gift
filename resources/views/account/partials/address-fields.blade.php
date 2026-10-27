@php
    $address = $address ?? null;
@endphp

<div class="gb-field">
    <label for="name_{{ $address->id ?? 'new' }}">Full Name *</label>
    <input type="text"
           id="name_{{ $address->id ?? 'new' }}"
           name="name"
           value="{{ old('name', $address->name ?? '') }}"
           required
           maxlength="255">
    @error('name') <span class="gb-field-error">{{ $message }}</span> @enderror
</div>

<div class="gb-field">
    <label for="phone_{{ $address->id ?? 'new' }}">Phone *</label>
    <input type="tel"
           id="phone_{{ $address->id ?? 'new' }}"
           name="phone"
           value="{{ old('phone', $address->phone ?? '') }}"
           required
           maxlength="20">
    @error('phone') <span class="gb-field-error">{{ $message }}</span> @enderror
</div>

<div class="gb-field">
    <label for="address_line1_{{ $address->id ?? 'new' }}">Address Line 1 *</label>
    <input type="text"
           id="address_line1_{{ $address->id ?? 'new' }}"
           name="address_line1"
           value="{{ old('address_line1', $address->address_line1 ?? '') }}"
           required
           maxlength="255">
    @error('address_line1') <span class="gb-field-error">{{ $message }}</span> @enderror
</div>

<div class="gb-field">
    <label for="address_line2_{{ $address->id ?? 'new' }}">Address Line 2</label>
    <input type="text"
           id="address_line2_{{ $address->id ?? 'new' }}"
           name="address_line2"
           value="{{ old('address_line2', $address->address_line2 ?? '') }}"
           maxlength="255">
    @error('address_line2') <span class="gb-field-error">{{ $message }}</span> @enderror
</div>

<div class="gb-field">
    <label for="city_{{ $address->id ?? 'new' }}">City *</label>
    <input type="text"
           id="city_{{ $address->id ?? 'new' }}"
           name="city"
           value="{{ old('city', $address->city ?? '') }}"
           required
           maxlength="100">
    @error('city') <span class="gb-field-error">{{ $message }}</span> @enderror
</div>

<div class="gb-field">
    <label for="state_{{ $address->id ?? 'new' }}">State *</label>
    <input type="text"
           id="state_{{ $address->id ?? 'new' }}"
           name="state"
           value="{{ old('state', $address->state ?? '') }}"
           required
           maxlength="100">
    @error('state') <span class="gb-field-error">{{ $message }}</span> @enderror
</div>

<div class="gb-field">
    <label for="pincode_{{ $address->id ?? 'new' }}">Pincode *</label>
    <input type="text"
           id="pincode_{{ $address->id ?? 'new' }}"
           name="pincode"
           value="{{ old('pincode', $address->pincode ?? '') }}"
           required
           maxlength="10"
           inputmode="numeric">
    @error('pincode') <span class="gb-field-error">{{ $message }}</span> @enderror
</div>

<div class="gb-field">
    <label for="type_{{ $address->id ?? 'new' }}">Address Type</label>
    <select id="type_{{ $address->id ?? 'new' }}" name="type">
        @foreach(['home' => 'Home', 'office' => 'Office', 'other' => 'Other'] as $value => $label)
            <option value="{{ $value }}" {{ old('type', $address->type ?? 'home') === $value ? 'selected' : '' }}>
                {{ $label }}
            </option>
        @endforeach
    </select>
</div>

<div class="gb-field">
    <label class="gb-address-check">
        <input type="checkbox"
               name="is_default"
               value="1"
               {{ old('is_default', $address->is_default ?? false) ? 'checked' : '' }}>
        Set as default address
    </label>
</div>
