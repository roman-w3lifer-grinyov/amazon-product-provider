<?php

namespace w3lifer\amazon;

class ProductProvider
{
    /**
     * @var null|\SimpleXMLElement
     */
    private $simpleXMLElement;

    /**
     * @param null|\SimpleXMLElement $simpleXMLElement Example:
     *                                                 $simpleXmlElement->Items->Item
     */
    public function __construct($simpleXMLElement = null)
    {
        if ($simpleXMLElement) {
            $this->simpleXMLElement = $simpleXMLElement;
        }
    }

    /**
     * @return string
     */
    public function getAmazonUrl()
    {
        $url = '';

        if ($asin = $this->getAsin()) {
            $url = 'https://www.amazon.com/gp/product/' . $asin;
        }

        return $url;
    }

    /**
     * @return string
     */
    public function getAsin()
    {
        $asin = '';

        if (isset($this->simpleXMLElement->ItemAttributes->ASIN[0])) {
            $asin =
                (string)
                    $this->simpleXMLElement->ItemAttributes->ASIN[0];
        }

        return $asin;
    }

    /**
     * @return string
     */
    public function getBrand()
    {
        $brand = '';

        if ($this->simpleXMLElement->ItemAttributes->Brand[0]) {
            $brand =
                (string) $this->simpleXMLElement->ItemAttributes->Brand[0];
        }
        return $brand;
    }

    /**
     * @return string
     */
    public function getColor()
    {
        $color = '';

        if (isset($this->simpleXMLElement->ItemAttributes->Color[0])) {
            $color =
                (string)
                    $this->simpleXMLElement->ItemAttributes->Color[0];
        }

        return $color;
    }

    /**
     * @param int $numberOfItems
     * @param int $numberOfCharacters
     * @return string
     */
    public function getDescription(
        $numberOfItems = 5,
        $numberOfCharacters = 100
    ) {
        $description = '';

        if (isset($this->simpleXMLElement->ItemAttributes->Feature)) {
            $i = 0;
            foreach (
                $this->simpleXMLElement->ItemAttributes->Feature as
                $key => $feature
            ) {
                if ($i < $numberOfItems) {
                    $description .=
                        '<li>' .
                            substr($feature, 0, $numberOfCharacters) .
                        '</li>';
                }
                $i++;
            }
        }

        return $description;
    }

    /**
     * @return string
     */
    public function getEan()
    {
        $ean = '';

        if (isset($this->simpleXMLElement->ItemAttributes->EAN[0])) {
            $ean =
                (string)
                    $this->simpleXMLElement->ItemAttributes->EAN[0];
        }

        return $ean;
    }

    /**
     * @return string
     */
    public function getFormattedUps()
    {
        if ($upc = $this->getUpc()) {
            return $upc;
        } else if ($ean = $this->getEan()) {
            return $ean;
        } else if ($mpn = $this->getMpn()) {
            return $mpn;
        }
        return '';
    }

    /**
     * @return string
     */
    public function getModel()
    {
        $model = '';

        if (isset($this->simpleXMLElement->ItemAttributes->Model[0])) {
            $model =
                (string)
                    $this->simpleXMLElement->ItemAttributes->Model[0];
        }

        return $model;
    }

    /**
     * @return string
     */
    public function getMpn()
    {
        $mpn = '';

        if (isset($this->simpleXMLElement->ItemAttributes->MPN[0])) {
            $mpn =
                (string)
                    $this->simpleXMLElement->ItemAttributes->MPN[0];
        }

        return $mpn;
    }

    /**
     * @return float|int
     */
    public function getPrice()
    {
        $price = 0;

        if (isset(
            $this->simpleXMLElement->OfferSummary->LowestNewPrice->Amount[0])
        ) {
            $price =
                (float)
                    $this->simpleXMLElement->OfferSummary->LowestNewPrice->Amount[0];
        }

        return $price / 100;
    }

    /**
     * @return string
     */
    public function getProductGroup()
    {
        $productGroup = '';

        if (isset($this->simpleXMLElement->ItemAttributes->ProductGroup[0])) {
            $productGroup =
                (string)
                    $this->simpleXMLElement->ItemAttributes->ProductGroup[0];
        }

        return $productGroup;
    }

