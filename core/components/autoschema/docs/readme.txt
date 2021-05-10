# AutoSchema for MODX

Add-on to automatically generate JSON+LD Schema on MODX Resources.

Basic usage is to just call the Snippet as `[[AutoSchema]]`.

## Available Properties

| **Key**        | **Default Value**                                                |
| -------------- | ---------------------------------------------------------------- |
| id             | `[[*id]]`                                                        | 
| tpl            | `@INLINE: <script type="application/ld+json">[[+data]]</script>` |
| context        | http://schema.org                                                |
| type           | Article                                                          |
| headline       | `[[*pagetitle]]`                                                 |
| name           | `[[*pagetitle]]`                                                 | 
| logo           | *null* (must be complete to pass validation)                     | 
| image          | *null* (must be complete to pass validation)                     |
| keywords       | *null*                                                           | 
| description    | `[[*description]]`                                               |
| articleSection | `[[*parent]]`                                                    |
| authorName     | gets the fullname property from the createdby user               |