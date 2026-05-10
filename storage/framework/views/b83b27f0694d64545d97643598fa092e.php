<?php $__env->startSection('title', 'Tutoring Sessions — Campus Connect Pro'); ?>
<?php $__env->startSection('page_title', 'Tutoring Sessions'); ?>

<?php $__env->startSection('content'); ?>
<div class="fade-in space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-white">Tutoring Sessions</h1>
            <p class="text-gray-400 text-sm">Manage booked sessions, reminder countdowns, and meeting maps.</p>
        </div>
        <div class="flex gap-3">
            <a href="<?php echo e(route('tutors.index')); ?>" class="px-4 py-2 rounded-lg bg-brand-600 hover:bg-brand-700 text-white font-medium">View Tutors</a>
            <a href="<?php echo e(route('tutors.create')); ?>" class="px-4 py-2 rounded-lg bg-green-600 hover:bg-green-700 text-white font-medium">Add New Tutor</a>
        </div>
    </div>

    <div class="grid gap-5">
        <?php $__empty_1 = true; $__currentLoopData = $sessions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $session): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <?php
                $sessionDateTime = \Carbon\Carbon::parse($session->session_date . ' ' . $session->session_time);
                $now = \Carbon\Carbon::now();
                if ($now->lessThan($sessionDateTime)) {
                    $diff = $now->diff($sessionDateTime);
                    $timeRemaining = $diff->d . ' days ' . $diff->h . ' hours ' . $diff->i . ' minutes';
                } else {
                    $timeRemaining = 'Session started or completed';
                }
            ?>
            <div class="glass rounded-xl p-5 card-hover">
                <h3 class="text-xl font-bold text-white mb-3"><?php echo e($session->tutorProfile->user->name); ?></h3>
                <p class="text-gray-300"><span class="font-semibold text-gray-100">Student:</span> <?php echo e($session->student->name); ?></p>
                <p class="text-gray-300"><span class="font-semibold text-gray-100">Date:</span> <?php echo e($session->session_date); ?></p>
                <p class="text-gray-300"><span class="font-semibold text-gray-100">Time:</span> <?php echo e($session->session_time); ?></p>
                <p class="text-gray-300"><span class="font-semibold text-gray-100">Location:</span> <?php echo e($session->meeting_location); ?></p>
                <p class="text-gray-300"><span class="font-semibold text-gray-100">Status:</span> <?php echo e(ucfirst($session->status)); ?></p>
                <div class="mt-4 rounded-lg bg-blue-500/10 border border-blue-500/20 text-blue-300 px-4 py-3 font-semibold">Time Remaining: <?php echo e($timeRemaining); ?></div>
                <iframe width="100%" height="260" class="mt-4 rounded-lg" style="border:0" loading="lazy" allowfullscreen src="https://www.google.com/maps?q=<?php echo e(urlencode($session->meeting_location)); ?>&output=embed"></iframe>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="glass rounded-xl p-6 text-gray-400">No tutoring sessions booked yet.</div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\nahid\Downloads\campus-connect-pro-complete-20-features-v2-fixed\campus-connect-pro-complete-20-features-v2-fixed\campus-connect-pro-complete\resources\views/tutoring-sessions/index.blade.php ENDPATH**/ ?>