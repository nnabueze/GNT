<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<ValidationResponse>

    <BillerID>{{$data['BillerID']}}</BillerID>
    
    <NextStep>{{$data['NextStep']}}</NextStep>

    <Param>
    <Key>ercasBillerId</Key>
    <Value>{{$data['ercasBillerId']}}</Value>
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

    <Param>
    <Key>subhead_key</Key>
    <Value>{{$data['subhead']}}</Value>
    </Param>

    <Param>
    <Key>Refcode</Key>
    <Value>{{$data['collection_key']}}</Value>
    </Param>


    <Param>
    <Key>collection_type</Key>
    <Value>{{$data['collection_type']}}</Value>
    </Param>

    <Param>
    <Key>tax</Key>
    <Value>{{$data['tax']}}</Value>
    </Param>
  
    @if(isset($data['Tin']))
    <Param>
    <Key>Tin</Key>
    <Value>{{$data['Tin']}}</Value>
    </Param>
    @endif
   
    <Param>
    <Key>name</Key>
    <Value>{{$data['name']}}</Value>
    </Param>

    <Param>
    <Key>phone</Key>
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

    @if(isset($data['email']))
        @if(empty($data['email']))
            <Param>
            <Key>email</Key>
            <Value></Value>
            </Param>
            @else
            <Param>
            <Key>email</Key>
            <Value>{{$data['email']}}</Value>
            </Param>
        @endif
    @endif

    @if(isset($data['payerid']))
    <Param>
    <Key>payerid</Key>
    <Value>{{$data['payerid']}}</Value>
    </Param>
    @endif
    
</ValidationResponse>

