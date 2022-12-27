# AutoSchema for MODX

Add-on to automatically generate JSON+LD Schema on MODX Resources.

Basic usage is to just call the Snippet as `[[AutoSchema]]`.

## Available Properties

| **Key**        | **Default Value**                                               |
|----------------|-----------------------------------------------------------------|
| id             | `[[*id]]`                                                       | 
| tpl            | `@INLINE <script type="application/ld+json">[[+data]]</script>` |
| context        | https://schema.org                                              |
| type           | Article                                                         |
| headline       | `[[*pagetitle]]`                                                |
| name           | `[[*pagetitle]]`                                                | 
| logo           | *null* (must be complete to pass validation)                    | 
| image          | *null* (must be complete to pass validation)                    |
| keywords       | *null*                                                          | 
| description    | `[[*description]]`                                              |
| articleSection | `[[*parent]]`                                                   |
| authorName     | gets the fullname property from the createdby user              |
| parseTags      | *true* (disable to prevent parsing modx tags in content)        |
| custom         | [] (json object to merge in to the schema)                      |