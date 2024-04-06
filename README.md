# Modules for efiction CMS
 

## storybanner module

This module adds banner field for story and series. Simple oneline text field for full path to image.

To use it in skin:

### series

Templates:
- listings.tpl
- seriesblock.tpl

`{seriesbanner}`  - insert image with HTML img tag

`{seriesbanner_src} `- return only image path
`{seriesbanner_title}` - return title of serie in format for alt tag

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
`{storybanner_title}` - return title of serie in format for alt tag

Example:
`<img src="{storybanner_src}" class="rounded mx-auto d-block" alt="{storybanner_title}">`
or 
`{storybanner}`
