# What it does

This WordPress plugin adds a corrections workflow system to posts. It allows editors to track correction status, send correction messages to recipients, and manage the correction process through both the WordPress editor and REST API.

The plugin creates two database tables to store correction status and messages, and extends the WordPress REST API by adding correction-related fields to post endpoints.

# How to test

## Database Tables
1. Check that `wp_zen_corrections_status` and `wp_zen_corrections_messages` tables exist in your database
2. Open a post in the WordPress editor
3. Change the corrections status in the top right panel
4. Verify the changes are saved in the `wp_zen_corrections_status` table

## REST API Extension
1. Visit https://zen-stage.palasthotel.de/wp-json/wp/v2/posts/2692180 (while logged in)
2. Verify these fields are present in the response:
   - `corrections_status` - current correction status
   - `corrections_messages` - array of correction messages
   - `corrections_revisions` - recent revisions by different authors
   - `corrections_message_content` - structured message content