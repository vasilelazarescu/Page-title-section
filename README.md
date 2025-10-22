# Custom Banner Widget for Elementor

A powerful, feature-rich Elementor widget that creates stunning full-width banner sections with background images, breadcrumbs, titles, and subtitles. Perfect for creating hero sections, page headers, and featured content areas.

## Features

### Background Image Management
- **Dual Image Source Options:**
  - Page Featured Image (automatic)
  - Custom Upload
- Fallback image support
- Complete background controls (size, position, repeat, attachment)
- Parallax effect support (fixed background)
- Responsive background positioning

### Background Overlay
- Customizable overlay color with opacity
- 15+ blend mode options (multiply, screen, overlay, etc.)
- Perfect for ensuring text readability

### Breadcrumb Navigation
- Auto-generate from WordPress hierarchy
- Custom breadcrumb text option
- Customizable separator characters
- Full typography controls
- Hover state styling
- Responsive spacing

### Title Component
- Dynamic tags support (page title, post title, custom fields)
- Auto-pull from page/post titles
- HTML tag selection (H1-H6, div, span, p)
- Full typography suite
- Text shadow and stroke effects
- Hover state colors
- Responsive spacing

### Subtitle Component
- Plain text or HTML input
- Dynamic tags support
- Full typography controls
- Text shadow effects
- Responsive styling

### Container & Layout
- Responsive min-height control
- Flexible content alignment (vertical & horizontal)
- Content max-width control
- Optional content box with background
- Full padding and margin controls
- Border and border-radius options

### Responsive Design
- Dedicated controls for desktop, tablet, and mobile
- Hide/show elements per device
- Responsive typography
- Responsive spacing

### Accessibility Features
- Proper ARIA labels
- Semantic HTML structure
- Keyboard navigation support
- Screen reader-friendly
- WCAG AA compliant color contrast

## Installation

### Method 1: WordPress Plugin Installation

1. Download the plugin files
2. Upload the entire folder to `/wp-content/plugins/`
3. Activate the plugin through the 'Plugins' menu in WordPress
4. The widget will automatically appear in Elementor's widget panel

### Method 2: Manual Integration

If you're integrating this into a theme or custom plugin:

1. Copy the `widgets` folder to your theme/plugin directory
2. Include the widget file in your theme's `functions.php` or plugin file:

```php
// Register the widget with Elementor
function register_custom_banner_widget() {
    require_once get_template_directory() . '/widgets/custom-banner-widget/custom-banner-widget.php';
    \Elementor\Plugin::instance()->widgets_manager->register(new \Custom_Banner_Widget());
}
add_action('elementor/widgets/register', 'register_custom_banner_widget');

// Enqueue styles
function enqueue_custom_banner_styles() {
    wp_enqueue_style(
        'custom-banner-widget',
        get_template_directory_uri() . '/widgets/custom-banner-widget/style.css',
        [],
        '1.0.0'
    );
}
add_action('wp_enqueue_scripts', 'enqueue_custom_banner_styles');
```

## Usage

### Adding the Widget

1. Edit any page with Elementor
2. Search for "Custom Banner Widget" in the widgets panel
3. Drag and drop the widget onto your page
4. Configure the settings in the left panel

### Configuring Background Image

#### Using Page Featured Image:
1. Set the "Image Source" to "Page Featured Image"
2. Go to your page settings and set a featured image
3. Configure background size, position, and other settings
4. Optionally set a fallback image

#### Using Custom Image:
1. Set the "Image Source" to "Custom Upload"
2. Click "Choose Image" and select from media library
3. Configure background settings as needed

### Setting Up Breadcrumbs

1. Toggle "Show Breadcrumb" to ON
2. Choose "Auto Generate" for WordPress hierarchy or "Custom Text" for manual input
3. Customize separator character
4. Style using the "Breadcrumb Style" section in the Style tab

### Configuring Title

1. Enter your title text or leave empty to auto-pull from page
2. Enable "Auto Pull Page Title" to use the current page/post title
3. Select appropriate HTML tag (H1 recommended for main page titles)
4. Style in the "Title Style" section

### Configuring Subtitle

1. Toggle "Show Subtitle" to ON
2. Enter your subtitle text
3. Select HTML tag
4. Style in the "Subtitle Style" section

### Layout Customization

1. Go to the "Container" section in the Style tab
2. Set min-height (recommend 400px or 50vh)
3. Adjust vertical and horizontal alignment
4. Set content max-width if needed
5. Add padding/margin as required

### Adding Content Box Background

For better text readability over complex backgrounds:

1. Scroll to "Content Box" in the Container style section
2. Set a background color (e.g., semi-transparent black: rgba(0,0,0,0.7))
3. Add padding to the content box
4. Optionally add border and border-radius

## Best Practices

### Image Optimization
- Use images with minimum 1920px width for full-width banners
- Optimize images before upload (recommended: 200-400KB)
- Consider using WebP format for better performance
- Set appropriate background size (usually "cover")

