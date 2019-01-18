<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE xsl:stylesheet PUBLIC "UNKNOWN" "https://www.w3.org/1999/11/xslt10.dtd">
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
	<xsl:output	method="html" encoding="UTF-8" omit-xml-declaration="yes" indent="no"/> 
    <xsl:template match="b|br|i|p|small|span|sub|sup">
        <xsl:copy>
            <xsl:apply-templates select="node()"/>
        </xsl:copy>
    </xsl:template>
    <xsl:template match="img">
         <xsl:element name="img">
         	<xsl:copy-of select="@src|@alt"/>
        </xsl:element>
    </xsl:template>
    <xsl:template match="a">
        <xsl:element name="a">
            <xsl:if test="starts-with(@href, 'http:') or starts-with(@href, 'https:') or starts-with(@href, 'mailto:')">
	         	<xsl:copy-of select="@href"/>
            </xsl:if>
            <xsl:apply-templates/>
        </xsl:element>
    </xsl:template>
</xsl:stylesheet>