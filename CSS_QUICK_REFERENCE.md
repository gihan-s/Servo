# CSS Quick Reference Guide

## File Locations

All CSS files are now located in: `public/assets/css/`

## CSS File Mapping

| PHP File | External CSS Files | Purpose |
|----------|-------------------|---------|
| `app/views/admin/providerView.php` | `common-components.css`<br>`providerView.min.css` | Provider details view |
| `app/views/client/Messages/index.php` | `messages.min.css` | Chat/messaging interface |
| `app/views/client/Notification/index.php` | `notification.min.css` | Notifications panel |
| `app/views/client/Payments/payments.php` | `common-components.css`<br>`payments.min.css` | Payment management |
| `app/views/client/Projects/serviceProjects.php` | `common-components.css`<br>`serviceProjects.min.css` | Service project listing |
| `app/views/provider/Earnings/index.php` | `earnings.min.css` | Provider earnings dashboard |
| `app/views/provider/Projects/index.php` | `providerProjects.min.css` | Provider projects |
| `app/views/includes/navbar.php` | `navbarExtra.css` | Navigation extras |
| `app/views/register/footer.php` | `registerFooter.min.css` | Registration footer |
| `app/views/register/services.php` | `common-components.css`<br>`registerServices.min.css` | Service registration |

## Common Components CSS

The file `common-components.css` contains shared styles used across multiple pages:

### Components Included:
- **Card Styles**: `.search-item`, `.search-item:hover`
- **Status Badges**: `.status-badge`, `.status-active`
- **Post Components**: `.post-header`, `.post-title`, `.post-description`, `.post-footer`
- **Skills**: `.post-skills`, `.skills-label`, `.skills-tags`, `.skill-tag`
- **Details**: `.post-details`, `.detail-item`, `.detail-label`, `.detail-value`
- **Stats**: `.engagement-stats`, `.stat-item`
- **Buttons**: `.action-btn`, `.btn-delete`

### Usage:
Include this file when you need any of the above components:
```html
<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/common-components.css">
```

## Development Workflow

### Making CSS Changes

1. **Locate the CSS file** for the page you want to modify (see mapping table above)
2. **Edit the non-minified version** (e.g., `messages.css` instead of `messages.min.css`)
3. **Test your changes** in development environment
4. **Re-minify** the CSS file:

```powershell
# PowerShell minification command
$css = Get-Content "path/to/file.css" -Raw
$css = $css -replace '/\*.*?\*/', ''
$css = $css -replace '\s+', ' '
$css = $css -replace '\s*([{:;,}])\s*', '$1'
$css = $css -replace ';}', '}'
Set-Content "path/to/file.min.css" -Value $css.Trim()
```

5. **Verify** the minified file works correctly
6. **Commit both files** (original and minified)

### Adding New Styles

1. Determine if the style is:
   - **Specific to one page**: Add to that page's CSS file
   - **Reusable across pages**: Add to `common-components.css`

2. Update the appropriate CSS file
3. Minify if needed
4. Test thoroughly

### Page-Specific Notes

#### Messages Page (`messages.min.css`)
- Chat layout and conversation styles
- Message bubbles and composer
- Sidebar and notifications

#### Notifications Page (`notification.min.css`)
- Notification cards and list
- Filter tabs and summary panel
- Period selectors

#### Payments Page (`payments.min.css`)
- Payment card layouts
- Status indicators (pending, paid, refunded)
- Invoice displays

#### Service Projects (`serviceProjects.min.css`)
- Project cards and modals
- Status chips
- Progress bars
- Request management UI

#### Provider Projects (`providerProjects.min.css`)
- Provider-specific project views
- Bid management styles
- Service request layouts

#### Earnings Page (`earnings.min.css`)
- Dashboard metrics
- Chart containers
- Earning cards and statistics

## Tips for Optimization

1. **Use common-components.css** when possible to avoid duplication
2. **Minify for production** to reduce file sizes
3. **Test across browsers** after CSS changes
4. **Use CSS variables** for consistent theming (consider implementing)
5. **Avoid inline styles** in PHP/HTML - use classes instead

## Browser Caching

External CSS files benefit from browser caching. Consider adding cache headers:

```apache
# .htaccess example
<FilesMatch "\.(css)$">
    Header set Cache-Control "max-age=31536000, public"
</FilesMatch>
```

## Performance Monitoring

Monitor CSS performance:
- File sizes (keep minified versions updated)
- Load times (use browser dev tools)
- Unused CSS (consider tools like PurgeCSS)

## Troubleshooting

### CSS Not Loading
1. Check file path in `<link>` tag
2. Verify `BASE_URL` constant is defined
3. Check file permissions on server
4. Clear browser cache

### Styles Not Applying
1. Check CSS selector specificity
2. Verify class names match HTML
3. Check for typos in class names
4. Use browser dev tools to inspect elements

### After Updates
1. Clear browser cache (Ctrl+F5)
2. Check console for 404 errors
3. Verify minified file was updated
4. Test in incognito/private mode

---

**Last Updated**: 2025-11-15
**Maintained By**: Development Team
