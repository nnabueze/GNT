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

    <Field>
        @if($data['igr_name'] == "ERCAS_BAUCHI")
        <Name>lga</Name>
        @else
        <Name>mda</Name>
        @endif
        <Type>list</Type>
        <Required>false</Required>
        <Readonly>false</Readonly>
        <MaxLength>0</MaxLength>
        <Order>0</Order>
        <RequiredInNextStep>true</RequiredInNextStep>
        <AmountField>false</AmountField>
        @foreach($data['mda_list'] as $mda)
        <Item>
            <Name>{{$mda->mda_name}}</Name>
            <Value>{{$mda->mda_key}}</Value>
        </Item>
        @endforeach
    </Field>
    
</ValidationResponse>

