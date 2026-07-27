@props(['slug'])
<section class="info-panel tool-quality-feedback" data-tool-feedback data-tool-slug="{{ $slug }}" aria-labelledby="tool-feedback-heading">
    <div><span class="eyebrow">Help us improve</span><h2 id="tool-feedback-heading">Was this page helpful?</h2><p>Your response stays in this browser and helps you remember your feedback.</p></div>
    <div role="group" aria-label="Was this page helpful?">
        <button class="btn" type="button" data-feedback-value="yes" aria-pressed="false">👍 Yes</button>
        <button class="btn" type="button" data-feedback-value="no" aria-pressed="false">👎 No</button>
    </div>
    <p class="copy-status" data-feedback-status aria-live="polite"></p>
</section>
