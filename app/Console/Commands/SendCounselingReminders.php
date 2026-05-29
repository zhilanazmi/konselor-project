<?php

namespace App\Console\Commands;

use App\Models\GroupCounseling;
use App\Models\IndividualCounseling;
use App\Models\ParentConsultation;
use App\Notifications\CounselingReminderNotification;
use Illuminate\Console\Command;

class SendCounselingReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'counseling:send-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send reminders for upcoming counseling sessions';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $tomorrow = now()->addDay()->startOfDay();
        $endOfTomorrow = now()->addDay()->endOfDay();

        // Individual Counseling Reminders
        $individualCounselings = IndividualCounseling::query()
            ->with(['counselor', 'student'])
            ->where('status', 'scheduled')
            ->whereBetween('scheduled_at', [$tomorrow, $endOfTomorrow])
            ->get();

        foreach ($individualCounselings as $counseling) {
            $counseling->counselor->notify(new CounselingReminderNotification(
                'Bimbingan Individu',
                $counseling->student->full_name.' - '.$counseling->category,
                $counseling->scheduled_at->format('d M Y, H:i'),
                route('guru-bk.individual-counselings.show', $counseling)
            ));
        }

        // Group Counseling Reminders
        $groupCounselings = GroupCounseling::query()
            ->with(['counselor'])
            ->where('status', 'scheduled')
            ->whereBetween('scheduled_at', [$tomorrow, $endOfTomorrow])
            ->get();

        foreach ($groupCounselings as $counseling) {
            $counseling->counselor->notify(new CounselingReminderNotification(
                'Bimbingan Kelompok',
                $counseling->topic,
                $counseling->scheduled_at->format('d M Y, H:i'),
                route('guru-bk.group-counselings.show', $counseling)
            ));
        }

        // Parent Consultation Reminders
        $parentConsultations = ParentConsultation::query()
            ->with(['counselor', 'student', 'guardian'])
            ->where('status', 'scheduled')
            ->whereBetween('scheduled_at', [$tomorrow, $endOfTomorrow])
            ->get();

        foreach ($parentConsultations as $consultation) {
            $consultation->counselor->notify(new CounselingReminderNotification(
                'Konsultasi Orang Tua',
                $consultation->topic.' - '.$consultation->student->full_name,
                $consultation->scheduled_at->format('d M Y, H:i'),
                route('guru-bk.parent-consultations.show', $consultation)
            ));
        }

        $totalReminders = $individualCounselings->count() + $groupCounselings->count() + $parentConsultations->count();

        $this->info("Sent {$totalReminders} counseling reminders successfully!");
    }
}
