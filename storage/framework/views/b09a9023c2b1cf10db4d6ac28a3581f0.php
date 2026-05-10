<?php $__env->startSection('title', 'Create Tutor Profile — Campus Connect Pro'); ?>
<?php $__env->startSection('page_title', 'Create Tutor Profile'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-3xl mx-auto fade-in">
    <div class="glass rounded-xl p-6">
        <h1 class="text-2xl font-bold text-white mb-5">Create Tutor Profile</h1>
        <form method="POST" action="<?php echo e(route('tutors.store')); ?>" class="space-y-4">
            <?php echo csrf_field(); ?>
            <div>
                <label class="block text-sm text-gray-300 mb-1">Subjects</label>
                <input name="subjects" value="<?php echo e(old('subjects')); ?>" class="w-full rounded-lg bg-surface-900 border border-brand-500/20 px-3 py-2 text-white" placeholder="CSE101, MAT110, Physics" required>
            </div>
            <div>
                <label class="block text-sm text-gray-300 mb-1">Hourly Rate</label>
                <input type="number" step="0.01" name="hourly_rate" value="<?php echo e(old('hourly_rate')); ?>" class="w-full rounded-lg bg-surface-900 border border-brand-500/20 px-3 py-2 text-white" placeholder="500">
            </div>
            <label class="flex items-center gap-2 text-gray-300"><input type="checkbox" name="is_free" class="rounded"> I provide tutoring for free</label>
            <div>
                <label class="block text-sm text-gray-300 mb-1">Bio</label>
                <textarea name="bio" class="w-full rounded-lg bg-surface-900 border border-brand-500/20 px-3 py-2 text-white" rows="4" placeholder="Write about your experience"><?php echo e(old('bio')); ?></textarea>
            </div>
            <div>
                <label class="block text-sm text-gray-300 mb-1">Availability</label>
                <input name="availability" value="<?php echo e(old('availability')); ?>" class="w-full rounded-lg bg-surface-900 border border-brand-500/20 px-3 py-2 text-white" placeholder="Sat-Mon 3PM-6PM">
            </div>
            <div>
                <label class="block text-sm text-gray-300 mb-1">Meeting Location</label>
                <input name="meeting_location" value="<?php echo e(old('meeting_location')); ?>" class="w-full rounded-lg bg-surface-900 border border-brand-500/20 px-3 py-2 text-white" placeholder="Library 2nd Floor / NSU Library">
            </div>
            <button class="px-5 py-2 rounded-lg bg-brand-600 hover:bg-brand-700 text-white font-semibold">Save Tutor Profile</button>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\nahid\Downloads\campus-connect-pro-complete-20-features-v2-fixed\campus-connect-pro-complete-20-features-v2-fixed\campus-connect-pro-complete\resources\views/tutors/create.blade.php ENDPATH**/ ?>