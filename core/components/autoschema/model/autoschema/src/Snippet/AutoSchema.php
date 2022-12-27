<?php
namespace AutoSchema\Snippet;

class AutoSchema extends Snippet
{
    public $resource;

    public function process()
    {
        
        $id = $this->getOption('id', $this->modx->resource->id);
        $this->resource = $this->modx->getObject('modResource', $id);
        
        if( empty($this->resource) ){
            $this->modx->log(xPDO::LOG_LEVEL_ERROR, 'AutoSchema: No Resource Provided');
            return; 
        };

        $tpl = $this->getOption('tpl', '@INLINE <script type="application/ld+json">[[+data]]</script>');

        $url = $this->modx->makeUrl($this->resource->id,$this->resource->context_key,'','full');
        $data = [
            '@context'=>$this->getOption('context','https://schema.org'),
            '@type'=>explode(',', $this->getOption('type', 'Article')),
            'dateCreated'=>date('c',strtotime($this->resource->get('createdon'))),
            'datePublished'=>date('c',strtotime($this->resource->get('publishedon'))),
            'dateModified'=>date('c',strtotime($this->resource->get('editedon'))),
            'headline'=>$this->getOption('headline', $this->resource->pagetitle),
            'name'=>$this->getOption('name', $this->resource->pagetitle),
            'keywords'=>$this->getOption('keywords', null),
            'url'=>$url,
            'description'=>$this->removeCode($this->getOption('description',$this->resource->get('description'))),
            'copyrightYear'=>date('Y',strtotime($this->resource->get('publishedon'))),
            'articleSection'=>$this->getOption('articleSection', $this->resource->get('parent')),
            'articleBody'=>$this->removeCode($this->resource->content),
            'publisher'=>[
                    '@id'=>'#Publisher',
                    '@type'=>'Organization',
                    'name'=>$this->modx->getOption('site_name'),
                    'url'=>$this->modx->getOption('site_url'),
                    'logo'=>$this->getOption('logo', null)
            ],
            'sourceOrganization'=>['@id'=>'#Publisher'],
            'copyrightHolder'=>['@id'=>'#Publisher'],
            'mainEntityOfPage'=>[
                    '@type'=>'WebPage',
                    '@id'=>$url.'#content'
            ],
            'image'=>$this->getOption('image',null)
        ];
        
        $author = $this->modx->getObject('modUser', $this->resource->createdby);
        if(!empty($author)){
            $profile = $author->getOne('Profile');
            if(!empty($profile)){
                $data['author'] = [
                        '@type'=>'Person',
                        'name'=>$profile->fullname,
                ];
            }
        }
        
        $authorName = $this->getOption('authorName', null);
        if($authorName){
            $data['author'] = [
                    '@type'=>'Person',
                    'name'=>$authorName,
            ];
        }
        $custom = $this->getOption('custom', null);
        if($custom){
            $data = array_merge($data, (array)json_encode($custom));
        }

        $json = json_encode($data);

        if($tpl){
            return $this->getChunk($tpl, [
                'data' => $json,
            ]);
        }

        return $json;
    }

    private function removeCode($content = null): string
    {
        $content = strip_tags($content, '<h1><h2><h3><h4><h5><h6><ol><ul><li><p><a>');
        if(!$this->getOption('parseTags', true)){
            $content = str_replace('[[','[[-', $content);
        }
        $content = trim(preg_replace('/\s+/', ' ', $content));
        return $content; 
    }
}