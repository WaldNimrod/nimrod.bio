### **מפרט הנדסי: ארכיטקטורת Trade Replay ואינטגרציית דאשבורד (v1.2.0)**

#### **1\. חזון טכנולוגי ופילוסופיית מוצר**

מערכת ה-Trade Replay מהווה את המימוש הארכיטקטוני של המעבר מיומן מסחר רטרוספקטיבי (Retrospective Journal) לאקו-סיסטם של  **Active Quality Assurance (QA)** . המערכת מוגדרת כ-"Behavioral Mirror" (מראה התנהגותית) – מסגרת שקיפות לא-ענשתית (Non-punitive transparency framework) שנועדה להתערב בלולאת קבלת ההחלטות של הסוחר במטרה למגר הטיות פסיכולוגיות של "תקווה ואגו" השוחקות את המשמעת העצמית.

##### **מנגנון ה-Plan Snapshot**

בלב הארכיטקטורה נמצא ה- **Plan Snapshot**  – רשומה בלתי ניתנת לשינוי (Immutable) המקפיאה את כוונת הסוחר (נקודות כניסה, סטופ-לוס ויעדי רווח) ברגע הביצוע הראשון. מנגנון זה משמש כעוגן אסטרטגי המונע את "מלכודת התקווה" (Widening stops) על ידי הצבת מראה אובייקטיבית מול הסטייה מהתכנון המקורי.

##### **קהל יעד ומתודולוגיית Force Review**

המערכת מותאמת אישית לפרופיל ה- **Swing Trader**  (המבצע 3–15 עסקאות בשבוע). עבור פלח זה, ה"נטל" של קישור ידני בין ביצועים (Force Review) הוא פיצ'ר המייצר רגע של בחינה כפויה החיונית לשיפור מתודולוגי. לעידוד משמעת זו, הממשק מציג באנר non-blocking במקרה של תוכניות חסרות:". שלך התוכנית השלמת עכשיו השלם — הניתוח את תשפר "

#### **2\. סטנדרטים של דיוק פיננסי ויושרה אלגוריתמית**

הדיוק הפיננסי הוא ה"חפיר" הטכנולוגי (Moat) של המערכת. חל איסור מוחלט על שימוש בנתוני Floating Point (IEEE 754\) בשכבת החישובים למניעת טעויות עיגול מצטברות.

##### **דרישות טיפוסי נתונים**

כל חישובי שכבת הביצועים וה-Replay יבוצעו תוך שימוש בטיפוסי הנתונים הבאים ב-PostgreSQL:| יישום | טיפוס נתון (PostgreSQL) | דרישת דיוק || \------ | \------ | \------ || מחירים וכמויות | NUMERIC(20, 8\) | דיוק מקסימלי למניעת סטיות מול הברוקר || P\&L וזרימת מזומנים | NUMERIC(20, 6\) | חישובי תזרים ותוצאות עסקאות || סיכומי פורטפוליו (Snapshots) | NUMERIC(18, 4\) | תאימות לטבלת ה-Portfolio Snapshots |  
**הנחיה ארכיטקטונית:**  יש לאכוף איסור שימוש ב-FLOAT באמצעות Check Constraints או Domain Types ברמת ה-Schema כדי למנוע זיהום נתונים.

##### **מנגנון ה-Projection והתאמה (Reconciliation)**

מנוע ה-Replay מחשב דלתאות ברמת גרנולריות של  **(20, 8\)** . תוצאות אלו מושלכות (Projected) אל תוך סכמת ה-Snapshots המשתמשת ב- **(18, 4\)**  לצורך עקביות עם ה-Portfolio Rollups, תוך שמירה על Audit Trail מלא של החישוב המקורי.

#### **3\. ארכיטקטורת נתונים וסכמת DB**

ניהול הנתונים מתבצע ב-PostgreSQL 16 תחת עקרון ה-Single Source of Truth ( **ADR034** ).

##### **טבלת user\_data.daily\_portfolio\_snapshots**

המערכת מנהלת את מצב הפורטפוליו היומי באמצעות הישויות הבאות:

* id: UUID (PK).  
* user\_id: UUID (FK ל-users עם CASCADE).  
* account\_id: UUID (FK ל-trading\_accounts, ניתן ל-NULL עבור rollup ברמת משתמש).  
* total\_value: NUMERIC(18, 4\) (שווי ב-Base Currency לאחר FX Rollup).  
* missing\_tickers: JSONB (תיעוד טיקרים ללא נתוני מחיר לצרכי Data Quality Audit).  
* source: VARCHAR(16) (ערכים: auto, manual, backfill).

  ##### **לוגיקת כתיבה וייחודיות**

  תהליך ה-EOD (המבוצע ב-23:55 UTC) פועל בשיטת  **INSERT OR UPDATE via conditional unique index** . הטיפול בערכי NULL ב-account\_id מבוצע באמצעות האילוץ הבא: UNIQUE (user\_id, snapshot\_date, COALESCE(account\_id, '00000000-0000-0000-0000-000000000000'::uuid))

  ##### **מיפוי ישויות ב-Graph**

