<x-guest-layout>
    <x-slot name="title">{{ $paper->title }} — Papelooo Exam Practice</x-slot>
    <x-slot name="description">Practice the {{ $paper->title }} online on Papelooo. Get instant MCQ marking, topic breakdowns, and detailed progress reports.</x-slot>

    <x-slot name="head">
    <script type="application/ld+json">
    {
      "@@context": "https://schema.org",
      "@@type": "Course",
      "name": "{{ $paper->title }}",
      "description": "Practice the {{ $paper->title }} online. Get instant scoring and topic analysis.",
      "provider": {
        "@@type": "Organization",
        "name": "Papelooo",
        "sameAs": "{{ config('app.url') }}"
      }
    }
    </script>
    </x-slot>

    <div class="py-12 px-6 max-w-4xl mx-auto">
        
        <div class="mb-8">
            <a href="{{ route('papers') }}" class="text-sm font-semibold text-teal hover:underline">&larr; Back to all papers</a>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-ink/10 p-8 md:p-12 mb-8">
            <div class="flex flex-col md:flex-row md:items-start justify-between gap-6 mb-8">
                <div>
                    <h1 class="font-display text-3xl md:text-4xl text-ink mb-4">{{ $paper->title }}</h1>
                    <div class="flex flex-wrap items-center gap-4 text-sm text-ink/70">
                        <div class="flex items-center gap-1.5">
                            <svg class="w-5 h-5 text-ink/40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            {{ $paper->duration_minutes }} mins
                        </div>
                        <div class="flex items-center gap-1.5">
                            <svg class="w-5 h-5 text-ink/40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"></path></svg>
                            {{ $paper->subject->name }}
                        </div>
                        <div class="flex items-center gap-1.5">
                            <svg class="w-5 h-5 text-ink/40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            {{ $paper->questions_count ?? $paper->questions->count() }} Questions
                        </div>
                    </div>
                </div>
                
                <div class="shrink-0">
                    <a href="{{ route('quiz.take', $paper->id) }}" class="inline-flex items-center justify-center rounded-lg bg-teal text-paper font-semibold px-8 py-3.5 hover:bg-teal/90 transition w-full md:w-auto text-center shadow-sm">
                        Start this Exam
                    </a>
                </div>
            </div>

            <hr class="border-ink/5 mb-8">

            <div>
                <h2 class="font-display text-2xl text-ink mb-6">Sneak Peek</h2>
                
                @if($paper->questions->count() > 0)
                    <div class="space-y-8">
                        @foreach($paper->questions as $index => $question)
                            <div class="bg-gray-50 rounded-xl p-6 border border-ink/5">
                                <p class="text-sm font-semibold text-ink/50 mb-2">Question {{ $index + 1 }}</p>
                                <div class="prose prose-sm max-w-none text-ink">
                                    {!! Str::markdown($question->question_text) !!}
                                </div>
                                
                                @if($question->image_path)
                                    <div class="mt-4">
                                        <img src="{{ Storage::url($question->image_path) }}" alt="Question Image" class="max-w-full h-auto rounded-lg shadow-sm border border-ink/10">
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                    
                    <div class="mt-8 text-center">
                        <div class="inline-block bg-gradient-to-b from-transparent to-white w-full h-24 -mt-32 relative z-10"></div>
                        <p class="text-ink/60 mb-4 relative z-20">Log in or subscribe to see the remaining questions and get instant auto-marking.</p>
                        <a href="{{ route('quiz.take', $paper->id) }}" class="relative z-20 inline-flex items-center justify-center rounded-lg border-2 border-teal text-teal font-semibold px-6 py-2.5 hover:bg-teal hover:text-white transition">
                            Take Full Exam
                        </a>
                    </div>
                @else
                    <p class="text-ink/50 italic">Questions are currently being uploaded for this paper.</p>
                @endif
            </div>
        </div>

    </div>
</x-guest-layout>
