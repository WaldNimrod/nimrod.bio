# nimrod.bio - WordPress Development
# ==================================

## 🎯 **פרויקט**
אתר nimrod.bio - אתר אישי/מקצועי מבוסס WordPress

## 🚀 **סביבת פיתוח**
- **WordPress**: 6.4
- **PHP**: 8.2
- **MySQL**: 8.0
- **Docker**: Development Environment

## 📁 **מבנה הפרויקט**
```
nimrod-bio/
├── wp-content/
│   ├── themes/          # ערכות נושא מותאמות
│   ├── plugins/         # תוספים מותאמים
│   └── uploads/         # קבצים שהועלו
├── wp-config-environment.php  # הגדרות סביבה
├── wordpress-manager.sh       # ניהול Docker
└── deploy-to-production.sh    # העברה לייצור
```

## 🔧 **הפעלה מקומית**
```bash
# הפעלת סביבת פיתוח
./wordpress-manager.sh start

# גישה לאתר
http://localhost:8081
```

## 🚀 **העברה לייצור**
```bash
# יצירת חבילת העברה
./deploy-to-production.sh

# ייצוא מסד נתונים
cd deployment-package && ./export-database.sh
```

## 🔗 **אינטגרציה עם Upress**
האתר מוגדר לעבודה עם שרת Upress.co.il:
- **Git Integration** - עדכון אוטומטי
- **Environment Detection** - הגדרות דינמיות
- **No Code Changes** - העברה חלקה

## 📋 **Workflow פיתוח**
1. **פיתוח מקומי** - localhost:8081
2. **Commit & Push** - Git repository
3. **Pull ב-Upress** - עדכון אוטומטי
4. **בדיקה בייצור** - אתר חי

## 🛠️ **פקודות שימושיות**
```bash
# ניהול סביבה
./wordpress-manager.sh start|stop|restart|status

# גיבוי
./wordpress-manager.sh backup my-backup

# לוגים
./wordpress-manager.sh logs

# מסד נתונים
./wordpress-manager.sh db
```

## 🔒 **אבטחה**
- **Environment Detection** - הגדרות אוטומטיות
- **Debug Mode** - רק בפיתוח
- **File Permissions** - מוגדרות נכון
- **Security Headers** - בייצור

## 📞 **תמיכה**
- **Development**: nimrod@nimrod.bio
- **Server**: Upress.co.il support
- **Documentation**: README files