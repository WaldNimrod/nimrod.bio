### **מפרט הנדסי: מנוע Trade Replay וארכיטקטורת Active QA**

#### **1\. חזון אסטרטגי: מהפכת ה-Active Quality Assurance**

מערכת TikTrack מגדירה מחדש את הקטגוריה על ידי מעבר מ"יומן מסחר רטרוספקטיבי" (Retrospective Journal) למערכת  **Active Quality Assurance (QA)**  פרואקטיבית. ארכיטקטורת ה-Trade Replay מיועדת לשמש כ"מראה התנהגותית" (Behavioral Mirror) – מסגרת שקיפות שאינה ענישתית (Non-punitive transparency framework), המתערבת בלולאת קבלת ההחלטות של הסוחר בזמן אמת כדי למגר הטיות פסיכולוגיות השוחקות את המשמעת העצמית.

* **Coach over Compliance:**  הפילוסופיה המובילה היא "מאמן ולא משגיח". המערכת אינה חוסמת פעולות (Gatekeeper), אלא מהווה כלי קליני לתיקון עצמי.  
* **Active QA Intervention:**  מימוש ה-QA מתבצע דרך ממשק המשתמש באמצעות באנר עברי לא-חוסם (Non-blocking banner) עבור תוכניות חסרות: "השלמת התוכנית שלך תשפר את הניתוח — השלם עכשיו".  
* **קהל יעד (Swing Trader):**  המנוע מותאם לפרופיל סוחר המבצע 3–15 טריידים בשבוע. עבור פלח זה, הצורך בקישור ידני של הוצאות לפועל לתוכנית המסחר מוגדר כ"מס משמעת" (Discipline Tax) – אילוץ מכוון המכריח רגע של סקירה מתודולוגית מקצועית.

  #### **2\. מנוע ה-Trade Replay: ארכיטקטורה ויכולות ליבה**

  המנוע מבצע סנכרון רציף בין נתוני שוק היסטוריים לבין כוונת הסוחר המקורית כפי שהוקפאה ב-Plan Snapshot.

* **Visual Execution Mapping:**  מיפוי קשר של "אחד לרבים" (One-to-Many) בין טרייד אסטרטגי יחיד לבין מספר רב של הוצאות לפועל טקטיות (Executions) על גבי גרף מחיר.  
* **Playback Mode:**  סינכרון תנועת מחיר היסטורית מול ה-Plan Snapshot המוקפא. ה-UI Trigger של ה-Replay מוטמע בתוך אבסטרקט ה-BurningActionItem ב-Planning Dashboard Cube ומוצג דרך ה-TradeDrillDownModal.  
* **מדדי מתודולוגיית Micha OS:**| מדד | הגדרה טכנית ב-Replay || \------ | \------ || **ATR Traffic Light** | רמות סטופ-לוס דינמיות מבוססות תנודתיות (Volatility-based) לאורך מחזור חיי הטרייד. || **The Death Kiss (חוק ה-150)** | אכיפה מחמירה של פרמטרי סיכון (Strict risk boundary enforcement) המציגה ויזואלית אם הטרייד חרג מגבולות הסיכון המותרים ("In-bounds"). |

  #### **3\. סטנדרט דיוק פיננסי (Financial Precision Standards)**

  קיימת חובה ל"יושרה אלגוריתמית" (Algorithmic Integrity). חל איסור מוחלט על שימוש ב-Floating Point (IEEE 754\) בשכבת החישובים למניעת שגיאות עיגול מצטברות.

* **טיפוסי נתונים (PostgreSQL):**  
* **מחירים וכמויות:**  NUMERIC(20, 8).  
* **רווח והפסד (P\&L):**  NUMERIC(20, 6).  
* **לוגיקת Reconciliation:**  המערכת מחשבת דלתות (Delta-granularity) בדיוק של (20, 8\) ומקרינה את התוצאות לתוך סכמת ה-Snapshots המשתמשת ב-NUMERIC(18, 4). תהליך זה מבטיח עקביות מלאה ב-Portfolio Rollups מול נתוני הברוקר.

  #### **4\. מנגנון ה-Plan Snapshot והמשילות בנתונים**

