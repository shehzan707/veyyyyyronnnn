# Admin Theme - Visual Reference Guide

## Color Scheme

```
┌─────────────────────────────────────────────────────────┐
│           ADMIN PANEL COLOR PALETTE                     │
├─────────────────────────────────────────────────────────┤
│                                                         │
│  🎨 PRIMARY BACKGROUND                                 │
│  Gradient: #0f2027 → #2c5364 → #0f2027               │
│  Usage: Page backgrounds, full-page fill              │
│                                                         │
│  📝 TEXT COLORS                                         │
│  Primary:   #e2e8f0  (Headings, main text)           │
│  Secondary: #cbd5e1  (Labels, descriptions)          │
│                                                         │
│  🟢 SUCCESS/ACTION BUTTONS                             │
│  Gradient: #34d399 → #10b981                         │
│  Usage: Add, Edit, Update, View buttons              │
│                                                         │
│  🔴 DANGER/DELETE BUTTONS                              │
│  Gradient: #ef4444 → #dc2626                         │
│  Usage: Delete buttons ONLY                           │
│                                                         │
│  🎭 CARD BACKGROUNDS                                   │
│  Background: rgba(255, 255, 255, 0.08)              │
│  Backdrop:   blur(10px)                              │
│  Border:     rgba(255, 255, 255, 0.15)              │
│                                                         │
└─────────────────────────────────────────────────────────┘
```

## Button Styles

### Green Buttons (Action Buttons)
```
┌──────────────────────────┐
│  ✏️  Edit    ➕ Add      │   ← Light Green
│  ✓ Update  👁️ View      │   Gradient
└──────────────────────────┘
```

**CSS:**
```css
background: linear-gradient(135deg, #34d399 0%, #10b981 100%);
color: #fff;
border: none;
border-radius: 8px;
padding: 10px 20px;
font-weight: 600;
cursor: pointer;
transition: all 0.3s ease;
```

**Hover Effect:**
```
┌──────────────────────────┐
│  ✏️  Edit    ➕ Add      │   ← Darker Green
│  ✓ Update  👁️ View      │   ↑ Move up 2px
│  (Subtle shadow)        │   ✨ Glow effect
└──────────────────────────┘
```

### Red Buttons (Delete Only)
```
┌──────────────────────────┐
│  🗑️  Delete              │   ← Red
│  ✕ Remove               │   Gradient
└──────────────────────────┘
```

**CSS:**
```css
background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
color: #fff;
border: none;
border-radius: 8px;
padding: 10px 20px;
font-weight: 600;
cursor: pointer;
transition: all 0.3s ease;
```

## Page Layouts

### Cards with Glassmorphism
```
┌────────────────────────────────────────┐
│  ╭──────────────────────────────────╮  │ Dark Gradient Background
│  │                                  │  │
│  │    Glassmorphic Card             │  │ Semi-transparent
│  │  • Backdrop blur(10px)           │  │ card with blur
│  │  • rgba(255,255,255,0.08)        │  │ effect
│  │  • Subtle border                 │  │
│  │                                  │  │
│  ╰──────────────────────────────────╯  │
│                                        │
└────────────────────────────────────────┘
```

### Tables
```
┌─────────────────────────────────────┐
│ Column 1  │ Column 2  │ Column 3   │ ← Green tinted header
├─────────────────────────────────────┤
│ Data      │ Data      │ Data       │
│ Data      │ Data      │ Data       │
│ Data      │ Data      │ Data       │ Light grey text
├─────────────────────────────────────┤
│ (Hover effect - subtle green tint)  │
└─────────────────────────────────────┘
```

### Status Badges
```
  🟡 Pending        🔵 Processing     🟣 Shipped
  🟢 Delivered      🔴 Cancelled
  
  Semi-transparent with matching color
  Padding: 5px 12px | Border-radius: 20px
```

## Form Elements

### Input Fields
```
┌──────────────────────────────┐
│ Input Label                  │
│ ┌────────────────────────────┐ ← Dark background
│ │ [Input field with placeholder] │ Light border
│ └────────────────────────────┘
│
│ Focus State:
│ ┌────────────────────────────┐
│ │ [Input field ✓] │ ← Green border, glow
│ └────────────────────────────┘
```

