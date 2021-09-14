<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Data</title>
</head>
<body>

    @foreach ($data as $key => $value)

        @if(!in_array($key,['honeypot','accessKey','redirectTo']))
        
            <b> {{$key}} : </b> {{$value}}  <br>
        
        @endif

    @endforeach
    

</body>
</html>