* **אי-שתנות (Immutability):**  ה-Plan Snapshot הוא רשומה בלתי ניתנת לשינוי המייצגת את כוונת הסוחר ברגע המעבר למצב ACTIVE.  
* **משילות (ADR034):**  בסיס הנתונים הוא Single Source of Truth (SSOT). חל איסור על שכבת האפליקציה לבצע כתיבה ישירה; כל המוטציות מבוצעות דרך שירותי הליבה.  
* **לוגיקת Inference (D29-NEXT):**  עבור טריידים ללא תוכנית, המערכת מפעילה מנגנון הסקה:  
* שימוש ב-user\_trade\_defaults ונתוני ביצוע ראשוניים.  
* תיעוד מקור המידע (Provenance) ב-JSONB תחת plan\_field\_sources עם ערכי Enum: "user", "inferred", "first\_execution".  
* **אילוץ:**  שדה is\_plan\_complete לעולם לא יוגדר כ-TRUE על ידי מנוע ההסקה; רק עדכון ידני של המשתמש רשאי לשנות סטטוס זה.

  #### **5\. ניהול מצב תיק (Daily Portfolio Snapshots)**

* **תהליך ה-EOD (End of Day):**  ה-Worker המרכזי compute\_portfolio\_snapshots רץ מדי יום בשעה 23:55 (UTC).  
* **מדידות (PortfolioMetricsService):**  השירות גוזר מדדי Sharpe Ratio, CAGR ו-Max Drawdown בזמן ה-Playback.  
* **FX Rollups:**  טיפול בחשבונות רב-מטבעיים מתבצע על ידי המרה מ-total\_value\_account\_ccy למטבע הבסיס (base\_currency) תוך שימוש ב-market\_data.exchange\_rates.  
* **מיגרציות:**  המבנה מבוסס על רצף המיגרציות 0022, 0024 ו-0026.

  #### **6\. פרוטוקולי אבטחה, הצפנה ופרטיות**

* **EncryptionService:**  שימוש בפרוטוקול Fernet (AES-128-CBC \+ HMAC) להצפנת מידע רגיש.  
* **שדות מחויבי הצפנה:**  מזהי Telegram chat IDs ופרטי גישה לברוקר (IBKR credentials) נשמרים כ-Ciphertext בלבד.  
* **Multi-tenancy:**  הפרדת נתונים קשיחה דרך מודל "Organization".  
* **Audit Log:**  כל מוטציה בנתונים ותחילת סשן Replay מתועדים ב-Compliance Trail הכולל Actor Tracking ו-Timestamp מדויק. דרישה זו היא אילוץ מחייב למוכנות מוסדית (Institutional Readiness) ודרישות Nostro.

  #### **7\. תכנון API וביצועי מערכת**

  נקודות הקצה תחת /api/v1/trade\_replay/:

* **GET /session/{trade\_id}:**  שליפת חבילת הנתונים הטמפורלית. מבנה התגובה  **חייב**  להתיישב עם מודל ה-PortfolioSeriesResponse.  
* **POST /analysis:**  הפעלת D25 "Skeptical Coach". שימוש ב-ai\_budget\_service.py לניהול Similarity Cache.  
* **ביצועים:**  שימוש חובה בתבנית FOR UPDATE SKIP LOCKED למניעת Deadlocks בעבודה מקבילית של ה-Workers.  
* **AI Cost Governance:**  הצגת "Cost Preview" (לדוגמה: "$0.03 to run this") כדרישת סף לפני הרצת ניתוח.

  #### **8\. תלויות ואינטגרציה טכנולוגית**

1. **Core Stack:**  FastAPI, PostgreSQL 16, APScheduler.  
2. **D25 (LLM Providers):**  תמיכה ב-5 ספקים במודל BYOK.  
3. **Backfill CLI:**  שימוש ב-scripts.backfill\_portfolio לשחזור היסטורי של צילומי מצב.  
4. **Data Quality Signals:**  במידה ושדה missing\_tickers ב-Snapshot אינו ריק, יוצג Warning Banner ב-UI המציין חוסר בנתוני מחיר היסטוריים לביצוע Replay תקין.  
   \-- דוגמת סכמה עבור Daily Portfolio Snapshots (D32)  
   CREATE TABLE user\_data.daily\_portfolio\_snapshots (  
       id UUID PRIMARY KEY DEFAULT gen\_random\_uuid(),  
       user\_id UUID NOT NULL REFERENCES user\_data.users(id) ON DELETE CASCADE,  
       account\_id UUID REFERENCES user\_data.trading\_accounts(id) ON DELETE CASCADE,  
       snapshot\_date DATE NOT NULL,  
       total\_value NUMERIC(18, 4\) NOT NULL,  
       realized\_pl NUMERIC(18, 4),  
       missing\_tickers JSONB,  
       computed\_at TIMESTAMPTZ NOT NULL,  
       UNIQUE (user\_id, snapshot\_date, COALESCE(account\_id, '00000000-0000-0000-0000-000000000000'::uuid))  
   );  
     
   