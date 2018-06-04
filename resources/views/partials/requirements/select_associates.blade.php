<select class="form-control input-sm select-associate"
        data-url="{{ route("requirement.service.associate.update", [$requirementServiceId]) }}">
    <option value="0">Associate</option>
    @foreach($associates as $associate)
        <option value="{{$associate->id}}"
                @if($associate->id == $associateUserId) selected @endif
                href="">
            {{ $associate->first_name . ' ' . $associate->last_name . ':' . $associate->phone_1 . '(' . $associate->address->first()->city . ')'}}
        </option>
    @endforeach
</select>
