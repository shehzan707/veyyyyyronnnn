# Admin Theme System - Code Reference

## 📋 CSS Variables Reference

### All Available Variables

```css
/* Light Theme Colors (both :root and body.theme-light) */
--bg-primary: #ffffff;          /* Main page background */
--bg-secondary: #f9f9f9;        /* Header, secondary backgrounds */
--bg-tertiary: #f0f0f0;         /* Hover states, tertiary backgrounds */
--text-primary: #000000;        /* Main text color */
--text-secondary: #333333;      /* Labels, secondary text */
--text-tertiary: #666666;       /* Hints, muted text */
--border-color: #e0e0e0;        /* All borders */
--btn-bg: #333333;              /* Button background */
--btn-text: #000000;            /* Button text */
--btn-hover: #555555;           /* Button hover background */
--card-bg: #ffffff;             /* Card backgrounds */
--input-bg: #ffffff;            /* Input field backgrounds */
--input-border: #cccccc;        /* Input field borders */
--input-text: #000000;          /* Input field text */
--placeholder-color: #999999;   /* Input placeholder text */

/* Dark Theme Colors (body.theme-dark) */
--bg-primary: #2a2a2a;          /* Medium grey background */
--bg-secondary: #333333;        /* Darker grey */
--bg-tertiary: #3d3d3d;         /* Even darker grey */
--text-primary: #ffffff;        /* White text */
--text-secondary: #e0e0e0;      /* Light grey text */
--text-tertiary: #b0b0b0;       /* Medium grey text */
--border-color: #444444;        /* Dark grey borders */
--btn-bg: #ffffff;              /* White buttons */
--btn-text: #000000;            /* Black button text */
--btn-hover: #e0e0e0;           /* Light grey on hover */
--card-bg: #323232;             /* Dark card background */
--input-bg: #1a1a1a;            /* Very dark input background */
--input-border: #444444;        /* Dark grey input border */
--input-text: #ffffff;          /* White input text */
--placeholder-color: #888888;   /* Medium grey placeholder */
```

---

## 🎨 Usage Examples

### Example 1: Styling a Card
```css
.card {
    background: var(--card-bg);
    color: var(--text-primary);
    border: 1px solid var(--border-color);
    transition: all 250ms ease;
}

.card:hover {
    background: var(--bg-secondary);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}
```

### Example 2: Styling a Button
```css
.button {
    background: var(--btn-bg);
    color: var(--btn-text);
    border: none;
    padding: 10px 20px;
    border-radius: 8px;
    transition: all 250ms ease;
}

.button:hover {
    background: var(--btn-hover);
    transform: translateY(-2px);
}
```

### Example 3: Form Input
```css
input, textarea {
    background: var(--input-bg);
    color: var(--input-text);
    border: 1px solid var(--input-border);
    border-radius: 8px;
    padding: 10px;
}

input::placeholder {
    color: var(--placeholder-color);
}

input:focus {
    border-color: var(--btn-bg);
    box-shadow: 0 0 8px rgba(0,0,0,0.1);
}
```

### Example 4: Table
```css
table {
    background: var(--card-bg);
    color: var(--text-primary);
}

table th {
    background: var(--bg-secondary);
    color: var(--text-primary);
    border-color: var(--border-color);
}

table td {
    border-color: var(--border-color);
    color: var(--text-primary);
}

table tbody tr:hover {
    background: var(--bg-tertiary);
}
```

### Example 5: Alert Box
```css
.alert {
    background: rgba(52, 211, 153, 0.1);
    border: 1px solid rgba(52, 211, 153, 0.3);
    color: var(--text-primary);
    padding: 12px;
    border-radius: 8px;
}
```

### Example 6: Navigation Item
```css
.nav-item {
    background: var(--bg-tertiary);
    color: var(--text-secondary);
    border: 1px solid var(--border-color);
    transition: all 250ms ease;
}

.nav-item:hover {
    background: var(--bg-secondary);
    color: var(--text-primary);
    border-color: var(--btn-bg);
}

.nav-item.active {
    background: var(--btn-hover);
    color: var(--text-primary);
    border-color: var(--btn-bg);
}
```

---

## 🎯 Common Styling Patterns

