<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<ValidationResponse>
	<Refcode>{{$tin['refcode']}}</Refcode>
	<NextStep>{{$tin['NextStep']}}</NextStep>
	<Param>
		<Key>name</Key>
		<Value>{{$tin['name']}}</Value>
	</Param>
	<Param>
		<Key>phone</Key>
		<Value>{{$tin['phone']}}</Value>
	</Param>
	<Param>
		<Key>address</Key>
		<Value>{{$tin['address']}}</Value>
	</Param>
	@if($tin['email'])
	<Param>
		<Key>email</Key>
		<Value>{{$tin['email']}}</Value>
	</Param>
	@endif

</ValidationResponse>

