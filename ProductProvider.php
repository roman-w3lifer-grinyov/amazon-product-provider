<?php

namespace w3lifer\amazon;

class ProductProvider
{
    private $data;

    /**
     * $data - single item node form amazon api response
     * @param null $data
     */
    public function __construct($data = null)
    {
        if ($data) {
            $this->data = $data;
        }
    }

    public function setData($data)
    {
        $this->data = $data;
        return $this;
    }

    public function getPrice()
    {
        $price = (isset($this->data->OfferSummary->LowestNewPrice->Amount[0]) ? (float)$this->data->OfferSummary->LowestNewPrice->Amount[0] : 0);
        return $price/100;
    }

    public function getBrand()
    {
        return (string)($this->data->ItemAttributes->Brand[0] ?? '');
    }

    public function getSmallImage()
    {
        return (string)($this->data->SmallImage->URL[0] ?? '');
    }

    public function getMediumImage()
    {
        return (string)($this->data->MediumImage->URL[0] ?? '');
    }

    public function getLargeImage()
    {
        return (string)($this->data->LargeImage->URL[0] ?? '');
    }

    /**
     * @return array
     */
    public function getAllImages()
    {
        $images = [];
        foreach ($this->data->ImageSets->ImageSet as $imageSet) {
            $images[] = (string) $imageSet->LargeImage->URL;
        }
        return $images;
    }

    public function getTitle()
    {
        if (isset($this->data->ItemAttributes->Title[0])) {
            return substr(trim($this->data->ItemAttributes->Title[0]), 0, 254);
        } else {
            return '';
        }
    }

    /**
     * @param int $limit
     * @return string
     */
    public function getDescription($limit = 5)
    {
        if (isset($this->data->ItemAttributes->Feature)) {
            $description = '';
            $i = 1;
            foreach ($this->data->ItemAttributes->Feature as $key => $feature) {
                if ($i <= $limit) {
                    $description .= '<li>' . substr($feature, 0, 100) . '</li>';
                }
                $i++;
            }
            return $description;
        } else {
            return '';
        }
    }

    public function getColor()
    {
        return (string)($this->data->ItemAttributes->Color[0] ?? '');
    }

    public function getEAN()
    {
        return (string)($this->data->ItemAttributes->EAN[0] ?? '');
    }

    public function getModel()
    {
        return (string)($this->data->ItemAttributes->Model[0] ?? '');
    }

    public function getMPN()
    {
        return (string)($this->data->ItemAttributes->MPN[0] ?? '');
    }

    public function getProductGroup()
    {
        return (string)($this->data->ItemAttributes->ProductGroup[0] ?? '');
    }

    public function getPublisher()
    {
        return (string)($this->data->ItemAttributes->Publisher[0] ?? '');
    }

    public function getStudio()
    {
        return (string)($this->data->ItemAttributes->Studio[0] ?? '');
    }

    public function getUPC()
    {
        return (string)($this->data->ItemAttributes->UPC[0] ?? '');
    }

    public function getASIN()
    {
        return (string)($this->data->ASIN[0] ?? '');
    }

    public function getAmazonUrl()
    {
        $asin =  $this->getASIN();
        $url = ($asin ? 'https://www.amazon.com/gp/product/' . $asin : '');
        return $url;
    }

    public function getSlug()
    {
        return fromStrToUrl(trim(substr($this->data->ItemAttributes->Title[0], 0, 140)));
    }

    public function getFormattedUps()
    {
        $ean = $this->getEAN();
        $mpn = $this->getMPN();
        $upc = $this->getUPC();

        if ($upc) {
            return $upc;
        } elseif ($ean) {
            return $ean;
        } elseif ($mpn) {
            return $mpn;
        } else {
            return false;
        }
    }
}
