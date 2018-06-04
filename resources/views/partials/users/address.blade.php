<div id="addressForm">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 top3 text-center">
            <h2>Address</h2>
        </div>
    </div>

    <input type="hidden" name="address_id" value="@if(!empty($address)){{$address->id}}@endif">

    <div class="top1 form-group">
        <label for="country" class="col-lg-4 col-form-label line-label">
            Add an Address <span>*</span>
        </label>
        <div class="col-lg-4">
            <select id="country" name="country" class="form-line"
                    data-url="{{ route('system.states') }}" required>
                <option value="">Country</option>
                @foreach ($countries as $key => $value)
                    <option value="{{$key}}"
                            @if(!empty($address)) @if ($address->country == $key) selected @endif @endif>
                        {{$value}}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-lg-2">
            <select id="state" name="state" class="form-line"
                    data-url="{{ route('system.cities') }}"
                    required>
                <option value="">State</option>
                @if (!empty($states))
                    @foreach ($states as $state)
                        <option value="{{$state}}"
                                @if(!empty($address)) @if ($address->state == $state) selected @endif @endif>
                            {{$state}}
                        </option>
                    @endforeach
                @endif
            </select>
        </div>

        <div class="col-lg-2">
            <select id="city" name="city" class="form-line" required>
                <option value="">City</option>
                @if (!empty($cities))
                    @foreach ($cities as $city)
                        <option value="{{$city}}"
                                @if(!empty($address)) @if ($address->city == $city) selected @endif @endif>
                            {{$city}}
                        </option>
                    @endforeach
                @endif
            </select>
        </div>

    </div>


    <div class="top1 form-group">
        <label for="address" class="col-lg-4 col-form-label line-label"></label>
        <div class="col-lg-4">
            <input class="form-line" type="text" title="address" name="address"
                   placeholder="Address"
                   value="@if(!empty($address)){{$address->address}}@endif"
                   required/>
        </div>

        <div class="col-lg-4">
            <input class="form-line" type="text" title="address-description"
                   name="address_description"
                   placeholder="e.g.: Apartment 7, near a building, etc "
                   value="@if(!empty($address)){{$address->description}}@endif"
                   required/>
        </div>
    </div>

    <div class="top1 form-group">
        <label for="address_name_select" class="col-lg-4 col-form-label line-label"></label>
        <div class="col-lg-3">
            <select id="addressNameSelect" name="address_name_select" class="form-line" required>
                <option value="home" @if(!empty($address)) @if ($address->name == 'home') selected @endif @endif>Home</option>
                <option value="work" @if(!empty($address)) @if ($address->name == 'work') selected @endif @endif>Work</option>
                <option value="0" @if(!empty($address)) @if ($address->name != 'home' && $address->name != 'work') selected @endif @endif>Other</option>
            </select>
        </div>

        <div class="col-lg-3">
            <input class="form-line" type="text" title="address-name"
                   name="address_name"
                   id="addressName"
                   placeholder="e.g.: Grandma's house"
                   value="@if(!empty($address)){{$address->name}}@endif"
                   required
                   @if(!empty($address)) @if ($address->name != 'home' && $address->name != 'work') style="display: block;" @else style="display: none;" @endif @endif />
        </div>

        <div class="col-lg-2">
            <input class="form-line" type="text" title="zip-code" name="zip_code"
                   placeholder="Zip Code"
                   value="@if(!empty($address)){{$address->zip_code}}@endif"/>
        </div>
    </div>

</div>