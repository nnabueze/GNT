<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<ValidationResponse>
	<NextStep>{{$item['NextStep']}}</NextStep>
    @foreach($item['subheads'] as $value)
    <Param>
    <Key>{{$value->id}}</Key>
    <Value>{{$value->subhead_name}}</Value>
    </Param>
    @endforeach

</ValidationResponse>

