<?php


namespace Navari\Bot\Models;


class News extends AbstractModel
{
    protected string $title;
    protected string $summary;
    protected string $image;
    protected string $originLink;
    protected string $description = "";
    protected string $rawBody = "";
    protected string $tags = "";
    protected string $gallery = "";
    protected string $category = "";


    /**
     * @return string
     */
    public function getRawBody(): string
    {
        return $this->rawBody;
    }
    /**
     * @return string
     */
    public function getCategory(): string
    {
        return $this->category;
    }
    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @return string
     */
    public function getGallery(): string
    {
        return $this->gallery;
    }

    /**
     * @return string
     */
    public function getImage(): string
    {
        return $this->image;
    }

    /**
     * @return string
     */
    public function getOriginLink(): string
    {
        return $this->originLink;
    }

    /**
     * @return string
     */
    public function getSummary(): string
    {
        return $this->summary;
    }

    /**
     * @return string
     */
    public function getTags(): string
    {
        return $this->tags;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }
    /**
     * @param $value
     * @param $prop
     * @param $arr
     */
    protected function initPropertiesCustom($value, $prop, $arr): void
    {
        switch ($prop){
            case 'title':
                $this->title = $value;
                break;
            case 'summary':
                $this->summary = $value;
                break;
            case 'image':
                $this->image = $value;
                break;
            case 'link':
                $this->originLink = $value;
                break;
            case 'description':
                $this->description = $value;
                break;
            case 'tags':
                $this->tags = $value;
                break;
            case 'gallery':
                $this->gallery = $value;
                break;
            case 'category':
                $this->category = $value;
                break;
            case 'raw_body':
                $this->rawBody = $value;
                break;
        }
    }

}
