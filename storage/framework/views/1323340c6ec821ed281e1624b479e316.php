<?php $__env->startSection('title', 'Book Tutoring Session — Campus Connect Pro'); ?>
<?php $__env->startSection('page_title', 'Book Session'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-3xl mx-auto fade-in">
    <div class="glass rounded-xl p-6">
        <h1 class="text-2xl font-bold text-white mb-5">Book Session</h1>
        <div class="mb-5 rounded-lg bg-white/5 p-4">
            <p class="text-gray-200"><span class="font-semibold">Tutor:</span> <?php echo e($tutor->user->name); ?></p>
            <p class="text-gray-300"><span class="font-semibold">Subjects:</span> <?php echo e($tutor->subjects); ?></p>
            <p class="text-gray-300"><span class="font-semibold">Availability:</span> <?php echo e($tutor->availability ?? 'Not given'); ?></p>
        </div>
        <form method="POST" action="<?php echo e(route('tutoring-sessions.store')); ?>" class="space-y-4">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="tutor_profile_id" value="<?php echo e($tutor->id); ?>">
            <div>
                <label class="block text-sm text-gray-300 mb-1">Session Date</label>
                <input type="date" name="session_date" class="w-full rounded-lg bg-surface-900 border border-brand-500/20 px-3 py-2 text-white" required>
            </div>
            <div>
                <label class="block text-sm text-gray-300 mb-1">Session Time</label>
                <input type="time" name="session_time" class="w-full rounded-lg bg-surface-900 border border-brand-500/20 px-3 py-2 text-white" required>
            </div>
            <div>
                <label class="block text-sm text-gray-300 mb-1">Meeting Location</label>
                <input type="text" name="meeting_location" value="<?php echo e($tutor->meeting_location); ?>" class="w-full rounded-lg bg-surface-900 border border-brand-500/20 px-3 py-2 text-white" placeholder="Library, Room 302" required>
            </div>
            <button class="px-5 py-2 rounded-lg bg-green-600 hover:bg-green-700 text-white font-semibold">Confirm Booking</button>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\nahid\Downloads\campus-connect-pro-complete-20-features-v2-fixed\campus-connect-pro-complete-20-features-v2-fixed\campus-connect-pro-complete\resources\views/tutoring-sessions/create.blade.php ENDPATH**/ ?>