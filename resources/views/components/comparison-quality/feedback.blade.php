@props(['slug'])

<section class="info-panel comparison-feedback" data-comparison-feedback="{{ $slug }}" aria-labelledby="comparison-feedback-heading">
    <div>
        <span class="eyebrow">Your feedback</span>
        <h2 id="comparison-feedback-heading">Was this comparison helpful?</h2>
        <p>Your answer is stored only on this device.</p>
    </div>
    <div role="group" aria-label="Rate this comparison">
        <button class="btn" type="button" data-comparison-vote="yes" aria-pressed="false">👍 Yes</button>
        <button class="btn" type="button" data-comparison-vote="no" aria-pressed="false">👎 No</button>
    </div>
    <p data-comparison-feedback-status aria-live="polite"></p>
</section>
