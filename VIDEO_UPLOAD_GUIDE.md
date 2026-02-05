# Video Upload Quality Guide for VEYRON Banners

## Problem: Video Quality Loss After Upload

When uploading videos to the banner carousel, the quality may decrease. Here's how to fix it:

---

## Solution Overview

### 1. **Server Configuration** ✅
Your server allows up to **200MB** video uploads (updated from 100MB)
- Max Upload: 40MB (PHP)
- Post Max Size: 40MB (PHP)
- Memory Limit: 512MB

**To upload larger videos**, increase PHP limits in `php.ini`:
```ini
upload_max_filesize = 500M
post_max_size = 500M
memory_limit = 1024M
```

### 2. **Video Format Requirements**

#### **Recommended Settings:**
```
Container: MP4 (H.264 codec)
Resolution: 1920x1080 (Full HD)
Bitrate: 8-10 Mbps (for quality)
Frame Rate: 30 FPS
Audio: AAC 128 kbps (or muted)
Duration: 10-15 seconds optimal
```

#### **File Size Targets:**
- 1920x1080 @ 10 Mbps = ~12.5 MB per minute
- Recommended: 15-30 MB for banner videos
- Maximum: 200 MB (server limit)

---

## How to Prepare Videos BEFORE Upload

### **Using FFmpeg (Best Quality)**

#### **High Quality Banner Video (1920x1080):**
```bash
ffmpeg -i input.mp4 -c:v libx264 -preset slow -crf 22 -s 1920x1080 -r 30 -b:v 10M -c:a aac -b:a 128k output.mp4
```

#### **Balanced Quality (Medium File Size):**
```bash
ffmpeg -i input.mp4 -c:v libx264 -preset medium -crf 24 -s 1920x1080 -r 30 -b:v 6M -c:a aac -b:a 96k output.mp4
```

#### **Mobile Optimized (Smaller File):**
```bash
ffmpeg -i input.mp4 -c:v libx264 -preset fast -crf 26 -s 1280x720 -r 24 -b:v 3M -c:a aac -b:a 64k output_mobile.mp4
```

#### **Create WebM Alternative (Better Compression):**
```bash
ffmpeg -i input.mp4 -c:v libvpx-vp9 -b:v 8M -c:a libopus -b:a 128k output.webm
```

---

## Detailed Parameter Explanation

| Parameter | Value | Meaning |
|-----------|-------|---------|
| `-c:v libx264` | Codec | H.264 video codec (best compatibility) |
| `-preset slow/medium/fast` | Speed | slow=better quality, fast=faster encoding |
| `-crf 22-26` | Quality | 0-51 scale (lower=better, 22=high quality) |
| `-s 1920x1080` | Resolution | Output resolution |
| `-r 30` | Frame Rate | 30 frames per second |
| `-b:v 8M` | Bitrate | 8 Mbps video bitrate |
| `-c:a aac` | Audio Codec | AAC audio (best compatibility) |
| `-b:a 128k` | Audio Bitrate | 128 kbps audio |

---

## Online Tools (No Installation Needed)

1. **CloudConvert** - cloudconvert.com
   - Upload → Select MP4 format → Advanced options
   - Set: 1920x1080, 30fps, 8Mbps bitrate
   - Download optimized video

2. **Handbrake** - handbrake.fr (Desktop App)
   - Free, open-source video converter
   - Presets: Apple 1080p60
   - Tab: Video → Bitrate 8000 Kbps

3. **VidCoder** - vidcoder.net (Windows)
   - Simple interface for H.264 encoding

---

## Browser/Client Side Issues

### If quality still looks bad in browser:

1. **Check browser console** (F12):
   - Look for video codec errors
   - Check if video is loading completely

2. **Clear browser cache**:
   - Press `Ctrl+Shift+Delete`
   - Clear cached images and files
   - Refresh page

3. **Test in different browser**:
   - Chrome, Firefox, Safari may handle video differently

---

## Video Player Optimization (Already Implemented)

Your banner carousel now includes:
- ✅ `preload="metadata"` - Faster loading
- ✅ `autoplay` - Starts playing automatically
- ✅ `loop` - Repeats video
- ✅ `muted` - No audio track (saves bandwidth)
- ✅ `playsinline` - Works on mobile
- ✅ Multiple format support (MP4 + WebM fallback)

---

## Step-by-Step Upload Process

### **Method 1: Use CloudConvert (Easiest)**

1. Go to **cloudconvert.com**
2. Upload your video
3. Select "Output Format" → **MP4**
4. Click "Advanced" and set:
   - Resolution: 1920x1080
   - Bitrate: 8000 kbps
   - Frame Rate: 30
5. Convert and download
6. Upload to VEYRON admin panel

### **Method 2: Use FFmpeg (Best Control)**

1. Install FFmpeg from ffmpeg.org
2. Open Command Prompt/Terminal
3. Run the command above
4. Upload the output file to VEYRON

---

## Checklist Before Upload

- [ ] Video resolution is 1920x1080 or higher
- [ ] Video bitrate is 6-10 Mbps
- [ ] Video is MP4 format (H.264 codec)
- [ ] File size is under 200 MB
- [ ] Video duration is 10-15 seconds
- [ ] Audio is muted or minimal
- [ ] Video plays smoothly locally before upload

---

## Common Issues & Solutions

### Issue: Video is still blurry after upload
**Solution:** 
- Your source video was low quality
- Re-encode using FFmpeg with `crf 22` (higher quality)
- Increase bitrate to 10Mbps

### Issue: Video won't play in browser
**Solution:**
- Check file format (must be MP4)
- Check codec is H.264 (not VP9)
- Try uploading a WebM fallback
- Clear browser cache (Ctrl+Shift+Delete)

### Issue: Upload takes very long
**Solution:**
- Video file is too large
- Reduce bitrate or resolution
- Use CloudConvert to optimize first
- Check server upload limits

### Issue: Video plays but looks pixelated
**Solution:**
- Bitrate too low (increase to 8Mbps minimum)
- CRF value too high (use 22-24 instead)
- Source video quality was low

---

## Server-Side Settings ✅

Already updated in your application:
- Max file size: **200MB** (up from 100MB)
- Supports: MP4, WebM, OGG, MOV, AVI
- Video logging: Enabled for debugging
- Auto-loop: Enabled
- Autoplay: Enabled

---

## Quick Reference

**Best Video Specs for Your Banner:**
```
Format: MP4 (H.264/AVC)
Resolution: 1920 x 1080
Aspect Ratio: 16:9
Bitrate: 8-10 Mbps
Frame Rate: 30 fps
Duration: 10-15 seconds
Audio: Muted (AAC 128kbps if needed)
File Size: 15-30 MB recommended
Maximum: 200 MB
```

**Upload Command (One-liner):**
```bash
ffmpeg -i your_video.mp4 -c:v libx264 -preset slow -crf 22 -s 1920x1080 -r 30 -b:v 8M -c:a aac -b:a 128k banner.mp4
```

---

**Need Help?** Check browser console (F12) for detailed error messages when uploading!
