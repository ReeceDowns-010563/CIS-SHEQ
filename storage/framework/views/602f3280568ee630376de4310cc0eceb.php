Accident Report Assignment

Hello <?php echo e($coordinator->name); ?>,

You have been assigned as the coordinator for the following Accident report:

Accident ID: #<?php echo e(str_pad($incident->id, 6, '0', STR_PAD_LEFT)); ?>

Brief Description: <?php echo e($incident->brief_description); ?>

Date of Occurrence: <?php echo e($incident->date_of_occurrence?->format('d/m/Y') ?? 'Not specified'); ?>

Location: <?php echo e($incident->location ?? 'Not specified'); ?>

Status: <?php echo e(ucfirst($incident->status)); ?>


Please review the Accident details and begin your investigation.

View Accident Report: <?php echo e(route('incidents.show', $incident->id)); ?>


---
This is an automated notification. Please do not reply to this email.
<?php /**PATH /home/sites/29a/4/4aab0e4a5e/public_html/laravel12/resources/views/emails/coordinator-assigned-txt.blade.php ENDPATH**/ ?>