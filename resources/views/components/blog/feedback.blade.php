@props(['slug'])

<section class="info-panel blog-feedback" data-article-feedback="{{ $slug }}" aria-labelledby="article-feedback-heading">
    <div>
        <span class="eyebrow">Your feedback</span>
        <h2 id="article-feedback-heading">Was this article helpful?</h2>
        <p>Your response stays on this device. No account or database is used.</p>
    </div>
    <div class="blog-feedback-actions" role="group" aria-label="Rate this article">
        <button class="btn" type="button" data-article-vote="yes" aria-pressed="false">👍 Yes</button>
        <button class="btn" type="button" data-article-vote="no" aria-pressed="false">👎 No</button>
    </div>
    <p class="blog-feedback-status" data-article-feedback-status aria-live="polite"></p>
</section>
