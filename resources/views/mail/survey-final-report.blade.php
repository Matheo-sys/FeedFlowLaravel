<!DOCTYPE html>
<html>

<head>
    <title>Rapport Final du Sondage</title>
</head>

<body>
    <h1>Rapport final : {{ $reportData['survey']->title }}</h1>

    <p><strong>Date de fin :</strong> {{ $reportData['survey']->end_date }}</p>
    <p><strong>Total de réponses :</strong> {{ $reportData['total_responses'] }}</p>

    <h2>Statistiques par question</h2>
    @foreach($reportData['statistics'] as $stat)
        <div>
            <h3>{{ $stat['question'] }}</h3>
            <p>Nombre de réponses : {{ $stat['response_count'] }}</p>
        </div>
    @endforeach
</body>

</html>