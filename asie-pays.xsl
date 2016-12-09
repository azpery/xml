<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    xmlns:xs="http://www.w3.org/2001/XMLSchema"
    exclude-result-prefixes="xs"
    version="2.0" >
    <xsl:output method="xml" encoding="UTF-8" doctype-system="liste-pays.dtd" indent="yes"/>
    <xsl:variable name="continent" select="'asia'"/>
    <xsl:template match="/">
        <liste-pays>
            <xsl:apply-templates select="./mondial/country"/>
        </liste-pays>
        
    </xsl:template>
    <xsl:template match="country">
        <xsl:if test="encompassed[@continent = $continent and @percentage &lt; 100]">
            <xsl:variable name="capital" select="@capital" />
            <xsl:variable name="proportion" select="encompassed[@continent = $continent]/@percentage" />
            <xsl:element name="pays">
                <xsl:attribute name="nom">
                    <xsl:value-of select="name"/>
                </xsl:attribute>
                <xsl:attribute name="capitale">
                    <xsl:value-of select="province/city[@id = $capital]/name"/>
                </xsl:attribute>
                <xsl:attribute name="proportion-asie">
                    <xsl:value-of select="$proportion"/>
                </xsl:attribute>
                <xsl:attribute name="proportion-autres">
                    <xsl:value-of select="100-$proportion"/>
                </xsl:attribute>
            </xsl:element>
        </xsl:if>
    </xsl:template>
</xsl:stylesheet>