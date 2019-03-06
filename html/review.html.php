<?php if (!empty($review)): ?>
<li class="block review-list-item">
    <div class="block-row">
        <div class="block-row-col review-name"><?= $review->user_name ?></div>
        <div class="block-row-col text-right review-date"><?= date("Y-m-d H:i:s", strtotime($review->create_date)) ?></div>
    </div>
    <div class="block-row">
        <div class="block-row-col review-email"><?= $review->email ?></div>
    </div>
    <div class="block-row">
        <div class="block-row-col review-content">
            <?= $review->review_text ?>
        </div>
    </div>
</li>
<?php endif; ?>