<?php


namespace TUDublin\dbObjects;


class CoffeeshopAddress
{
    const CREATE_TABLE_SQL =
        <<<HERE
CREATE TABLE `coffeeshopaddress` (
  `id` int NOT NULL AUTO_INCREMENT,
  `country` varchar(60) NOT NULL DEFAULT 'Ireland',
  `county` varchar(60) NOT NULL,
  `city` varchar(60) NOT NULL,
  `street1` varchar(120) NOT NULL,
  `street2` varchar(120) NULL,
  `postcode` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
);
HERE;

    private $id;
    private $country;
    private $county;
    private $city;
    private $street1;
    private $street2;
    private $postcode;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param mixed $country
     */
    public function setCountry($country)
    {
        $this->country = $country;
    }

    /**
     * @return mixed
     */
    public function getCounty()
    {
        return $this->county;
    }

    /**
     * @param mixed $county
     */
    public function setCounty($county)
    {
        $this->county = $county;
    }

    /**
     * @return mixed
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param mixed $city
     */
    public function setCity($city)
    {
        $this->city = $city;
    }


    /**
     * @return mixed
     */
    public function getStreet1()
    {
        return $this->street1;
    }

    /**
     * @param mixed $street1
     */
    public function setStreet1($street1)
    {
        $this->street1 = $street1;
    }

    /**
     * @return mixed
     */
    public function getStreet2()
    {
        return $this->street2;
    }

    /**
     * @param mixed $street2
     */
    public function setStreet2($street2)
    {
        $this->street2 = $street2;
    }

    /**
     * @return mixed
     */
    public function getPostcode()
    {
        return $this->postcode;
    }

    /**
     * @param mixed $postcode
     */
    public function setPostcode($postcode)
    {
        $this->postcode = $postcode;
    }



}
