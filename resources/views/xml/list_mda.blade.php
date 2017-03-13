<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<ValidationResponse>
	<NextStep>{{$mda['NextStep']}}</NextStep>
    @foreach($mda['mda'] as $value)
    <Param>
    <Key>{{$value->id}}</Key>
    <Value>{{$value->mda_name}}</Value>
    </Param>
    @endforeach

</ValidationResponse>

