<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$start = microtime(true);
$xmlDoc = new DOMDocument();
$xmlDoc->load("../ressources/mondial.xml");
$is_eligible    = false;
$elements       = $xmlDoc->getElementsByTagName("country");
$imp            = new DOMImplementation;
//$dtd = $imp->createDocumentType('liste-pays', '', 'ressliste-pays.dtd');
$xml            = $imp->createDocument("", "");
$xml->formatOutput = true;
$xml_liste_pays = $xml->createElement("liste-pays");
$xml->appendChild($xml_liste_pays);
if (!is_null($elements)) {
    foreach ($elements as $element) {
        $xml_pays   = $xml->createElement("pays");
        $id_capital = $element->getAttribute('capital');
        $nodes      = $element->childNodes;
        foreach ($nodes as $node) {
            if ($node->nodeName == "name") {
                $xml_nom = new DOMAttr("nom", $node->nodeValue);
                $xml_pays->appendChild($xml_nom);
            }
            if ($node->nodeName == "encompassed" && $node->hasAttributes() && $node->getAttribute('continent') == "asia" && (int) $node->getAttribute('percentage') < 100) {
                $xml_proportion_asia  = new DOMAttr("proportion-asie", (int) $node->getAttribute('percentage'));
                $xml_proportion_autre = new DOMAttr("proportion-asie", 100 - (int) $node->getAttribute('percentage'));
                $xml_pays->appendChild($xml_proportion_asia);
                $xml_pays->appendChild($xml_proportion_autre);
                $is_eligible = true;
            }
            if ($node->nodeName == "province") {
                $nodeProvinces = $node->childNodes;
                foreach ($nodeProvinces as $nodeProvince) {
                    if ($nodeProvince->nodeName == "city" && $nodeProvince->hasAttributes() && $nodeProvince->getAttribute('id') == $id_capital) {
                        $xml_pays->appendChild(new DOMAttr("capitale", $nodeProvince->firstChild->nextSibling->nodeValue));
                    }
                }
            }
        }
        if ($is_eligible) {
            $xml_liste_pays->appendChild($xml_pays);
            $is_eligible = false;
        }
    }
}
$xml->save("mondial.xml");
header("Content-type: text/xml");
$xml->saveXML();

$time_elapsed_secs = microtime(true) - $start;






echo "<h2>DOM PHP</h2>
<a href=\"dom/mondial.xml\"> fichier généré</a>
<h4>Code source</h4>
<p>Temps de traitement : $time_elapsed_secs</p>
<pre>
\$xmlDoc = new DOMDocument();
\$xmlDoc->load(\"../ressources/mondial.xml\");
\$is_eligible    = false;
\$elements       = \$xmlDoc->getElementsByTagName(\"country\");
\$imp            = new DOMImplementation;
\$xml            = \$imp->createDocument(\"\", \"\");
\$xml->formatOutput = true;
\$xml_liste_pays = \$xml->createElement(\"liste-pays\");
\$xml->appendChild(\$xml_liste_pays);
if (!is_null(\$elements)) {
    foreach (\$elements as \$element) {
        \$xml_pays   = \$xml->createElement(\"pays\");
        \$id_capital = \$element->getAttribute('capital');
        \$nodes      = \$element->childNodes;
        foreach (\$nodes as \$node) {
            if (\$node->nodeName == \"name\") {
                \$xml_nom = new DOMAttr(\"nom\", \$node->nodeValue);
                \$xml_pays->appendChild(\$xml_nom);
            }
            if (\$node->nodeName == \"encompassed\" && \$node->hasAttributes() && \$node->getAttribute('continent') == \"asia\" && (int) \$node->getAttribute('percentage') < 100) {
                \$xml_proportion_asia  = new DOMAttr(\"proportion-asie\", (int) \$node->getAttribute('percentage'));
                \$xml_proportion_autre = new DOMAttr(\"proportion-asie\", 100 - (int) \$node->getAttribute('percentage'));
                \$xml_pays->appendChild(\$xml_proportion_asia);
                \$xml_pays->appendChild(\$xml_proportion_autre);
                \$is_eligible = true;
            }
            if (\$node->nodeName == \"province\") {
                \$nodeProvinces = \$node->childNodes;
                foreach (\$nodeProvinces as \$nodeProvince) {
                    if (\$nodeProvince->nodeName == \"city\" && \$nodeProvince->hasAttributes() && \$nodeProvince->getAttribute('id') == \$id_capital) {
                        \$xml_pays->appendChild(new DOMAttr(\"capitale\", \$nodeProvince->firstChild->nextSibling->nodeValue));
                    }
                }
            }
        }
        if (\$is_eligible) {
            \$xml_liste_pays->appendChild(\$xml_pays);
            \$is_eligible = false;
        }
    }
}
 \$xml->save(\"pouet.xml\");
header(\"Content-type: text/xml\");
\$xml->saveXML();
</pre>";
?> 