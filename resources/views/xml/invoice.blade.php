<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<ValidationResponse>
	
   
    <BillerID>{{$data['BillerID']}}</BillerID>
    <NextStep>{{$data['NextStep']}}</NextStep>
    <ResponseCode>{{$data['ResponseCode']}}</ResponseCode>

    <PaymentDetail>
        <BillerAccountID>{{$data['biller_name']}}</BillerAccountID>
        <Amount>{{$data['amount']}}</Amount>
        <Fee>0</Fee>
        <Narration>Invoice Payment</Narration>
    </PaymentDetail>

    <Param>
    <Key>ercasBillerId</Key>
    <Value>{{$data['ercasBillerId']}}</Value>
    </Param>
    

    <Param>
    <Key>mda_key</Key>
    <Value>{{$data['mda']}}</Value>
    </Param>

    <Param>
    <Key>subhead_key</Key>
    <Value>{{$data['subhead']}}</Value>
    </Param>

    <Param>
    <Key>Invoice</Key>
    <Value>{{$data['Invoice']}}</Value>
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
    <Key>subhead</Key>
    <Value>{{$data['subhead_name']}}</Value>
    </Param>

    <Param>
    <Key>amount</Key>
    <Value>{{$data['amount']}}</Value>
    </Param>
    
</ValidationResponse>

