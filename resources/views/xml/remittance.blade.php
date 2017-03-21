<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<ValidationResponse>
	
   
    <BillerID>{{$data['BillerID']}}</BillerID>
    <NextStep>{{$data['NextStep']}}</NextStep>
    
    <Param>
    <Key>ercasBillerId</Key>
    <Value>{{$data['ercasBillerId']}}</Value>
    </Param>

    <Param>
    <Key>mda_key</Key>
    <Value>{{$data['mda']}}</Value>
    </Param>

    <Param>
    <Key>Remittance</Key>
    <Value>{{$data['Remittance']}}</Value>
    </Param>

    <Param>
    <Key>refcode</Key>
    <Value>{{$data['refcode']}}</Value>
    </Param>

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