### Form Card
```
┌──────────────────────────────┐
│  ➕ Add New [Item]           │
├──────────────────────────────┤
│                              │
│  Label                       │
│  [Input Field]               │
│                              │
│  Label                       │
│  [Select Dropdown]           │
│                              │
│  [Submit Button - Green]     │
│                              │
└──────────────────────────────┘
```

## Navigation Header

```
╔════════════════════════════════════════════════════╗
║  🏢 LOGO  Dashboard  Products  Orders  Users ... ║  ← Dark Gradient
║          (Active link highlighted in blue)       ║  Underline animation
╚════════════════════════════════════════════════════╝
```

## Sidebar/Navigation Links

```
Active State:
┌────────────────────────┐
│ 📊 Dashboard           │ ← Cyan/Blue color
│    (Blue underline)    │ Highlighted background
└────────────────────────┘

Inactive State:
┌────────────────────────┐
│ 📦 Products            │ ← Grey color
│                        │ Subtle background
└────────────────────────┘
```

## Stat Cards (Dashboard)

```
┌──────────────────┐
│                  │
│    💳  2,543     │ ← Icon color (blue/green/orange/purple)
│                  │
│   Total Orders   │ ← Light grey label
│                  │
└──────────────────┘
```

## Typography

```
Heading 1 (h1, h2):     #fff (White)          - 1.5rem - 700 weight
Heading 3 (h3, h4):     #fff (White)          - 1.2rem - 600 weight
Body Text:              #e2e8f0 (Light grey)  - 1rem   - 400 weight
Labels:                 #cbd5e1 (Med grey)    - 0.9rem - 600 weight
Descriptions:           #cbd5e1 (Med grey)    - 0.9rem - 400 weight
Error Messages:         #fca5a5 (Light red)   - 0.85rem - 500 weight
Success Messages:       #86efac (Light green) - 0.85rem - 500 weight
```

## Responsive Breakpoints

```
Desktop:  ≥1200px - Full layouts, 4-column grids
Tablet:   768-1199px - 2-column grids, adjusted spacing
Mobile:   <768px - Single column, touch-friendly buttons
```

## Animation Effects

### Button Hover
```
Duration:    300ms (cubic-bezier(0.34, 1.56, 0.64, 1))
Transform:   translateY(-2px) - Move up slightly
Shadow:      Enhanced glow effect
Color:       Slightly darker gradient
```

### Link Hover
```
Duration:    300ms
Color:       Lighter green
Text Effect: Underline animation
```

### Card Hover
```
Duration:    300ms
Transform:   translateY(-3px)
Background:  Slightly more opaque
Shadow:      Enhanced shadow
```

## Best Practices

✅ **DO:**
- Use light green buttons for all non-destructive actions
- Use red buttons ONLY for delete operations
- Maintain consistent spacing and padding
- Use glassmorphic cards for visual hierarchy
- Apply smooth transitions to all interactive elements
- Keep text contrast high for accessibility
- Use proper semantic HTML for forms

❌ **DON'T:**
- Use red for any button other than delete
- Mix light green and red buttons on same form
- Use solid backgrounds instead of glassmorphic effects
- Add extra shadows or effects not defined here
- Change text colors without permission
- Disable transitions/animations
- Use light backgrounds in dark theme

## Quick Reference

| Element | Color | Usage |
|---------|-------|-------|
| Background | `#0f2027-#2c5364` | Full page |
| Card | `rgba(255,255,255,0.08)` | Cards, forms, tables |
| Text Primary | `#e2e8f0` | Headings, body text |
| Text Secondary | `#cbd5e1` | Labels, descriptions |
| Button (Action) | `#34d399-#10b981` | Add, edit, update, view |
| Button (Delete) | `#ef4444-#dc2626` | Delete only |
| Link | `#34d399` | Navigation, actions |
| Border | `rgba(255,255,255,0.15)` | Card borders |
| Hover Overlay | `rgba(52,211,153,0.1)` | Row hover, item hover |

---

**Theme Version**: 1.0 - Dark Professional Blue  
**Last Updated**: 2024  
**Compatibility**: All Modern Browsers with CSS Grid & Filters
