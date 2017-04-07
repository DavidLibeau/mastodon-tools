<xsl:stylesheet version="2.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:output method="html"/>
<xsl:template match="/">
  <html>
  <body>
      <h1>Status</h1>
      <p><xsl:value-of select="/"></xsl:value-of></p>
	
  </body>
  </html>
</xsl:template>
</xsl:stylesheet>