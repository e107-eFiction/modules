# Modules for efiction CMS
 

## storybanner module

This module adds a banner field for stories and series. Simple one-line text field for the full path to the image.

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
