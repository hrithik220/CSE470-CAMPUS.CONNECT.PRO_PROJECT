<?php $__env->startSection('title', 'Tutors — Campus Connect Pro'); ?>
<?php $__env->startSection('page_title', 'Tutors'); ?>

<?php $__env->startSection('content'); ?>
<div class="fade-in space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-white">All Tutors</h1>
            <p class="text-gray-400 text-sm">Find senior students and book tutoring sessions.</p>
        </div>
        <div class="flex gap-3">
            <a href="<?php echo e(route('tutors.create')); ?>" class="px-4 py-2 rounded-lg bg-brand-600 hover:bg-brand-700 text-white font-medium">Add New Tutor</a>
            <a href="<?php echo e(route('tutoring-sessions.index')); ?>" class="px-4 py-2 rounded-lg bg-purple-600 hover:bg-purple-700 text-white font-medium">View Sessions</a>
        </div>
    </div>

    <div class="grid md:grid-cols-2 gap-5">
        <?php $__empty_1 = true; $__currentLoopData = $tutors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tutor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="glass rounded-xl p-5 card-hover">
                <h3 class="text-xl font-bold text-white mb-2"><?php echo e($tutor->user->name); ?></h3>
                <p class="text-gray-300"><span class="font-semibold text-gray-100">Subjects:</span> <?php echo e($tutor->subjects); ?></p>
                <p class="text-gray-300"><span class="font-semibold text-gray-100">Rate:</span> <?php echo e($tutor->is_free ? 'Free' : '৳' . $tutor->hourly_rate . '/hour'); ?></p>
                <p class="text-gray-300"><span class="font-semibold text-gray-100">Availability:</span> <?php echo e($tutor->availability ?? 'Not given'); ?></p>
                <p class="text-gray-300"><span class="font-semibold text-gray-100">Location:</span> <?php echo e($tutor->meeting_location ?? 'Not given'); ?></p>
                <?php if($tutor->bio): ?>
                    <p class="text-gray-400 mt-2 text-sm"><?php echo e($tutor->bio); ?></p>
                <?php endif; ?>
                <a href="<?php echo e(route('tutoring-sessions.create', $tutor->id)); ?>" class="inline-block mt-4 px-4 py-2 rounded-lg bg-green-600 hover:bg-green-700 text-white font-medium">Book Session</a>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="glass rounded-xl p-6 text-gray-400">No tutor profiles found.</div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\nahid\Downloads\campus-connect-pro-complete-20-features-v2-fixed\campus-connect-pro-complete-20-features-v2-fixed\campus-connect-pro-complete\resources\views/tutors/index.blade.php ENDPATH**/ ?>