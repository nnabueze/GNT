<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<ValidationResponse>
	
    <Remittance>{{$data['Remittance']}}</Remittance>
    <BillerID>{{$data['BillerID']}}</BillerID>
    <Mda_key>{{$data['mda']}}</Mda_key>
    <NextStep>{{$data['NextStep']}}</NextStep>
   
    <Param>
    <Key>name</Key>
    <Value>{{$data['name']}}</Value>
    </Param>

    <Param>
    <Key>Phone</Key>
    <Value>{{$data['phone']}}</Value>
    </Param>

    @if($data['mda_category'] == 'lga')
    <Param>
    <Key>lga</Key>
    <Value>{{$data['mda_name']}}</Value>
    </Param>
    @else
    <Param>
    <Key>mda</Key>
    <Value>{{$data['mda_name']}}</Value>
    </Param>
    @endif

    <Param>
    <Key>amount</Key>
    <Value>{{$data['amount']}}</Value>
    </Param>
    
</ValidationResponse>