    /**
     * @return string
     */
    public function getPublisher()
    {
        $publisher = '';

        if (isset($this->simpleXMLElement->ItemAttributes->Publisher[0])) {
            $publisher =
                (string)
                    $this->simpleXMLElement->ItemAttributes->Publisher[0];
        }

        return $publisher;
    }

    /**
     * @param int $numberOfCharacters
     * @return string
     */
    public function getSlug($numberOfCharacters = 150)
    {
        $title = $this->simpleXMLElement->ItemAttributes->Title[0];
        $title = substr($title, 0, $numberOfCharacters - 1);
        $title = trim($title);
        $title = self::slugify($title);
        return $title;
    }

    /**
     * @return string
     */
    public function getStudio()
    {
        $studio = '';

        if (isset($this->simpleXMLElement->ItemAttributes->Studio[0])) {
            $studio =
                (string)
                    $this->simpleXMLElement->ItemAttributes->Studio[0];
        }

        return $studio;
    }

    /**
     * @param int $numberOfCharacters
     * @return bool|string
     */
    public function getTitle($numberOfCharacters = 255)
    {
        if (isset($this->simpleXMLElement->ItemAttributes->Title[0])) {
            return
                substr(
                    trim($this->simpleXMLElement->ItemAttributes->Title[0]),
                    0,
                    $numberOfCharacters - 1
                );
        }
        return '';
    }

    /**
     * @return string
     */
    public function getUpc()
    {
        $upc = '';

        if (isset($this->simpleXMLElement->ItemAttributes->UPC[0])) {
            $upc =
                (string)
                    $this->simpleXMLElement->ItemAttributes->UPC[0];
        }

        return $upc;
    }

    /*
     * =========================================================================
     * IMAGES
     * =========================================================================
     */

    /**
     * @return array
     */
    public function getAllSmallImages()
    {
        $images = [];

        foreach ($this->simpleXMLElement->ImageSets->ImageSet as $imageSet) {
            $images[] = (string) $imageSet->SmallImage->URL;
        }

        return $images;
    }

    /**
     * @return array
     */
    public function getAllMediumImages()
    {
        $images = [];

        foreach ($this->simpleXMLElement->ImageSets->ImageSet as $imageSet) {
            $images[] = (string) $imageSet->MediumImage->URL;
        }

        return $images;
    }

    /**
     * @return array
     */
    public function getAllLargeImages()
    {
        $images = [];

        foreach ($this->simpleXMLElement->ImageSets->ImageSet as $imageSet) {
            $images[] = (string) $imageSet->LargeImage->URL;
        }

        return $images;
    }

    /**
     * @return string
     */
    public function getSmallImage()
    {
        $smallImage = '';

        if (isset($this->simpleXMLElement->SmallImage->URL[0])) {
            $smallImage =
                (string)
                    $this->simpleXMLElement->SmallImage->URL[0];
        }

        return $smallImage;
    }

    /**
     * @return string
     */
    public function getMediumImage()
    {
        $mediumImage = '';

        if (isset($this->simpleXMLElement->MediumImage->URL[0])) {
            $mediumImage =
                (string)
                    $this->simpleXMLElement->MediumImage->URL[0];
        }

        return $mediumImage;
    }

    /**
     * @return string
     */
    public function getLargeImage()
    {
        $largeImage = '';

        if (isset($this->simpleXMLElement->LargeImage->URL[0])) {
            $largeImage =
                (string)
                    $this->simpleXMLElement->LargeImage->URL[0];
        }

        return $largeImage;
    }

    /*
     * =========================================================================
     * HELPERS
     * =========================================================================
     */

    /**
     * @param string $text
     * @return string
     */
    public static function slugify($text)
    {
        // replace non letter or digits by -
        $text = preg_replace('~[^\pL\d]+~u', '-', $text);

        // transliterate
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

        // remove unwanted characters
        $text = preg_replace('~[^-\w]+~', '', $text);

        // trim
        $text = trim($text, '-');

        // remove duplicate -
        $text = preg_replace('~-+~', '-', $text);

        // lowercase
        $text = strtolower($text);

        if (empty($text)) {
            return 'n-a';
        }

        return $text;
    }
}