### Typography
- Maintain heading hierarchy (use H1 for page titles)
- Ensure text is readable with sufficient contrast
- Use text shadows for better readability over images
- Keep mobile font sizes readable (minimum 24px for titles)

### Accessibility
- Always provide meaningful title text
- Use proper heading tags
- Ensure color contrast meets WCAG AA standards (4.5:1 for body text)
- Test keyboard navigation

### Performance
- Avoid parallax (fixed background) on mobile devices
- Use appropriate image sizes
- Limit number of banner widgets per page
- Enable lazy loading when possible

### Responsive Design
- Test all breakpoints (desktop, tablet, mobile)
- Adjust min-height for mobile (recommend 300px minimum)
- Ensure text remains readable at all sizes
- Consider hiding breadcrumbs on mobile if needed

## Styling Examples

### Classic Hero Section
```
Background: Featured Image with cover size
Overlay: rgba(0,0,0,0.4)
Title: H1, 48px, white, centered
Subtitle: 18px, white, centered
Min Height: 60vh
Alignment: Center/Center
```

### Page Header with Breadcrumbs
```
Background: Featured Image with fixed attachment
Overlay: rgba(0,0,0,0.5)
Breadcrumb: Shown, auto-generate
Title: H1, 36px, white, left-aligned
Min Height: 400px
Alignment: Center/Left
```

### Content Box Style
```
Background: Featured Image
Overlay: None
Content Box Background: rgba(255,255,255,0.95)
Content Box Padding: 40px
Title: Dark color
Subtitle: Dark color
Border Radius: 10px
```

## Customization

### Adding Custom CSS

You can add custom CSS through:

1. **Elementor's Custom CSS:**
   - Go to Advanced tab → Custom CSS

2. **Theme CSS:**
   ```css
   /* Custom styles for banner widget */
   .custom-banner-widget .banner-title {
       text-transform: uppercase;
       letter-spacing: 2px;
   }
   ```

### Modifying PHP

To customize widget behavior, edit the widget file:
- `/widgets/custom-banner-widget/custom-banner-widget.php`

Key methods to modify:
- `register_controls()` - Add/modify widget controls
- `render()` - Change HTML output
- `get_breadcrumb_html()` - Customize breadcrumb generation

## Troubleshooting

### Featured Image Not Showing

**Problem:** Featured image doesn't display when selected

**Solutions:**
1. Verify the page/post has a featured image set
2. Check if featured image support is enabled in your theme
3. Try using custom upload to test if issue is with featured image
4. Check browser console for image loading errors

### Text Not Visible

**Problem:** Text is hard to read or invisible

**Solutions:**
1. Add or increase overlay opacity
2. Add text shadow to title and subtitle
3. Use content box background
4. Adjust text colors for better contrast
5. Test with different background images

### Widget Not Appearing

**Problem:** Widget doesn't show in Elementor panel

**Solutions:**
1. Ensure Elementor is installed and activated
2. Check if plugin is activated
3. Verify minimum Elementor version (3.0.0+)
4. Clear cache (browser and WordPress)
5. Check for JavaScript errors in console

### Parallax Not Working on Mobile

**Note:** This is intentional for performance reasons. The CSS automatically disables fixed backgrounds on mobile devices (tablets and phones).

### Breadcrumbs Not Generating

**Problem:** Auto-generated breadcrumbs are empty or incorrect

**Solutions:**
1. Check if page has proper parent hierarchy
2. Try custom breadcrumb text as alternative
3. Verify WordPress permalink structure is set
4. Check if viewing on front page (breadcrumbs may be minimal)

## Browser Support

- Chrome (latest 2 versions)
- Firefox (latest 2 versions)
- Safari (latest 2 versions)
- Edge (latest 2 versions)
- Opera (latest 2 versions)

## Requirements

- WordPress 5.0 or higher
- Elementor 3.0.0 or higher
- PHP 7.0 or higher

## File Structure

```
custom-banner-widget/
├── custom-banner-widget-plugin.php    # Main plugin file
├── widgets/
│   └── custom-banner-widget/
│       ├── custom-banner-widget.php   # Widget class
│       ├── style.css                  # Frontend styles
│       └── editor.css                 # Editor styles
└── README.md                          # Documentation
```

## Changelog

### Version 1.0.0
- Initial release
- Featured image and custom upload support
- Auto-generated breadcrumbs
- Dynamic title and subtitle
- Full styling controls
- Responsive design
- Accessibility features

## Support

For support, feature requests, or bug reports:
- GitHub Issues: [Your repository URL]
- Email: [Your email]
- Documentation: [Your documentation URL]

## Credits

- Developed by [Your Name]
- Built with Elementor
- Icons by Font Awesome (via Elementor)

## License

This plugin is licensed under the GPL v2 or later.

---

**Made with ❤️ for the Elementor community**
