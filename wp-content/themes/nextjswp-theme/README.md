# NextJS WordPress Theme

A headless WordPress theme optimized for serving content to a Next.js frontend via GraphQL and REST APIs.

## Overview

This theme is designed to work as a headless CMS, providing content and data to a Next.js application through:
- **GraphQL API** (via WPGraphQL)
- **REST API** (custom endpoints)
- **ACF (Advanced Custom Fields)** for flexible content management

## Features

### 🎯 Headless CMS Optimized
- Cleaned `functions.php` (removed 261 lines of frontend-only code)
- No frontend rendering - pure API/data layer
- CORS configured for Next.js frontend

### 📦 Custom Post Types
- **News** - Blog posts and news articles
- **Products** - Product catalog
- **Reports** - Financial and corporate reports
- **Team Members** - Staff directory

### 🔌 API Endpoints

#### GraphQL
All custom post types and taxonomies are exposed via GraphQL with proper naming conventions.

#### REST API
- `/wp-json/nextjs/v1/header` - Site logo and navigation menu
- `/wp-json/nextjs/v1/footer` - Footer data from ACF options
- `/wp-json/custom/v1/formidable-form/{id}` - Formidable Forms HTML
- `/wp-json/custom/v1/gravity-form/{id}` - Gravity Forms data

### 🎨 ACF Configuration
- JSON sync enabled (`/acf-json` directory)
- Flexible content layouts for pages
- Field groups for all custom post types

### ⚡ Performance Optimizations
- WordPress heartbeat limited to 60s
- Post revisions limited to 3
- Memory limit increased to 256M
- Auto-updates disabled

## Installation

1. **Clone the repository:**
   ```bash
   cd /path/to/wordpress/wp-content/themes/
   git clone https://github.com/chetan3460/nextjswp-theme.git
   ```

2. **Activate the theme:**
   - Go to WordPress Admin → Appearance → Themes
   - Activate "NextJS WordPress Theme"

3. **Configure CORS:**
   - Update the allowed origin in `functions.php` (line 35):
     ```php
     header('Access-Control-Allow-Origin: https://your-nextjs-domain.com');
     ```

4. **Install Required Plugins:**
   - Advanced Custom Fields (ACF)
   - WPGraphQL
   - WPGraphQL for ACF

## ACF Sync Workflow

This theme uses ACF JSON for version control of field groups:

1. **Export from WordPress:**
   - Go to ACF → Tools → Export Field Groups
   - Select field groups
   - Uncheck "Generate PHP"
   - Click "Export File"

2. **Sync to Next.js:**
   - The exported JSON is processed by the Next.js project
   - Run `npm run acf-sync` in the Next.js repo

## File Structure

```
nextjswp-theme/
├── acf-json/              # ACF field group definitions
├── assets/                # CSS, JS, images
├── inc/                   # Core functionality
│   ├── core/             # Theme setup and initialization
│   ├── features/         # Custom features
│   └── ajax/             # AJAX handlers
├── templates/            # Block templates (not used in headless)
├── functions.php         # Main theme functions
└── style.css            # Theme metadata
```

## Development

### Backend (WordPress)
This theme is optimized for headless WordPress and doesn't render any frontend HTML.

### Frontend (Next.js)
The Next.js application consumes data from this WordPress installation:
- Repository: [nextjs-wordpress-headless](https://github.com/chetan3460/nextjs-wordpress-headless)

## Custom Post Types & Taxonomies

### News
- Taxonomy: `news_category`
- GraphQL: `News`, `NewsCategory`

### Products
- Taxonomies: `product_chemistry`, `product_brand`, `product_application`
- GraphQL: `Product`, `ProductChemistry`, `ProductBrand`, `ProductApplication`

### Reports
- Taxonomies: `report_category`, `financial_year`, `quarter`
- GraphQL: `Report`, `ReportCategory`, `FinancialYear`, `Quarter`

### Team Members
- Taxonomy: `team_category`
- GraphQL: `TeamMember`, `TeamCategory`

## Utilities

### Reading Time Calculation
Automatically calculates and caches reading time for posts:
```php
$read_time = calculate_post_read_time($post_id);
```

## Configuration

### CORS
Update allowed origins in `functions.php`:
```php
header('Access-Control-Allow-Origin: http://localhost:3000');
```

### ACF JSON Path
Field groups are saved to `/acf-json` directory for version control.

## Deployment

1. **Push theme to production:**
   ```bash
   git push origin main
   ```

2. **On production server:**
   ```bash
   cd /path/to/wordpress/wp-content/themes/
   git pull origin main
   ```

3. **Update CORS origin** to production Next.js URL

## Changelog

### Initial Release
- Cleaned functions.php (removed 261 lines of frontend code)
- ACF JSON configuration
- Custom post types and taxonomies
- GraphQL and REST API endpoints
- Performance optimizations

## License

MIT License - Feel free to use this theme for your own headless WordPress projects.

## Related Repositories

- **Next.js Frontend:** [nextjs-wordpress-headless](https://github.com/chetan3460/nextjs-wordpress-headless)

## Support

For issues or questions, please contact the development team.
