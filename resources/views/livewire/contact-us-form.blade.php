<div>
    @if($successMessage)
        <div class="mb-5 p-4 rounded-lg bg-teal/10 border border-teal text-teal font-semibold">
            {{ $successMessage }}
        </div>
    @endif

    <form wire:submit.prevent="submit" class="space-y-5">
        <div class="grid sm:grid-cols-2 gap-4">
            <div class="field">
                <label>Name</label>
                <input type="text" wire:model="name" placeholder="Your name">
                @error('name') <span class="text-xs text-margin mt-1">{{ $message }}</span> @enderror
            </div>
            <div class="field">
                <label>Email</label>
                <input type="email" wire:model="email" placeholder="you@example.com">
                @error('email') <span class="text-xs text-margin mt-1">{{ $message }}</span> @enderror
            </div>
        </div>
        <div class="field">
            <label>What's this about?</label>
            <select wire:model="category">
                <option>General question</option>
                <option>Payment or billing issue</option>
                <option>Problem with a paper or question</option>
                <option>Account help</option>
                <option>Something else</option>
            </select>
            @error('category') <span class="text-xs text-margin mt-1">{{ $message }}</span> @enderror
        </div>
        <div class="field">
            <label>Message</label>
            <textarea rows="5" wire:model="message" placeholder="Tell us what's going on..."></textarea>
            @error('message') <span class="text-xs text-margin mt-1">{{ $message }}</span> @enderror
        </div>

        <div>
            <div wire:ignore>
                <div class="g-recaptcha" data-sitekey="{{ env('RECAPTCHA_SITE_KEY') }}" data-callback="handleRecaptchaCallback"></div>
            </div>
            @error('recaptchaToken') <span class="text-xs text-margin mt-1">{{ $message }}</span> @enderror
        </div>

        <button type="submit" class="w-full sm:w-auto rounded-lg bg-teal text-paper font-semibold px-8 py-3 hover:bg-teal/90 transition flex items-center justify-center gap-2" wire:loading.attr="disabled">
            <span wire:loading.remove wire:target="submit">Send message</span>
            <span wire:loading wire:target="submit">Sending...</span>
        </button>
    </form>

    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <script>
        function handleRecaptchaCallback(token) {
            @this.set('recaptchaToken', token);
        }
        
        document.addEventListener('livewire:init', () => {
            Livewire.on('reset-recaptcha', () => {
                if (typeof grecaptcha !== 'undefined') {
                    grecaptcha.reset();
                }
            });
        });
    </script>
</div>
