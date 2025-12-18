<x-mail::message>

New answer from your survey  : **{{ $survey->title }}**.

<x-mail::button :url="route('surveys.show', $survey)">
    See the result
</x-mail::button>

Thanks,<br>
</x-mail::message>