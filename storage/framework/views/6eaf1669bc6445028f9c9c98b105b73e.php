<?php $__env->startSection('title', 'Doubt Forum — Campus Connect Pro'); ?>
<?php $__env->startSection('page_title', 'Doubt Forum'); ?>

<?php $__env->startSection('content'); ?>
<div class="fade-in space-y-6 max-w-5xl mx-auto">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-white">Doubt Forum</h1>
            <p class="text-gray-400 text-sm">Ask course-specific questions anonymously and vote helpful answers.</p>
        </div>
        <div class="flex gap-3">
            <a href="<?php echo e(route('tutors.index')); ?>" class="px-4 py-2 rounded-lg bg-brand-600 hover:bg-brand-700 text-white">Tutors</a>
            <a href="<?php echo e(route('tutoring-sessions.index')); ?>" class="px-4 py-2 rounded-lg bg-purple-600 hover:bg-purple-700 text-white">Sessions</a>
        </div>
    </div>

    <div class="glass rounded-xl p-5">
        <h2 class="text-lg font-semibold text-white mb-4">Post a Question</h2>
        <form method="POST" action="<?php echo e(route('doubt-forum.question')); ?>" class="space-y-3">
            <?php echo csrf_field(); ?>
            <input name="course_code" class="w-full rounded-lg bg-surface-900 border border-brand-500/20 px-3 py-2 text-white" placeholder="Course Code, e.g. CSE470" required>
            <textarea name="question" class="w-full rounded-lg bg-surface-900 border border-brand-500/20 px-3 py-2 text-white" rows="3" placeholder="Write your question" required></textarea>
            <label class="flex items-center gap-2 text-gray-300"><input type="checkbox" name="is_anonymous" checked> Post anonymously</label>
            <button class="px-5 py-2 rounded-lg bg-green-600 hover:bg-green-700 text-white font-semibold">Post Question</button>
        </form>
    </div>

    <?php $__currentLoopData = $questions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $q): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="glass rounded-xl p-5">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <h3 class="text-xl font-bold text-white"><?php echo e($q->course_code); ?></h3>
                    <p class="text-gray-300 mt-2"><?php echo e($q->question); ?></p>
                    <p class="text-gray-500 text-sm mt-2">Asked by: <?php echo e($q->is_anonymous ? 'Anonymous Student' : $q->user->name); ?></p>
                </div>
            </div>
            <hr class="border-white/10 my-4">
            <h4 class="font-semibold text-gray-200 mb-3">Answers</h4>
            <?php $__empty_1 = true; $__currentLoopData = $q->answers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $a): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="rounded-lg bg-white/5 border border-white/10 p-4 mb-3">
                    <p class="text-gray-200"><?php echo e($a->answer); ?></p>
                    <p class="text-gray-500 text-sm mt-1">Answered by: <?php echo e($a->user->name); ?></p>
                    <div class="flex items-center gap-3 mt-3">
                        <form method="POST" action="<?php echo e(route('doubt-forum.upvote', $a->id)); ?>"><?php echo csrf_field(); ?><button class="px-3 py-1 rounded bg-blue-600 hover:bg-blue-700 text-white">Upvote</button></form>
                        <span class="text-gray-300">Votes: <?php echo e($a->votes); ?></span>
                        <form method="POST" action="<?php echo e(route('doubt-forum.downvote', $a->id)); ?>"><?php echo csrf_field(); ?><button class="px-3 py-1 rounded bg-red-600 hover:bg-red-700 text-white">Downvote</button></form>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <p class="text-gray-500">No answers yet.</p>
            <?php endif; ?>
            <form method="POST" action="<?php echo e(route('doubt-forum.answer', $q->id)); ?>" class="mt-4">
                <?php echo csrf_field(); ?>
                <textarea name="answer" class="w-full rounded-lg bg-surface-900 border border-brand-500/20 px-3 py-2 text-white" rows="2" placeholder="Write an answer" required></textarea>
                <button class="mt-2 px-4 py-2 rounded-lg bg-brand-600 hover:bg-brand-700 text-white">Submit Answer</button>
            </form>
        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\nahid\Downloads\campus-connect-pro-complete-20-features-v2-fixed\campus-connect-pro-complete-20-features-v2-fixed\campus-connect-pro-complete\resources\views/doubt-forum/index.blade.php ENDPATH**/ ?>