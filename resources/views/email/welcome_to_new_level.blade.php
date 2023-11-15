@component('mail::message')
# Congratulations
<p>
    <span style="color: #727272"> Dear {{$name}}: </span>
    <br/>
    <span style="color: black"> You are promoted to <b>{{$levelName}}</b> level</span>
</p>

<a href="{{$logoAddress}}" download="{{$levelName}}Logo">Download Logo</a>
<p>
    <span style="color: #727272">your level code : <span style="color: black"><b>{{$code}}</b></span></span>
</p>
<br/>
<hr/>
Best regards,<br>
@endcomponent
