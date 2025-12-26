<?php

/**
 * Headless WordPress Theme
 * 
 * This WordPress installation serves as a headless CMS.
 * All frontend rendering is handled by Next.js.
 * 
 * If you're seeing this page, you've accessed the WordPress URL directly.
 * Please visit the Next.js frontend instead.
 */

// Set 404 header
header('HTTP/1.1 404 Not Found');
header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Headless WordPress</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .container {
            background: white;
            border-radius: 20px;
            padding: 60px 40px;
            max-width: 600px;
            text-align: center;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        }

        h1 {
            font-size: 2.5rem;
            color: #333;
            margin-bottom: 20px;
        }

        .emoji {
            font-size: 4rem;
            margin-bottom: 20px;
        }

        p {
            font-size: 1.1rem;
            color: #666;
            line-height: 1.6;
            margin-bottom: 15px;
        }

        .info {
            background: #f8f9fa;
            border-left: 4px solid #667eea;
            padding: 20px;
            margin: 30px 0;
            text-align: left;
        }

        .info strong {
            color: #667eea;
        }

        code {
            background: #f1f3f5;
            padding: 2px 8px;
            border-radius: 4px;
            font-family: 'Monaco', 'Courier New', monospace;
            color: #e83e8c;
        }

        a {
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="emoji">🚀</div>
        <h1>Headless WordPress</h1>
        <p>This is a <strong>headless WordPress installation</strong>.</p>
        <p>The WordPress frontend is disabled. All content is served via GraphQL and REST APIs.</p>

        <div class="info">
            <p><strong>For Developers:</strong></p>
            <p>• WordPress Admin: <code><?php echo admin_url(); ?></code></p>
            <p>• GraphQL Endpoint: <code><?php echo home_url('/graphql'); ?></code></p>
            <p>• REST API: <code><?php echo rest_url(); ?></code></p>
        </div>

        <p>Frontend is powered by <strong>Next.js</strong></p>
        <p><small>WordPress is used only for content management</small></p>
    </div>
</body>

</html>
<?php
exit;