### Pattern 1: Card with Hover
```css
.card {
    background: var(--card-bg);
    border: 1px solid var(--border-color);
    padding: 20px;
    border-radius: 12px;
    transition: all 250ms ease;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    background: var(--bg-secondary);
}
```

### Pattern 2: Button with States
```css
.btn {
    background: var(--btn-bg);
    color: var(--btn-text);
    padding: 10px 20px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    transition: all 250ms ease;
}

.btn:hover {
    background: var(--btn-hover);
    transform: translateY(-2px);
}

.btn:active {
    transform: translateY(0);
}

.btn:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}
```

### Pattern 3: Input Group
```css
.input-group {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.input-group label {
    color: var(--text-secondary);
    font-weight: 600;
    font-size: 14px;
}

.input-group input {
    background: var(--input-bg);
    color: var(--input-text);
    border: 1px solid var(--input-border);
    padding: 10px;
    border-radius: 8px;
    transition: all 250ms ease;
}

.input-group input:focus {
    border-color: var(--btn-bg);
    outline: none;
    box-shadow: 0 0 8px rgba(0,0,0,0.1);
}
```

### Pattern 4: Badge/Label
```css
.badge {
    background: var(--bg-tertiary);
    color: var(--text-secondary);
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
}

.badge.success {
    background: rgba(34, 197, 94, 0.2);
    color: #22c55e;
}

.badge.error {
    background: rgba(239, 68, 68, 0.2);
    color: #ef4444;
}
```

### Pattern 5: Status Indicator
```css
.status {
    display: inline-block;
    width: 12px;
    height: 12px;
    border-radius: 50%;
    margin-right: 8px;
}

.status.active {
    background: #22c55e;
}

.status.inactive {
    background: var(--border-color);
}

.status.pending {
    background: #f59e0b;
}
```

---

## 📱 Responsive Example

```css
/* Mobile */
@media (max-width: 768px) {
    .card {
        padding: 15px;
        margin: 10px;
    }
    
    .button {
        width: 100%;
        padding: 12px 16px;
    }
}
```

---

## 🎭 Theme Toggle Button Styling

```css
.theme-toggle {
    position: fixed;
    bottom: 16px;
    left: 16px;
    width: 44px;
    height: 44px;
    border-radius: 50%;
    background: var(--card-bg);
    border: 1px solid var(--border-color);
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    z-index: 1000;
    opacity: 0.5;
    transition: all 250ms ease;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.theme-toggle:hover {
    opacity: 1;
    transform: scale(1.1);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

.theme-toggle .material-icons {
    font-size: 20px;
    color: var(--text-primary);
}

.theme-toggle .icon-light,
.theme-toggle .icon-dark {
    position: absolute;
    transition: opacity 250ms ease, transform 250ms ease;
}

.theme-toggle .icon-light {
    opacity: 1;
    transform: scale(1);
}

.theme-toggle .icon-dark {
    opacity: 0;
    transform: scale(0.5);
}

.theme-dark .theme-toggle .icon-light {
    opacity: 0;
    transform: scale(0.5);
}

.theme-dark .theme-toggle .icon-dark {
    opacity: 1;
    transform: scale(1);
}
```

---

## 🔧 JavaScript Helper Functions

### Check Current Theme
```javascript
function getCurrentTheme() {
    return document.body.classList.contains('theme-light') 
        ? 'light' 
        : 'dark';
}
```

### Set Specific Theme
```javascript
function setTheme(theme) {
    const body = document.body;
    if (theme === 'light') {
        body.classList.remove('theme-dark');
        body.classList.add('theme-light');
    } else if (theme === 'dark') {
        body.classList.remove('theme-light');
        body.classList.add('theme-dark');
    }
    localStorage.setItem('admin-theme', `theme-${theme}`);
}
```

### Toggle Theme
```javascript
function toggleTheme() {
    const current = getCurrentTheme();
    const newTheme = current === 'light' ? 'dark' : 'light';
    setTheme(newTheme);
}
```

### Get CSS Variable Value
```javascript
function getCSSVariable(varName) {
    return getComputedStyle(document.documentElement)
        .getPropertyValue(`--${varName}`).trim();
}
```

### Example: Get Button Background Color
```javascript
const btnColor = getCSSVariable('btn-bg');
console.log(btnColor); // Outputs: #333333 (light) or #ffffff (dark)
```

