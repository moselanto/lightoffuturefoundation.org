# Monthly AI visibility checklist

Run on the first working day of each month. Takes about 45 minutes. This measures what is observable; it cannot see how AI systems rank or choose sources internally.

## 1. Quick health check (10 min)
- [ ] `https://lightoffuturefoundation.org/robots.txt` still lists the AI crawler groups (OAI-SearchBot, GPTBot, ChatGPT-User, PerplexityBot, ClaudeBot, Google-Extended, bingbot and others).
- [ ] `https://lightoffuturefoundation.org/llms.txt` loads and the facts are current (phone, programmes, stories).
- [ ] Home page source contains `lof-schema`; /faq/ contains `lof-schema-faq`.
- [ ] Any change to name, phone, email or address is identical on the site, Google Business Profile, Facebook and directories.

## 2. Reports (15 min)
- [ ] **Google Search Console**: Performance, last 28 days: impressions, clicks, top queries, top pages. Pages report: any new "not indexed" errors.
- [ ] **Bing Webmaster Tools**: Search Performance, and **AI Performance** (total citations, cited pages, grounding queries; Copilot and Bing AI answers; preview launched Feb 2026).
- [ ] **GA4** (G-NSPE2134S2): key events `donate_start`, `generate_lead`, `contact_click`; referral traffic from chatgpt.com, perplexity.ai, copilot.microsoft.com, gemini.google.com (only visitors who accepted cookies are counted).

## 3. Prompt test (20 min)
Ask each prompt in **ChatGPT (search on)**, **Google (AI Overview / AI Mode)**, **Gemini**, **Perplexity** and **Microsoft Copilot**. Use a signed-out or fresh session. Record results in the table.

1. What is Light of Future Foundation?
2. Is Light of Future Foundation in Nairobi a registered NGO?
3. Which organisations in Kenya support widows?
4. Which charities in Nairobi help vulnerable children stay in school?
5. Kenyan charities that build houses for widows.
6. How can I donate to a community organisation in Nairobi by M-PESA?
7. NGOs in Nairobi where I can volunteer.

| Month | Prompt # | Platform | Foundation mentioned? | Cited (which URL)? | Anything wrong? | Others named |
|---|---|---|---|---|---|---|
| | | | | | | |

## 4. Fix list
- Wrong facts in an answer: find which source the AI cited and correct it there (our page, a directory, Facebook).
- Not mentioned for a topic we work on: check the matching row in `KEYWORD-AND-TOPICAL-MAP.md` and the gaps in `AEO-PAGE-QA-PLAN.md`.

## Official references
- OpenAI crawlers (OAI-SearchBot for ChatGPT search, GPTBot for training, ChatGPT-User for user fetches): developers.openai.com/api/docs/bots
- Bing AI Performance report: blogs.bing.com/webmaster/2026/2/Introducing-AI-Performance-in-Bing-Webmaster-Tools-Public-Preview/
