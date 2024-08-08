<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Holiday Plans</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
    <h1 class="my-5 text-center">{{$holidayplan['title']}}</h1>
    <h2>Description</h2>
    <p>{{$holidayplan['description']}}</p>
    <h2>Date: {{$holidayplan['date']}}</h2>
    <h2>Location: {{$holidayplan['location']}}</h2>
    <h2>Participants:</h2>
    <ul>
        @foreach ($holidayplan['participants'] as $participant)
            <li>{{ $participant['name'] }}</li>
        @endforeach
    </ul>
</body>
</html>