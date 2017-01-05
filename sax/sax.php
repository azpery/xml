<?php
	include_once("Sax4PHP.php");
	class sax extends DefaultHandler{
		public $xml_liste_pays;
		public $xml;
		public $val = "";
		private $proportion;
		private $idCapital = "";
		private $capital = "";
		private $pays;
		private $flag_country = false;
		private $flag_city = false;
		private $flag_capital = false;
		private $flag_asia = false;
		function __construct() {
        	parent::__construct();
	        $imp = new DOMImplementation;
	        //$dtd = $imp->createDocumentType('liste-pays', '', 'ressliste-pays.dtd');
	        $this->xml = $imp->createDocument("", "");
	        $this->xml_liste_pays = $this->xml->createElement("liste-pays");
	        $this->xml->appendChild($this->xml_liste_pays);
	    }
		function startElement($name, $att) {
			switch ($name) {
				case "country":
					$this->flag_country = true;
					if (isset($att["capital"])) {
						$this->idCapital = (string)$att["capital"];
					}
					break;
				case "province":
					$this->flag_country = false;
					break;
				case "city":
					$this->flag_city = true;
					if ((string)$att["id"] == $this->idCapital) {
						$this->flag_capital = true;
					}
					break;
				case 'encompassed':
					if((string)$att["continent"] == "asia" && (int)$att['percentage'] < 100){
						$this->flag_asia = true;
						$this->proportion = (int)$att['percentage'];
					}
					break;
				case "name":
						if ($this->flag_country) {
						}
					break;
			}
		}
		function endElement($name) {
			switch ($name) {
				case 'country':
					if ($this->flag_asia) {
						$xml_pays = $this->xml->createElement("pays");
						$xml_nom = new DOMAttr("nom", $this->pays);
						$xml_capital = new DOMAttr("capitale", $this->capital);
						$xml_proportion_asia = new DOMAttr("proportion-asie", $this->proportion);
						$xml_proportion_autre = new DOMAttr("proportion-autre", 100 - $this->proportion);
	   					$xml_pays->appendChild($xml_nom);
	   					$xml_pays->appendChild($xml_capital);
	   					$xml_pays->appendChild($xml_proportion_asia);
	   					$xml_pays->appendChild($xml_proportion_autre);
	   					$this->xml_liste_pays->appendChild($xml_pays);
					}
					$this->flag_asia = false;
					$this->flag_city = false;
					break;
				case "name":
						if ($this->flag_country) {
							$this->pays = $this->val;
						}
						if ($this->flag_capital == true) {
							$this->capital = $this->val;
							$this->flag_capital = false;
						}
					break;
					break;
			}
			$this->val = "";
		} 

		function characters($txt){
		    $txt=trim($txt);
		    if (!(empty($txt))){
		      $this->val .= $txt;
		    }
		}

		function startDocument() {
			
		} 
		function endDocument() {
			$this->xml->save("mondial.xml");
		}
	}
?>