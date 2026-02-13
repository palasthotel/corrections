# Setup
resource/ and .wp-env.json are part of the development environment.

Start wordpress with `wp-env start`

setup source:
https://flickerleap.com/setting-up-wp-env-for-local-development/

# Corrections Plugin – Technical Overview

## Purpose

The **Corrections** plugin implements a workflow that allows editors to request corrections (or review) for a post directly from the Gutenberg editor.

An editor can:

- Select recipients inside a Gutenberg panel
- Save the post
- The plugin queues and processes correction requests (e.g., sending emails)

---

# High-Level Workflow

## 1️⃣ Gutenberg UI

The plugin adds a hidden panel in the Gutenberg editor (accessible via the top toolbar icon).

Inside the **“Requests”** panel:

- The editor selects recipients (via autocomplete or checkboxes)
- The UI stores pending recipients as items with:

```ts
{
  id: 0,
  recipient: "email@example.com",
  modified_timestamp: 1234567890,
  sent_timestamp: null,
  error_timestamp: null
}
```

These are stored in the post attribute:

```
corrections_messages
```

Adding recipients does **not** immediately write to the database.

---

## 2️⃣ Saving the Post Triggers the Workflow

Recipients are written to the database only when the post is saved.

The hook:

```ts
dispatch.editPost({ corrections_messages: value })
```

only updates local editor state.

When the post is saved:

- Gutenberg sends a REST request to:

```
/wp-json/wp/v2/<postType>/<id>
```

- The request includes:

```json
"corrections_messages": [...]
```

---

