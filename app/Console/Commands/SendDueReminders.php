<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Activity;
use App\Mail\ActivityDueReminder;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class SendDueReminders extends Command
{
    protected $signature = 'reminders:send-due';
    protected $description = 'Send email reminders to students for activities due today and tomorrow';

    public function handle()
    {
        $today = Carbon::today();
        $tomorrow = Carbon::tomorrow();

        $this->info('🔍 Checking for activities due today and tomorrow...');
        $this->info('Today is: ' . $today->toDateString());
        $this->info('Tomorrow is: ' . $tomorrow->toDateString());

        // Activities due today (not archived)
        $dueToday = Activity::whereDate('due_date', $today)
                            ->where('is_archived', 0)
                            ->with(['section', 'students'])
                            ->get();

        $this->info("\n🔴 Found {$dueToday->count()} activities due today");

        $emailsSentToday = 0;
        foreach ($dueToday as $activity) {
            $students = $activity->students()
                                 ->where('section_members.is_archived', 0)
                                 ->get();

            $this->info("\n📚 Activity: {$activity->name}");
            $this->info("   Section ID: {$activity->section_id}");
            $this->info("   Students in section: {$students->count()}");

            foreach ($students as $student) {
                if ($student->email) {
                    try {
                        Mail::to($student->email)->send(new ActivityDueReminder($activity, 0));
                        $this->info("   ✓ Sent to: {$student->name} ({$student->email})");
                        $emailsSentToday++;
                    } catch (\Exception $e) {
                        $this->error("   ✗ Failed to send to {$student->email}: " . $e->getMessage());
                    }
                } else {
                    $this->warn("   ⚠ Student '{$student->name}' has no email address");
                }
            }
        }

        // Activities due tomorrow (not archived)
        $dueTomorrow = Activity::whereDate('due_date', $tomorrow)
                               ->where('is_archived', 0)
                               ->with(['section', 'students'])
                               ->get();

        $this->info("\n🟡 Found {$dueTomorrow->count()} activities due tomorrow");

        $emailsSentTomorrow = 0;
        foreach ($dueTomorrow as $activity) {
            $students = $activity->students()
                                 ->where('section_members.is_archived', 0)
                                 ->get();

            $this->info("\n📚 Activity: {$activity->name}");
            $this->info("   Section ID: {$activity->section_id}");
            $this->info("   Students in section: {$students->count()}");

            foreach ($students as $student) {
                if ($student->email) {
                    try {
                        Mail::to($student->email)->send(new ActivityDueReminder($activity, 1));
                        $this->info("   ✓ Sent to: {$student->name} ({$student->email})");
                        $emailsSentTomorrow++;
                    } catch (\Exception $e) {
                        $this->error("   ✗ Failed to send to {$student->email}: " . $e->getMessage());
                    }
                } else {
                    $this->warn("   ⚠ Student '{$student->name}' has no email address");
                }
            }
        }

        $totalEmails = $emailsSentToday + $emailsSentTomorrow;
        $this->info("\n📧 Total emails sent: {$totalEmails}");
        $this->info("   - Due today: {$emailsSentToday}");
        $this->info("   - Due tomorrow: {$emailsSentTomorrow}");

        return Command::SUCCESS;
    }
}