---

## 🎨 Color Reference Charts

### Light Theme Hex Values
```
Background:     #ffffff
Header:         #f9f9f9
Hover:          #f0f0f0
Text:           #000000
Secondary:      #333333
Tertiary:       #666666
Border:         #e0e0e0
Button:         #333333
Button Hover:   #555555
Card:           #ffffff
Input:          #ffffff
Input Border:   #cccccc
Placeholder:    #999999
```

### Dark Theme Hex Values
```
Background:     #2a2a2a
Header:         #333333
Hover:          #3d3d3d
Text:           #ffffff
Secondary:      #e0e0e0
Tertiary:       #b0b0b0
Border:         #444444
Button:         #ffffff
Button Hover:   #e0e0e0
Card:           #323232
Input:          #1a1a1a
Input Border:   #444444
Placeholder:    #888888
```

---

## ✨ Advanced Styling

### Gradient with Theme Colors
```css
.gradient-box {
    background: linear-gradient(
        135deg,
        var(--bg-secondary) 0%,
        var(--bg-tertiary) 100%
    );
    color: var(--text-primary);
    padding: 20px;
    border-radius: 12px;
}
```

### Shadow with Theme
```css
.card-shadow {
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    /* Works automatically in both themes */
}
```

### Transparent Color with Theme
```css
.overlay {
    background: rgba(0, 0, 0, 0.5);
    /* Always dark overlay */
}

.overlay-light {
    background: rgba(255, 255, 255, 0.5);
    /* Always light overlay */
}
```

---

## 📊 Real-World Component Examples

### Admin Dashboard Card
```css
.dashboard-card {
    background: var(--card-bg);
    border: 1px solid var(--border-color);
    border-radius: 12px;
    padding: 20px;
    margin: 15px 0;
    transition: all 250ms ease;
}

.dashboard-card h3 {
    color: var(--text-primary);
    margin-bottom: 10px;
    font-size: 18px;
    font-weight: 700;
}

.dashboard-card p {
    color: var(--text-secondary);
    line-height: 1.6;
}

.dashboard-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 6px 20px rgba(0,0,0,0.1);
}
```

### Admin Table
```css
.admin-table {
    width: 100%;
    background: var(--card-bg);
    border-collapse: collapse;
    border: 1px solid var(--border-color);
    border-radius: 8px;
    overflow: hidden;
}

.admin-table thead {
    background: var(--bg-secondary);
    border-bottom: 2px solid var(--border-color);
}

.admin-table th {
    color: var(--text-primary);
    padding: 15px;
    text-align: left;
    font-weight: 700;
    font-size: 14px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.admin-table td {
    color: var(--text-primary);
    padding: 12px 15px;
    border-bottom: 1px solid var(--border-color);
}

.admin-table tbody tr:hover {
    background: var(--bg-tertiary);
}
```

### Modal Dialog
```css
.modal {
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, 0.5);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 100;
}

.modal-content {
    background: var(--card-bg);
    border: 1px solid var(--border-color);
    border-radius: 12px;
    padding: 30px;
    max-width: 500px;
    width: 90%;
    box-shadow: 0 20px 60px rgba(0,0,0,0.3);
}

.modal-title {
    color: var(--text-primary);
    font-size: 22px;
    font-weight: 700;
    margin-bottom: 20px;
}

.modal-body {
    color: var(--text-secondary);
    margin-bottom: 20px;
}
```

---

## 🎯 Quick Copy-Paste Snippets

### Minimal Card
```css
.card {
    background: var(--card-bg);
    color: var(--text-primary);
    border: 1px solid var(--border-color);
    padding: 20px;
    border-radius: 12px;
}
```

### Minimal Button
```css
.btn {
    background: var(--btn-bg);
    color: var(--btn-text);
    border: none;
    padding: 10px 20px;
    border-radius: 8px;
    cursor: pointer;
    transition: 250ms ease;
}

.btn:hover {
    background: var(--btn-hover);
}
```

### Minimal Input
```css
input {
    background: var(--input-bg);
    color: var(--input-text);
    border: 1px solid var(--input-border);
    padding: 10px;
    border-radius: 8px;
}
```

---

*End of Code Reference - Ready to use!*
