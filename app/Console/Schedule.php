protected function schedule(Schedule $schedule)
{
$schedule->command('surveys:check-for-close')
->dailyAt('00:01');
}