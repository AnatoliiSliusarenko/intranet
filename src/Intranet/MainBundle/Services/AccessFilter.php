<?php

namespace Intranet\MainBundle\Services;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Intl\Intl;

class AccessFilter
{
	private $user = null;
	private $em = null;
	
	private $blacklistCountries = array('IN','CN');
	
    public function __construct($securityContext, $em)
    {
    	//$this->user = $securityContext->getToken()->getUser();
    	$this->em = $em;
    }
    
    public function getCountrySymbolByIp($ip)
    {
    	$result = file_get_contents("http://ipinfo.io/".$ip."/json");
    	
    	list($a, $b) = explode('country', $result);
    	
    	return substr($b,4,2);
    }
    
    public function getCountryNameByIp($ip)
    {
    	return Intl::getRegionBundle()->getCountryName($this->getCountrySymbolByIp($ip)); 
    }
    
    public function getAllCountries()
    {
    	return Intl::getRegionBundle()->getCountryNames();
    }
    
    public function hasAccess($ip)
    {
    	return !in_array($this->getCountrySymbolByIp($ip),$this->blacklistCountries);
    }
}
