<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE xsl:stylesheet PUBLIC "UNKNOWN" "https://www.w3.org/1999/11/xslt10.dtd">
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
	<xsl:output	method="html" encoding="UTF-8" omit-xml-declaration="yes" indent="no"/>
	<xsl:template match="*">
		<xsl:apply-templates/>
	</xsl:template>
</xsl:stylesheet>