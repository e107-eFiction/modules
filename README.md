# Modules for efiction CMS
 
## notification module

This module is part of 3.5.8 version, this is just backup. 
You need files from 3.5.8 version to get it works.

The notifications module is part of the core since 3.5.8 (the e107-eFiction/efiction-3.5.x line ships it as `modules/notifications/`; the official 3.5.7x/3.5.8x releases from efiction.org do not include it). This copy is kept for reference only.

## storybanner module

This module adds a banner field for stories and series. Simple one-line text field for the full path to the image.

Accepted banner values (anything else is saved as an empty banner):

- an absolute `http://` or `https://` URL, for example `https://example.org/banners/story.png`;
- a site-relative path that starts with a single `/`, for example `/images/banners/story.png`.

The value may be at most 250 characters (the size of the `banner` columns) and must not contain
spaces, quotes (`"`, `'`, `` ` ``), `<`, `>`, backslashes or control characters. Relative paths without a
leading `/`, protocol-relative addresses (`//example.org/x.png`), `javascript:` and `data:` addresses
are not accepted. Saving the story or series form with an empty or rejected value removes the banner.
All banner values and titles are HTML-escaped when they are written into the page.

To use it in skin:

### series

Templates:
- listings.tpl
- seriesblock.tpl

`{seriesbanner}`  - insert image with HTML img tag

`{seriesbanner_src} `- return only image path
`{seriesbanner_title}` - return the title of the series in format for the alt tag

Example:
`<img src="{seriesbanner_src}" class="rounded mx-auto d-block" alt="{seriesbanner_title}">`
or 
`{seriesbanner}`

### stories

Templates:
- listings.tpl
- storyindex.tpl

`{storybanner} ` - insert image with HTML img tag

`{storybanner_src}` - return only image path
`{storybanner_title}` - return the title of story in format for the alt tag

Example:
`<img src="{storybanner_src}" class="rounded mx-auto d-block" alt="{storybanner_title}">`
or 
`{storybanner}`

## storyend module

Templates:
- viewstory.tpl
- storyend.tpl

`{storyend}`  
