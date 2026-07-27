@props(['slug'])

<section class="info-panel authority-feedback" data-authority-feedback="{{ $slug }}" aria-labelledby="authority-feedback-heading">
    <div><span class="eyebrow">Your feedback</span><h2 id="authority-feedback-heading">Was this topic helpful?</h2><p>Your response is stored only on this device.</p></div>
    <div role="group" aria-label="Rate this topic"><button class="btn" type="button" data-authority-vote="yes" aria-pressed="false">👍 Yes</button><button class="btn" type="button" data-authority-vote="no" aria-pressed="false">👎 No</button></div>
    <p data-authority-feedback-status aria-live="polite"></p>
</section>
