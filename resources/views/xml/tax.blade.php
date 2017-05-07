<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<ValidationResponse>

	<NextStep>{{$item['NextStep']}}</NextStep>
    <ResponseCode>{{$item['ResponseCode']}}</ResponseCode>

    <Param>
    <Key>ercasBillerId</Key>
    <Value>{{$item['ercasBillerId']}}</Value>
    </Param>
    
    <Param>
    <Key>tin</Key>
    <Value>{{$item['tin']}}</Value>
    </Param>

    <Param>
    <Key>page</Key>
    <Value>{{$item['page']}}</Value>
    </Param>
   
    <Param>
    <Key>name</Key>
    <Value>{{$item['name']}}</Value>
    </Param>

    <Param>
    <Key>Phone</Key>
    <Value>{{$item['phone']}}</Value>
    </Param>

    <Field>
    @if($item['biller_name'] == "ERCAS_BAUCHI")
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
        @foreach($item['mda_item'] as $mda)
        <Item>
            <Name>{{$mda->mda_name}}</Name>
            <Value>{{$mda->mda_key}}</Value>
        </Item>
        @endforeach
    </Field>
    
</ValidationResponse>

