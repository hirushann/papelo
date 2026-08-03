# Graph Report - .  (2026-08-02)

## Corpus Check
- Corpus is ~19,905 words - fits in a single context window. You may not need a graph.

## Summary
- 462 nodes · 532 edges · 86 communities (73 shown, 13 thin omitted)
- Extraction: 93% EXTRACTED · 7% INFERRED · 0% AMBIGUOUS · INFERRED: 38 edges (avg confidence: 0.8)
- Token cost: 100 input · 100 output

## Community Hubs (Navigation)
- User.php
- QuestionManager.php
- composer.json
- QuestionManager
- scripts
- autoprefixer
- Alpine.js
- question-manager.blade.php
- Admin/QuestionManager (Livewir...
- ⚡question-manager.blade.php
- AppServiceProvider.php
- VerifyEmailController.php
- IsAdmin.php
- AppLayout.php
- LoginForm.php
- UserFactory.php
- goToQuestion({{ $idx }})
- Logout.php
- PHPUnit\Framework\TestCase
- profile.delete-user-form
- verify-email.blade.php
- graphify
- admin.question-manager
- layout.navigation
- paper-catalog
- quiz-taker
- resetFilters
- dashboard.blade.php
- layout/navigation.blade.php
- result.blade.php

## God Nodes (most connected - your core abstractions)
1. `User` - 28 edges
2. `QuestionManager` - 25 edges
3. `TestCase` - 18 edges
4. `Paper` - 14 edges
5. `Sri Lankan Exam Practice Platform` - 11 edges
6. `Exam Platform Database Schema` - 11 edges
7. `Question` - 9 edges
8. `require-dev` - 9 edges
9. `scripts` - 9 edges
10. `PaperCatalog` - 8 edges

## Surprising Connections (you probably didn't know these)
- `Laravel Boost` --semantically_similar_to--> `Sri Lankan Exam Practice Platform`  [INFERRED] [semantically similar]
  README.md → AGENTS.md
- `Allow-all Crawler Directive` --conceptually_related_to--> `Sri Lankan Exam Practice Platform`  [INFERRED]
  public/robots.txt → AGENTS.md
- `Laravel` --conceptually_related_to--> `Laravel Framework`  [INFERRED]
  AGENTS.md → README.md
- `graphify workflow` --conceptually_related_to--> `graphify`  [INFERRED]
  .agents/workflows/graphify.md → .agents/rules/graphify.md
- `VerifyEmailController` --inherits--> `Controller`  [EXTRACTED]
  app/Http/Controllers/Auth/VerifyEmailController.php → app/Http/Controllers/Controller.php

## Import Cycles
- None detected.

## Hyperedges (group relationships)
- **Exam Platform Database Schema** — agents_md_database_schema, agents_md_subjects_table, agents_md_papers_table, agents_md_questions_table, agents_md_options_table, agents_md_purchases_table, agents_md_attempts_table, agents_md_attempt_answers_table [EXTRACTED 1.00]
- **Livewire Components Depending on Exam Schema** — agents_md_quiztaker, agents_md_resultsummary, agents_md_papercatalog, agents_md_admin_questionmanager, agents_md_database_schema [EXTRACTED 1.00]
- **Locked Tech Stack** — agents_md_laravel, agents_md_blade, agents_md_livewire, agents_md_flux_ui_pro, agents_md_alpinejs, agents_md_mysql, agents_md_payhere [EXTRACTED 1.00]

## Communities (86 total, 13 thin omitted)

### Community 0 - "User.php"
Cohesion: 0.07
Nodes (18): User, DatabaseSeeder, Illuminate\Database\Console\Seeds\WithoutModelEvents, Illuminate\Database\Eloquent\Factories\HasFactory, Illuminate\Database\Seeder, Illuminate\Foundation\Auth\User, Illuminate\Foundation\Testing\RefreshDatabase, Illuminate\Foundation\Testing\TestCase (+10 more)

### Community 1 - "QuestionManager.php"
Cohesion: 0.07
Nodes (12): PaperCatalog, StudentDashboard, Attempt, AttemptAnswer, Option, Paper, Purchase, Illuminate\Database\Eloquent\Model (+4 more)

### Community 2 - "composer.json"
Cohesion: 0.04
Nodes (46): pestphp/pest-plugin, php-http/discovery, autoload, autoload-dev, psr-4, psr-4, config, allow-plugins (+38 more)

### Community 3 - "QuestionManager"
Cohesion: 0.11
Nodes (3): QuestionManager, Question, Subject

### Community 4 - "scripts"
Cohesion: 0.08
Nodes (26): scripts, dev, post-autoload-dump, post-create-project-cmd, post-root-package-install, post-update-cmd, pre-package-uninstall, setup (+18 more)

### Community 5 - "autoprefixer"
Cohesion: 0.08
Nodes (23): autoprefixer, concurrently, laravel-vite-plugin, devDependencies, autoprefixer, concurrently, laravel-vite-plugin, postcss (+15 more)

### Community 6 - "Alpine.js"
Cohesion: 0.09
Nodes (23): Alpine.js, Blade Templates, Branding Guidelines, Department of Examinations Sri Lanka, Flux UI Pro, Never Commit Secrets Guardrail (auth.json, .env), Laravel, Livewire (+15 more)

### Community 7 - "question-manager.blade.php"
Cohesion: 0.13
Nodes (14): cancelEdit, createPaper, createSubject, deleteQuestion({{ $question->id }}), editQuestion({{ $question->id }}), goToStep(1), goToStep(2), goToStep(3) (+6 more)

### Community 8 - "Admin/QuestionManager (Livewir..."
Cohesion: 0.27
Nodes (13): Admin/QuestionManager (Livewire component), attempt_answers table, attempts table, Exam Platform Database Schema, options table, PaperCatalog (Livewire component), papers table, purchases table (+5 more)

### Community 9 - "⚡question-manager.blade.php"
Cohesion: 0.15
Nodes (12): cancelEdit, createPaper, createSubject, deleteQuestion({{ $question->id }}), editQuestion({{ $question->id }}), goToStep(1), goToStep(2), goToStep(3) (+4 more)

### Community 10 - "AppServiceProvider.php"
Cohesion: 0.28
Nodes (3): AppServiceProvider, VoltServiceProvider, Illuminate\Support\ServiceProvider

### Community 11 - "VerifyEmailController.php"
Cohesion: 0.36
Nodes (4): VerifyEmailController, Controller, Illuminate\Foundation\Auth\EmailVerificationRequest, Illuminate\Http\RedirectResponse

### Community 12 - "IsAdmin.php"
Cohesion: 0.36
Nodes (4): IsAdmin, Closure, Illuminate\Http\Request, Symfony\Component\HttpFoundation\Response

### Community 13 - "AppLayout.php"
Cohesion: 0.43
Nodes (4): AppLayout, GuestLayout, Illuminate\View\Component, Illuminate\View\View

### Community 15 - "UserFactory.php"
Cohesion: 0.47
Nodes (3): UserFactory, Illuminate\Database\Eloquent\Factories\Factory, static

### Community 16 - "goToQuestion({{ $idx }})"
Cohesion: 0.40
Nodes (4): goToQuestion({{ $idx }}), nextQuestion, previousQuestion, submitQuiz

### Community 19 - "profile.delete-user-form"
Cohesion: 0.50
Nodes (3): profile.delete-user-form, profile.update-password-form, profile.update-profile-information-form

## Knowledge Gaps
- **125 isolated node(s):** `$schema`, `name`, `type`, `description`, `laravel` (+120 more)
  These have ≤1 connection - possible missing edges or undocumented components.
- **13 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `User` connect `User.php` to `QuestionManager.php`?**
  _High betweenness centrality (0.025) - this node is a cross-community bridge._
- **Why does `QuestionManager` connect `QuestionManager` to `QuestionManager.php`?**
  _High betweenness centrality (0.016) - this node is a cross-community bridge._
- **Why does `Paper` connect `QuestionManager.php` to `QuestionManager`?**
  _High betweenness centrality (0.014) - this node is a cross-community bridge._
- **Are the 21 inferred relationships involving `User` (e.g. with `.run()` and `.test_navigation_menu_can_be_rendered()`) actually correct?**
  _`User` has 21 INFERRED edges - model-reasoned connections that need verification._
- **What connects `$schema`, `name`, `type` to the rest of the system?**
  _125 weakly-connected nodes found - possible documentation gaps or missing edges._
- **Should `User.php` be split into smaller, more focused modules?**
  _Cohesion score 0.06704260651629072 - nodes in this community are weakly interconnected._
- **Should `QuestionManager.php` be split into smaller, more focused modules?**
  _Cohesion score 0.06748911465892599 - nodes in this community are weakly interconnected._