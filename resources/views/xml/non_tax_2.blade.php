<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<ValidationResponse>

    <BillerID>{{$data['BillerID']}}</BillerID>
    
    <NextStep>{{$data['NextStep']}}</NextStep>
    <ResponseCode>{{$data['ResponseCode']}}</ResponseCode>

    <Param>
    <Key>ercasBillerId</Key>
    <Value>{{$data['ercasBillerId']}}</Value>
    </Param>
   
    <Param>
    <Key>name</Key>
    <Value>{{$data['name']}}</Value>
    </Param>

    <Param>
    <Key>payerid</Key>
    <Value>{{$data['payerid']}}</Value>
    </Param>

    @if(isset($data['mda']))
    <Param>
    <Key>mda_key</Key>
    <Value>{{$data['mda']}}</Value>
    </Param>
    @else
    <Param>
    <Key>mda_key</Key>
    <Value>{{$data['lga']}}</Value>
    </Param>
    @endif

    <Field>
        <Name>subhead</Name>
        <Type>list</Type>
        <Required>false</Required>
        <Readonly>false</Readonly>
        <MaxLength>0</MaxLength>
        <Order>0</Order>
        <RequiredInNextStep>true</RequiredInNextStep>
        <AmountField>false</AmountField>
        @foreach($data['subhead_list'] as $subhead)
        <Item>
            <Name>{{$subhead->subhead_name}}</Name>
            <Value>{{$subhead->subhead_key}}</Value>
        </Item>
        @endforeach
    </Field>
    
</ValidationResponse>

