<!DOCTYPE html>
<html>

<head>
    <title>Rapport Quotidien</title>
</head>

<body>
    <h1>Rapport pour votre sondage : {{ $survey->title }}</h1>
    <p>Bonjour,</p>
    <p>Hier, votre sondage a reçu <strong>{{ $count }}</strong> nouvelles réponses.</p>
    <p>Connectez-vous à votre tableau de bord pour voir les détails.</p>
    <br>
    <p>Cordialement,<br>L'équipe FeedFlow</p>
</body>

</html>