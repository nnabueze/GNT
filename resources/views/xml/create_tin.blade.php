<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<ValidationResponse>
	<NextStep>{{$tin['NextStep']}}</NextStep>
	<Param>
		<Key>refcode</Key>
		<Value>{{$tin['refcode']}}</Value>
	</Param>
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
	@if(isset($tin['email']))
		@if(empty($tin['email']))
	<Param>
		<Key>email</Key>
		<Value></Value>
	</Param>
		@else
		<Param>
			<Key>email</Key>
			<Value>{{$tin['email']}}</Value>
		</Param>
		@endif
	@endif

</ValidationResponse>

