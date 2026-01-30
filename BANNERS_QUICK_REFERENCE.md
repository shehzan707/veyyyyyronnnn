# Dynamic Banners System - Quick Start Guide

## 🚀 Quick Setup Checklist

- [ ] Run database migration: `php artisan migrate`
- [ ] Clear cache: `php artisan cache:clear`
- [ ] Test homepage at `/`
- [ ] Test category pages: `/men`, `/women`, `/accessories`, `/footwear`
- [ ] Upload test banners in Admin Panel

---

## 👥 For Website Visitors

### Navigate to Category Pages
```
Home Page           →  /
Men's Section       →  /men
Women's Section     →  /women
Accessories Section →  /accessories
Footwear Section    →  /footwear
```

### How to Use Navigation
1. **Hover** on "Men", "Women", "Accessories", or "Footwear" → See mega-menu
2. **Click** on any category name → Go to that category's home page
3. **Click** any mega-menu item → Go directly to product listing

### Banner Features
- Auto-slides every 5 seconds (images)
- Videos play automatically, then move to next slide
- Use arrow buttons (< >) to manually navigate
- Click dots (●) at bottom to jump to specific slide
- Click banner to go to the product page

---

## 👨‍💼 For Admins

### Access Banner Management
1. Go to Admin Dashboard
2. Click "Banners" in the navigation
3. You'll see:
   - **Left side**: All uploaded banners with filters
   - **Right side**: Upload form for new banners

### Upload a New Banner

#### Step 1: Select Section
- **Default Home**: Main homepage banners
- **Men**: Banners for `/men` page
- **Women**: Banners for `/women` page
- **Accessories**: Banners for `/accessories` page
- **Footwear**: Banners for `/footwear` page

#### Step 2: (Optional) Add Product Link
- Enter the full URL where banner should link to
- Example: `https://yoursite.com/products?category=men-shirts`
- Leave blank if you don't want the banner clickable

#### Step 3: (Optional) Set Display Order
- Default: 0 (first)
- Lower numbers appear first
- Higher numbers appear later
- Example: 0, 1, 2, 3...

#### Step 4: Upload Media
- Click the upload area or drag & drop
- Supported formats:
  - **Images**: JPG, PNG, GIF
  - **Videos**: MP4 (recommended)
- Max file size: 100MB

### Edit Banner Details
1. Find the banner in the list
2. Click the ✏️ (edit) icon
3. Update section, link, or order
4. Click "Update Banner"

### Enable/Disable Banner
1. Find the banner in the list
2. Toggle the switch on/off
3. **ON** = Banner will display on site
4. **OFF** = Banner won't show (but isn't deleted)

### Delete Banner
1. Find the banner in the list
2. Click the 🗑️ (trash) icon
3. Confirm deletion
4. Banner and file are permanently removed

### Filter Banners by Section
Click the filter buttons at the top:
- **All Sections**: Show all banners
- **Default**: Only banners for main homepage
- **Men**: Only banners for /men page
- **Women**: Only banners for /women page
- **Accessories**: Only banners for /accessories page
- **Footwear**: Only banners for /footwear page

---

## 📸 Best Practices for Banners

### Image Banners
- **Recommended size**: 1920x1080 or larger
- **Aspect ratio**: 16:9 works best
- **Optimization**: Compress to reduce load time
- **Format**: PNG for graphics, JPG for photos

### Video Banners
- **Format**: MP4 (H.264 codec)
- **Duration**: 5-10 seconds is ideal
- **Recommended size**: 1920x1080
- **File size**: Keep under 20MB for fast loading
- **Audio**: Muted automatically (optional in video)
- **Note**: Always test on mobile devices

### General Tips
1. **Always add product links** - Users expect banners to be clickable
2. **Use consistent branding** - Match your site's color scheme
3. **Test on mobile** - Verify banners look good on phones
4. **Set proper order** - Most important banners first (0, 1, 2...)
5. **Update regularly** - Keep content fresh and relevant
6. **Use mix of media** - Combine images and videos for interest
7. **Optimize for speed** - Large files will slow down pages

---

## 🔗 Banner Link Examples

```
Category page:
https://yoursite.com/products?category=men-shirts

Product page:
https://yoursite.com/products/123

Sale page:
https://yoursite.com/products?sort=new

Specific gender + category:
https://yoursite.com/products?gender=women&category=tops
```

---

## ⚙️ Technical Info

### Database Fields
- **section**: Which page shows this banner
- **banner_link**: URL to open when banner is clicked
- **is_enabled**: Toggle to show/hide banner
- **display_order**: Sort order (lower = first)
- **media_type**: Image or Video
- **created_at**: When banner was uploaded

### Routes
```
GET /                    → Default home (shows 'default' banners)
GET /men                 → Men's home (shows 'men' banners)
GET /women               → Women's home (shows 'women' banners)
GET /accessories         → Accessories home (shows 'accessories' banners)
GET /footwear            → Footwear home (shows 'footwear' banners)

POST /admin/banners      → Upload new banner
GET /admin/banners       → View all banners
GET /admin/banners/:id/edit    → Edit banner
PUT /admin/banners/:id         → Save banner changes
POST /admin/banners/:id/toggle → Enable/disable banner
DELETE /admin/banners/:id      → Delete banner
```

---

## 🐛 Troubleshooting

### Banners not showing on site
**Solution:**
1. Check if banner is enabled (toggle should be ON)
2. Verify correct section is selected
3. Clear browser cache (Ctrl+F5)
4. Run `php artisan cache:clear`

### Video not playing
**Solution:**
1. Ensure video is MP4 format
2. Check video file size (max 100MB)
3. Test in Chrome or Firefox
4. Verify video has no audio or is muted

### Banner carousel not auto-sliding
**Solution:**
1. Check browser console for JavaScript errors (F12)
2. Reload page
3. Try different browser
4. Clear browser cache

### Link not working
**Solution:**
1. Verify full URL is entered correctly
2. Check URL actually exists
3. Test link in new tab to verify

### Slow to load
**Solution:**
1. Compress images (use online tools)
2. Reduce video file size
3. Use PNG for graphics, JPG for photos
4. Keep files under 5MB if possible

---

## 💡 Pro Tips

1. **Banner Scheduling**: Create banners in advance, then enable them later
2. **A/B Testing**: Upload multiple versions of banners, disable losers
3. **Seasonal Updates**: Change banners by season to keep site fresh
4. **Mobile Testing**: Always check how banners look on phones
5. **User Engagement**: Add product links to all banners
6. **Performance**: Monitor page load times when adding videos
7. **Analytics**: Track which banners get most clicks

---

## 📊 Carousel Behavior

### For Images
- Displays for 5 seconds
- Fades to next image smoothly
- Progress bar fills as countdown happens
- Repeats indefinitely

### For Videos
- Auto-plays (muted)
- Plays full duration
- When finished, auto-advances to next slide
- Useful for product demos or storytelling

### Mixed Media
- Images and videos can be in same carousel
- Each gets appropriate treatment
- Creates dynamic, engaging experience

---

## 🎯 Common Tasks

### Change banner section
1. Edit banner
2. Select new section
3. Update

### Reorder banners
1. Edit banner
2. Change display_order number
3. Update

### Make banner not clickable
1. Edit banner
2. Clear the "Banner Link" field
3. Update

### Temporarily hide banner
1. Toggle enable/disable switch OFF
2. Click again to re-enable later

### Test banner links
1. Visit the page where banner appears
2. Click the banner
3. Verify link works correctly

---

**Last Updated**: January 27, 2026
**Version**: 1.0
