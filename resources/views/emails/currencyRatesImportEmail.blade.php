@component('mail::message')
# Introduction

<h3>
    {{ $title }},
</h3>
<p>
    {{ $resultMessage }}
</p>

{{--
->with('title', $this->title)
->with('successResult', $this->successResult)
->with('resultMessage', $this->resultMessage);
--}}


@component('mail::button', ['url' => ''])
Button Text
@endcomponent

@if( !empty($additiveVars['support_signature']) )
    {{ $additiveVars['support_signature'] }}
@endif
@endcomponent
