<?php
namespace AutoSchema\Snippet;

class AutoSchema extends Snippet
{
    public $resource;

    public function process()
    {
        
        $id = $this->getOption('id', $this->modx->resource->id);
        $this->resource = $this->modx->getObject('modResource', $id);
        
        if( empty($this->resource) ) return;

        $tpl = $this->getOption('tpl', '@INLINE: <script type="application/ld+json">[[+data]]</script>');

        $url = $this->modx->makeUrl($this->resource->id,$this->resource->context_key,'','full');
        $data = [
            '@context'=>$this->getOption('context','http://schema.org'),
            '@type'=>$this->getOption('type', 'Article'),
            'dateCreated'=>date('c',strtotime($this->resource->get('createdon'))),
            'datePublished'=>date('c',strtotime($this->resource->get('publishedon'))),
            'dateModified'=>date('c',strtotime($this->resource->get('editedon'))),
            'headline'=>$this->getOption('headline', $this->resource->pagetitle),
            'name'=>$this->getOption('name', $this->resource->pagetitle),
            'keywords'=>$this->getOption('keywords', null),
            'url'=>$url,
            'description'=>$this->getOption('description',$this->resource->get('description')),
            'copyrightYear'=>date('Y',strtotime($this->resource->get('publishedon'))),
            'articleSection'=>$this->getOption('articleSection', $this->resource->get('parent')),
            'articleBody'=>strip_tags($this->resource->content, '<h1><h2><h3><h4><h5><h6><ol><ul><li><p><a>'),
            'publisher'=>[
                    '@id'=>'#Publisher',
                    '@type'=>'Organization',
                    'name'=>$this->modx->getOption('site_name'),
                    'url'=>$this->modx->getOption('site_url')
            ],
            'sourceOrganization'=>['@id'=>'#Publisher'],
            'copyrightHolder'=>['@id'=>'#Publisher'],
            'mainEntityOfPage'=>[
                    '@type'=>'WebPage',
                    '@id'=>$url,
                    'breadcrumb'=>['@id'=>'#breadcrumb']
            ],
        ];
        
        $author = $this->modx->getObject('modUser', $this->resource->createdby);
        $profile = $author->getOne('Profile');
        if(!empty($profile)){
            $data['author'] = [
                    '@type'=>'Person',
                    'name'=>$profile->fullname,
            ];
        }
        
        $authorName = $this->getOption('authorName', null);
        if($authorName){
            $data['author'] = [
                    '@type'=>'Person',
                    'name'=>$authorName,
            ];
        }

        $json = json_encode($data);

        if($tpl){
            return $this->getChunk($tpl, [
                'data' => $data,
            ]);
        }else{
            return $data;
        }
    }
}