# AGENTS.md — Sri Lankan Exam Practice Platform

This file is read automatically by Antigravity at the start of every session.
Keep it as the single source of truth — if something here conflicts with an
older prompt, this file wins.

## Project Summary

An online platform for Sri Lankan students (Grade 5 Scholarship, O/L, A/L
levels) to take past exam papers as MCQ quizzes and get instant, auto-marked
results with a topic-by-topic weak-area breakdown. Monetized per-paper /
per-subject purchase. Differentiator vs. free PDF competitors: instant
scoring + topic analytics, not just access to the papers.

## Tech Stack (locked — do not substitute without asking)

- **Framework:** Laravel (latest stable)
- **Frontend:** Blade + Livewire (no separate SPA, no Next.js/Vue/React)
- **UI Components:** Flux UI Pro — use Flux components (`<flux:button>`,
  `<flux:radio.group>`, `<flux:progress>`, `<flux:table>`, `<flux:card>`,
  `<flux:select>`, `<flux:badge>`, `<flux:modal>`, `<flux:callout>`) instead
  of hand-written Tailwind markup wherever a suitable Flux component exists
- **Client-side interactivity:** Alpine.js (ships with Flux/Livewire) — used
  only for things that must NOT round-trip to the server, e.g. the quiz
  countdown timer
- **Database:** MySQL
- **Payments:** PayHere (Sri Lankan gateway) — always check current PayHere
  docs for hash/signature format rather than assuming

## Branding

- **Primary:** Indigo `#4F46E5` — buttons, links, headers, brand accents
- **Success / correct answers:** Emerald `#10B981`
- **Warning / countdown timer:** Amber `#F59E0B`
- **Error / incorrect answers:** Rose `#F43F5E`
- **Neutral:** Slate gray scale — backgrounds, borders, secondary text
- **Font:** Inter for all UI text
- **Tone:** calm, trustworthy, exam-focused — not playful/gamified. Parents
  are often the ones paying, so the visual tone should read as credible and
  serious, similar to a tuition institute's materials rather than a
  consumer app.

## Database Schema (source of truth — see full plan doc for field details)

```
subjects        (id, name, level[scholarship|ol|al], medium[english|sinhala|tamil], slug)
papers          (id, subject_id FK, year, title, price, duration_minutes, is_published)
questions       (id, paper_id FK, question_text, image_path, topic_tag, order_index)
options         (id, question_id FK, option_text, is_correct, order_index)
purchases       (id, user_id FK, paper_id FK, amount_paid, payhere_order_id, status[pending|completed|failed])
attempts        (id, user_id FK, paper_id FK, started_at, completed_at, score, total_questions)
attempt_answers (id, attempt_id FK, question_id FK, selected_option_id FK, is_correct)
```

Do not rename these tables/fields without updating this file — every
component (QuizTaker, ResultSummary, PaperCatalog, Admin/QuestionManager)
depends on these exact names.

## Conventions

- Livewire component names: PascalCase, feature-first (`QuizTaker`, not
  `QuizComponent` or `Quiz`)
- Admin-only components live under `App\Livewire\Admin\`
- All scoring logic runs server-side, inside the Livewire component's PHP,
  never trust a score value posted from the client
- Money fields are `decimal(8,2)`, currency is always LKR
- Use Laravel's built-in validation (`$this->validate()` in Livewire) rather
  than manual checks

## Guardrails

- Never commit `auth.json` (Flux Pro license file) — must be in `.gitignore`
- Never commit `.env`
- Payment webhook handling must verify PayHere's signature before trusting
  any status update — do not mark a Purchase as `completed` without
  signature verification
- Past exam paper content sourcing/copyright is an open item with the
  Department of Examinations Sri Lanka — flag if any prompt seems to assume
  unrestricted redistribution rights

## Current Status

- [x] Schema planned
- [x] Prompt 1 (migrations + models) — in progress
- [x] Prompt 2 (Admin/QuestionManager)
- [x] Prompt 3 (PaperCatalog)
- [x] Prompt 4 (QuizTaker)
- [x] Prompt 5 (ResultSummary)
- [ ] Prompt 6 (PayHere integration)
- [x] Prompt 7 (Landing page)

Update this checklist as you complete each prompt so a fresh agent session
knows where the project stands without you re-explaining.
