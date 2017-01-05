<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$start = microtime(true);
$xmlDocument = new XMLWriter();
$xmlDocument->openURI("mondial.xml"); 
$xmlDocument->setIndent(true); 
$xmlDocument->setIndentString(' '); 
$xmlDocument->startDocument('1.0', 'UTF-8'); 
$xmlDocument->startElement("liste-pays"); 
$xmlReader = new XMLReader();
$xmlReader->open("../ressources/mondial.xml");
$xmlReader->read();
$tree = $xmlReader->expand();
foreach ($tree->childNodes as $country) {
	if($country->hasChildNodes()){ 
		$is_eligible = false;
		$idCapital = ($country->hasAttributes() && $country->attributes->getNamedItem("capital")) ? $country->attributes->getNamedItem("capital")->value : $idCapital;
		foreach ($country->childNodes as $countryDetail) {
			switch ($countryDetail->nodeName) {
				case 'name':
					$name = $countryDetail->nodeValue;
					break;
				case 'encompassed':
					$percentage = (int)$countryDetail->attributes->getNamedItem("percentage")->value;
					if($countryDetail->hasAttributes() && $countryDetail->attributes->getNamedItem("continent")->value == "asia" && $percentage < 100){
						$is_eligible = true;
						$proportion = (int)$countryDetail->attributes->getNamedItem("percentage")->value;
					}
					break;
				case 'province':
					foreach ($countryDetail->childNodes as $province) {
						if($is_eligible && $province->nodeName == "city" && $province->attributes->getNamedItem("id")->value == $idCapital){
							$xmlDocument->startElement("pays"); 
							$capital = $province->firstChild->nextSibling->nodeValue;
							$xmlDocument->writeAttribute("nom", $name);
							$xmlDocument->writeAttribute("proportion", $proportion);
							$xmlDocument->writeAttribute("proportion-autre", 100 - $proportion);
							$xmlDocument->writeAttribute("capital", $capital);
							$xmlDocument->endElement();
						}
					}
					break;					
				default:
					break;
			}
		}
	}
}
$xmlDocument->endElement();
$xmlDocument->endDocument();
$xmlDocument->flush(); 
unset($xmlDocument); //important!
$xmlReader->close();
$time_elapsed_secs = microtime(true) - $start;

echo "<h2>DOM PHP</h2>
<a href=\"dom/mondial.xml\"> fichier généré</a>
<h4>Code source</h4>
<p>Temps de traitement : $time_elapsed_secs</p>
<pre>
	\$xmlDocument = new XMLWriter();
	\$xmlDocument->openURI(\"mondial.xml\"); 
    \$xmlDocument->setIndent(true); 
    \$xmlDocument->setIndentString(' '); 
    \$xmlDocument->startDocument('1.0', 'UTF-8'); 
    \$xmlDocument->startElement(\"liste-pays\"); 
	\$xmlReader = new XMLReader();
	\$xmlReader->open(\"../ressources/mondial.xml\");
	\$xmlReader->read();
	\$tree = \$xmlReader->expand();
	foreach (\$tree->childNodes as \$country) {
		if(\$country->hasChildNodes()){ 
			\$is_eligible = false;
			\$idCapital = (\$country->hasAttributes() && \$country->attributes->getNamedItem(\"capital\")) ? \$country->attributes->getNamedItem(\"capital\")->value : \$idCapital;
			
			foreach (\$country->childNodes as \$countryDetail) {
				switch (\$countryDetail->nodeName) {
					case 'name':
						\$name = \$countryDetail->nodeValue;
						 
						break;
					case 'encompassed':
						\$percentage = (int)\$countryDetail->attributes->getNamedItem(\"percentage\")->value;
						if(\$countryDetail->hasAttributes() && \$countryDetail->attributes->getNamedItem(\"continent\")->value == \"asia\" && \$percentage < 100){
							\$is_eligible = true;
							\$proportion = (int)\$countryDetail->attributes->getNamedItem(\"percentage\")->value;
							
						}
						break;
					case 'province':
						foreach (\$countryDetail->childNodes as \$province) {
							if(\$is_eligible && \$province->nodeName == \"city\" && \$province->attributes->getNamedItem(\"id\")->value == \$idCapital){
								\$xmlDocument->startElement(\"pays\"); 
								\$capital = \$province->firstChild->nextSibling->nodeValue;
								\$xmlDocument->writeAttribute(\"nom\", \$name);
								\$xmlDocument->writeAttribute(\"proportion\", \$proportion);
								\$xmlDocument->writeAttribute(\"proportion-autre\", 100 - \$proportion);
								\$xmlDocument->writeAttribute(\"capital\", \$capital);
								\$xmlDocument->endElement();
							}
						}
						break;					
					default:
						# code...
						break;
				}
			}
		}
	}
	\$xmlDocument->endElement();
	\$xmlDocument->endDocument();
     \$xmlDocument->flush(); 
	unset(\$xmlDocument); //important!
	\$xmlReader->close();
</pre>";
?>