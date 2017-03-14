<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<ValidationResponse>
	
    <Tin>{{$data['Tin']}}</Tin>
    <BillerID>{{$data['BillerID']}}</BillerID>
    <Mda_key>{{$data['mda']}}</Mda_key>
    <subhead_key>{{$data['subhead']}}</subhead_key>
    <Refcode>{{$data['collection_key']}}</Refcode>
    <collection_type>{{$data['collection_type']}}</collection_type>
    <tax>{{$data['tax']}}</tax>
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
    <Key>subhead</Key>
    <Value>{{$data['subhead_name']}}</Value>
    </Param>

    <Param>
    <Key>period</Key>
    <Value>{{$data['start_date']}} / {{$data['end_date']}}</Value>
    </Param>

    <Param>
    <Key>amount</Key>
    <Value>{{$data['amount']}}</Value>
    </Param>
    
</ValidationResponse>

