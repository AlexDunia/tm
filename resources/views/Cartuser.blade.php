<h1> Cart page </h1>
@foreach($mycart as $onewelcome)
<a> {{$onewelcome['cname']}} </a>
<a href="{{url('/delete', $onewelcome->id)}}""> Delete me </a>
<br/>
@endforeach