* **Trade (החלטה אסטרטגית):**  רכיב הליבה הכולל את ה-Plan Snapshot.  
* **Executions (פעולות טקטיות):**  קשר של One-to-Many. המערכת ממפה ויזואלית את כל הכניסות והיציאות על גבי ציר הזמן ההיסטורי ביחס לתוכנית המקורית.

  #### **4\. אינטגרציית Dashboard Cubes ו-FastAPI**

  המערכת מטמיעה שש קוביות (Cubes) פונקציונליות:  **Home, Planning, Tracking, Research, Data, Management** .

  ##### **אינטגרציית Micha OS ב-Replay**

  ממשק ה-Replay כולל שכבות מידע מתודולוגיות (Overlays):

1. **ATR Traffic Light:**  רמות סטופ-לוס דינמיות המבוססות על תנודתיות בזמן אמת.  
2. **The Death Kiss (150 Law):**  אכיפה ויזואלית של מגבלות סיכון קשיחות לבחינת חריגות מה-In-bounds risk.

   ##### **דרישות Backend ו-FastAPI**

   Endpoints תחת /api/v1/trade\_replay/ המממשים דפוס אסינכרוני ושימוש ב-FOR UPDATE SKIP LOCKED למניעת Deadlocks:

* GET /session/{trade\_id}: שליפת temporal data packet (מבנה PortfolioSeriesResponse).  
* POST /analysis: הפעלת ה- **Skeptical Coach (D25)**  לזיהוי הטיות (Bias Detection) בשיטת BYOK.

  ##### **ה-BurningActionItem**

  בתוך ה-Planning Cube, עסקאות עם  **Maturity Score**  נמוך (מתחת ל-0.5) מסומנות כ-BurningActionItem. הגדרה זו מציפה חוסר משמעת תכנוני ודורשת התערבות מיידית דרך ה-PlanWizard.

  #### **5\. פרוטוקולי אבטחה, הצפנה ומשילות (Governance)**

  ##### **הצפנה ובידוד (Multi-tenancy)**

* **EncryptionService:**  שימוש ב-Fernet (AES-128-CBC \+ HMAC) להצפנת מפתחות API, מזהי Telegram ופרטי IBKR.  
* **Organization Model:**  אכיפת הפרדת נתונים קשיחה (Data Isolation) בין ארגונים שונים כחלק מהמוכנות ל-B2B.

  ##### **משילות נתונים ו-Audit Log**

  בהתאם ל-ADR034, חל איסור על כתיבה ישירה משכבת האפליקציה. כל מוטציה מתועדת ב- **Audit Log / Compliance Trail**  הכולל:

* Timestamp בדיוק גבוה.  
* **Actor Tracking:**  זיהוי הגורם המבצע לכל פעולה.  
* **Nostro Readiness:**  עמידה בתקני ביקורת של מוסדות פיננסיים.

  #### **6\. רצף מיגרציה וניהול פיתוח (AOS)**

  ##### **רצף מיגרציות (0019–0032)**

  התשתית הוקמה ברצף המיגרציות הבא:

* **0019:**  תמיכה ב-Trade-Without-Plan ואינפרנס (D29-NEXT).  
* **0022/0024:**  הקמת תשתית ה-Snapshots היומית ואילוצי ייחודיות.  
* **0032:**  מנגנון Account Deletion עם Cascade Deletion ל-Executions, Tags ו-Sync Logs.

  ##### **מתודולוגיית AOS Sprint**

  צוות 100 פועל במודל סוכני AI מקביליים להשגת קצב פיתוח גבוה:

* **Orchestrator (Opus):**  מחזיק את הקשר הספרינט המלא.  
* **Sub-agents (Sonnet):**  בנייה סימולטנית של דפי Production ו-Validators (מצב  **LOD500\_LOCKED** ).  
* **Inline Validators (Haiku):**  בדיקות תקינות ואימות קוד בזמן אמת.

  #### **7\. הוראות לצוותי הפיתוח (Team 100, 99, 30\)**

  לפני מעבר לשלב ה-Pilot, יש להשלים את רשימת המשימות הבאה:

  ##### **צוות 100 (Core & AWS Infrastructure)**

*  הרצה חובה של validate\_aos.sh וקבלת תוצאה "0 FAIL".  
*  הטמעת ה-Backfill CLI (scripts.backfill\_portfolio) לשחזור היסטוריית משתמשי הפיילוט.  
*  וידוא זמינות שירותים: UI (Port 8180), API (Port 8182).  
*  הגדרת ALLOWED\_ORIGINS קשיח ב-CORS והגנה באמצעות  **Tailscale-gated access** .  
*  מימוש ה-Mutation Log עבור מוכנות "Nostro Readiness".

  ##### **צוות 99 (AI & Governance)**

*  הטמעת D25 עם תמיכה ב-Similarity Cache ו-Delta Mode לחיסכון בעלויות.  
*  וידוא אכיפת תקציבי AI (Cost Governance) ברמת משתמש.  
*  אימות תמיכה ב-5 ספקי ה-LLM (Anthropic/OpenAI SDKs).

  ##### **צוות 30 (UX & Frontend)**

*  הטמעת ה-Tracking Cluster תחת  **Phoenix v4 (data-dash)** .  
*  פיתוח ה-Plan Maturity Score ב-Planning Cube תוך שימוש ב- **TanStack Query v5** .  
*  וידוא רספונסיביות מלאה ל-Mobile Trade Capture (שיפור חוויית "swipe down \+ 2 taps").  
  