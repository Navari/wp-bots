<?php


namespace Navari\Bot\Parser;
use DOMDocument;
use DOMElement;
use DOMNodeList;
use DOMXPath;


class SimpleDom
{
    public string $rawBody;
    public string $savedHtml;
    public DOMDocument $dom;

    public function __construct($content)
    {
        $dom = new DOMDocument();
        @$dom->loadHTML($content, LIBXML_HTML_NOIMPLIED);
        $this->dom = $dom;
    }

    public function removeElements(array $elements): void
    {
        foreach($elements as $element){
            $xpath = new DOMXPath($this->dom);
            $findElements = $xpath->query("//*[class='{$element}}']");
            foreach($findElements as $findElement){
                $findElement->parentNode->removeChild($findElement);
            }
        }
    }

    public function returnById(string $domId): bool|string
    {
        return $this->dom->saveHTML($this->dom->getElementById($domId));
    }


    public function getElementByClass($tagName, $className, $offset = 0): string
    {
        $html = '';
        $response = false;
        $childNodeList = $this->dom->getElementsByTagName($tagName);
        $tagCount = 0;
        for ($i = 0; $i < $childNodeList->length; $i++) {
            $temp = $childNodeList->item($i);
            if (stripos($temp->getAttribute('class'), $className) !== false) {
                if ($tagCount == $offset) {
                    $response = $temp;
                    break;
                }
                $tagCount++;
            }
        }
        if($response){
            $tmp_doc = new DOMDocument();
            $tmp_doc->appendChild($tmp_doc->importNode($response,true));
            $html .= $tmp_doc->saveHTML();
        }
        return $html;
    }

}
