<select class="form-control input-sm select-status"
        data-url="{{ route("requirement.service.status.update", [$requirementServiceId]) }}">
    @foreach($status as $s)
        <option value="{{$s->id}}"
                @if($s->id == $requirementServiceStatusId) selected @endif
                href="">
            {{$s->name}}
        </option>
    @endforeach
</select>
